<?php

namespace App\Http\Controllers\Admin;

use App\BalanceModel;
use App\Http\Controllers\Controller;
use App\Http\Controllers\GlobalConstant;
use App\Http\Controllers\Response\Response;
use App\MemberModel;
use App\Rules\GreaterThanEqualBalance;
use App\StockOutModel;
use App\UnitModel;
use App\WareHouseModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StockOutController extends Controller
{
    public function index()
    {
        $balances = BalanceModel::where('b_value', '!=', null)
            ->whereHas('materials', function ($q) {
                $q->orderBy('m_name', 'asc');
            })
            ->orderBy('b_exp_date', 'asc')
            ->groupBy('material_id', 'b_exp_date')
            ->paginate(15);

        $wareHouses = WareHouseModel::all();
        $units = UnitModel::all();
        return view('admin.material.stock_out')->with('balances', $balances)->with('wareHouses', $wareHouses)->with('units', $units);
    }

    public function fetch_data(Request $request)
    {
        if ($request->ajax()) {
            $res = new Response;
            try {
                $searchText = $request->get('searchText');
                $balances = BalanceModel::where('b_exp_date', 'like', "%$searchText%")
                    ->orWhereHas('materials', function ($q) use ($searchText) {
                        $q->where('m_code', 'like', "%$searchText%")
                            ->orWhere('m_name', 'like', "%$searchText%");
                    })
                    ->groupBy('material_id', 'b_exp_date')
                    ->paginate(15);

                $html = view('table.stock_out_table_body')->with('balances', $balances)->render();
                $res->setResponse(200, null, $html);
                return response()->json($res->getResponse());
            } catch (\Throwable $th) {
                // throw $th;
                $res->setResponse(500, $th->getMessage(), null);
                return response()->json($res->getResponse());
            }
        }
    }

    public function getUserByName(Request $request)
    {
        if ($request->ajax()) {
            try {
                $member = $request->post('member');
                $members = MemberModel::where('fname', 'like', "%$member%")
                    ->orWhere('lname', 'like', "%$member%")
                    ->orderBy('fname', 'asc')
                    ->get()
                    ->take(10);
                // $html = view('search.list_members')->with('members', $members)->render();
                // return response()->json(['status' => 200, 'data' => $html]);
                $data = collect($members)->map(function ($collection, $key) {
                    $collect = (object) $collection;
                    $fullName = $collect->fname . ' ' . $collect->lname;
                    return ['value' => $collect->id, 'text' => $fullName];
                });
                return response()->json(['status' => 200, 'data' => $data]);
            } catch (\Throwable $th) {
                return response()->json(['status' => 500, 'message' => $th->getMessage()], 500);
            }
        }
    }

    public function widthDraw(Request $request)
    {
        $message = [
            'id.required' => 'กรุณากรอกรหัสรายการเบิก',
            'member.required' => 'กรุณากรอกผู้ที่ขอเบิก',
            'room.required' => 'กรุณากรอกห้องที่เบิก',
            'room.integer' => 'ห้องที่เบิก : ข้อมูลเป็นตัวเลขจำนวนเต็มบวกเท่านั้น',
            'room.min' => 'ห้องที่เบิก : ข้อมูลน้อยสุดคือ 0',
            'room.digits' => 'ห้องที่เบิก : ข้อมูลมากสุดได้ 10 หลัก',
            'width_draw_value.required' => 'กรุณากรอกจำนวนที่ต้องการเบิก',
            'width_draw_value.integer' => 'จำนวนที่ต้องการเบิก : ข้อมูลเป็นตัวเลขจำนวนเต็มบวกเท่านั้น',
            'width_draw_value.min' => 'จำนวนที่ต้องการเบิก : ข้อมูลน้อยสุดคือ 1',
            'width_draw_value.digits' => 'จำนวนที่ต้องการเบิก : ข้อมูลมากสุดได้ 10 หลัก',
        ];

        $valid = Validator::make($request->all(), [
            'id' => 'required|integer',
            'member' => 'required|string|max:255',
            'room' => 'required|numeric|min:0',
            'width_draw_value' => ['required', 'numeric', 'min:1', new GreaterThanEqualBalance($request->id)],
        ], $message);

        if ($valid->fails()) {
            return redirect('/admin/material/stock/out/')
                ->withErrors($valid)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $value_out = $request->width_draw_value;
            $exp_date = $request->exp_date;
            $room = $request->room;
            $member = $request->member;

            $balance = BalanceModel::where('id', $request->id)->where('b_exp_date', $exp_date)->first();

            if ($balance != null) {
                $newBalance = $balance->b_value - $value_out;
                $balance->b_value = $newBalance;
                $balance->save();

                $stockOut = new StockOutModel;
                $stockOut->member_id = $member;
                $stockOut->balance_id = $balance->id;
                $stockOut->room = $room;
                $stockOut->value_out = $value_out;
                $stockOut->total_price_out = $balance->materials->m_price_unit * $value_out;
                $stockOut->date_out = date('Y-m-d');
                $stockOut->save();

                DB::commit();

                alert()->success(GlobalConstant::$SUCCESS_TITLE, 'เบิกวัสดุ ' . $balance->materials->m_name . ' จำนวน ' . $value_out . ' ' . $balance->materials->materialUnit->unit_name . ' เรียบร้อยแล้ว');
                return redirect()->route('admin.material.stock.out.view');
            }
            throw new Exception("ไม่พบรายการที่ต้องการเบิก กรุณาลองใหม่อีกครั้ง");
        } catch (\Throwable $th) {
            // throw $th;
            DB::rollBack();
            alert()->error(GlobalConstant::$ERROR_TITLE, $th->getMessage());
            return redirect()->route('admin.material.stock.out.view');
        }
    }
}
