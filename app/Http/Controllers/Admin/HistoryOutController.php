<?php

namespace App\Http\Controllers\Admin;

use App\BalanceModel;
use App\Http\Controllers\Controller;
use App\Http\Controllers\GlobalConstant;
use App\Http\Controllers\Response\Response;
use App\MaterialModel;
use App\Rules\GreaterThanEqualMaterial;
use App\StockOutModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class HistoryOutController extends Controller
{
    public function historyOutView()
    {

        $stockOut = StockOutModel::with('members', 'balances.materials.materialUnit')
            ->orderBy('date_out', 'desc')
            ->orderBy('id_stock_out', 'desc')
            ->paginate(15);
        return view('reports.history_out')->with('stockOut', $stockOut);
    }

    public function searchStockOut(request $request)
    {
        if ($request->ajax()) {
            $searchText = $request->search;

            $stockOut = StockOutModel::where('date_out', 'like', '%' . $searchText . '%')
                ->orWhereHas('materials', function ($query) use ($searchText) {
                    $query->where('m_name', 'like', '%' . $searchText . '%');
                })
                ->orderBy('date_out', 'desc')
                ->paginate(15);
            $html = view('table.history_out_table_body')->with('stockOut', $stockOut)->render();
            // return view('table.history_table_body')->with('stockIn', $stockIn)->with('stockOut', $stockOut);
            return response()->json(['status' => 200, 'data' => $html]);
        }
    }

    public function stockOutFetchData(Request $request)
    {
        if ($request->ajax()) {
            $res = new Response;
            try {
                $searchText = $request->get('searchText');

                $stockOut = StockOutModel::where('date_out', 'like', '%' . $searchText . '%')
                    ->orWhereHas('materials', function ($query) use ($searchText) {
                        $query->where('m_name', 'like', '%' . $searchText . '%');
                    })
                    ->orderBy('date_out', 'desc')
                    ->paginate(15);

                $html = view('table.history_out_table_body')->with('stockOut', $stockOut)->render();
                // return view('table.history_table_body')->with('stockIn', $stockIn)->with('stockOut', $stockOut);
                $res->setResponse(200, null, $html);
                return response()->json($res->getResponse());
            } catch (\Throwable $th) {
                //throw $th;
                $res->setResponse(500, $th->getMessage(), null);
                return response()->json($res->getResponse());
            }
        }
    }

    public function update(Request $request)
    {
        $message = [
            'id_stock_out.required' => 'กรุณากรอกรหัสใบเบิกวัสดุ',
            'id_stock_out.integer' => 'รหัสใบเบิกวัสดุ : ข้อมูลเป็นตัวเลขจำนวนเต็มบวกเท่านั้น',
            'room.required' => 'กรุณากรอกห้องที่เบิก',
            'room.integer' => 'ห้องที่เบิก : ข้อมูลเป็นตัวเลขจำนวนเต็มบวกเท่านั้น',
            'room.min' => 'ห้องที่เบิก : ข้อมูลน้อยสุดคือ 0',
            'width_draw_value.required' => 'กรุณากรอกจำนวนที่ต้องการเบิก',
            'width_draw_value.numeric' => 'จำนวนที่ต้องการเบิก : ข้อมูลเป็นตัวเลขจำนวนเต็มบวกเท่านั้น',
            'width_draw_value.min' => 'จำนวนที่ต้องการเบิก : ข้อมูลน้อยสุดคือ 1',
        ];

        $valid = Validator::make($request->all(), [
            'id_stock_out' => 'required|integer',
            'room' => 'required|numeric|min:0',
            'width_draw_value' => ['required', 'numeric', 'min:1', new GreaterThanEqualMaterial($request->id_stock_out)],
        ], $message);

        if ($valid->fails()) {
            return redirect()->route('admin.history.out.view')->withErrors($valid)->withInput();
        }

        try {
            DB::beginTransaction();

            $stockOuts = StockOutModel::where('id_stock_out', $request->id_stock_out)->first();
            $balance = BalanceModel::where('id', $stockOuts->balance_id)->with('materials')->first();

            if (!$stockOuts || !$balance) {
                throw new Exception(GlobalConstant::$EMPTY_TITLE);
            }

            $oldBalance = $stockOuts->value_out + $balance->b_value;
            $newBalance = $oldBalance - $request->width_draw_value;
            $balance->b_value = $newBalance;
            $balance->save();

            $stockOuts->room = $request->room;
            $stockOuts->value_out = $request->width_draw_value;
            $stockOuts->total_price_out = $request->width_draw_value * $balance->materials->m_price_unit;
            $stockOuts->save();

            DB::commit();

            alert()->success(GlobalConstant::$SUCCESS_TITLE, 'แก้ไขรายการเบิกวัสดุ ' . $balance->materials->m_name . ' คุณ' . $stockOuts->members->fname . ' ' . $stockOuts->members->lname);
            return redirect()->route('admin.history.out.view');
        } catch (\Throwable $th) {
            // throw $th;
            DB::rollback();
            alert()->error(GlobalConstant::$ERROR_TITLE, $th->getMessage());
            return redirect()->route('admin.history.out.view');
        }
    }

    public function cancel(Request $request, $stockId = null)
    {
        if ($request->ajax()) {
            $res = new Response;
            try {
                DB::beginTransaction();

                $stockOut = StockOutModel::where('id_stock_out', $stockId)->with('members')->with('balances.materials.materialUnit')->first();
                $balance = BalanceModel::where('id', $stockOut->balance_id)->first();

                if (!$stockOut || !$balance) {
                    throw new Exception(GlobalConstant::$EMPTY_TITLE);
                }

                $newBalance = $balance->b_value + $stockOut->value_out;
                $balance->b_value = $newBalance;
                $balance->save();

                $stockOut->delete();

                DB::commit();

                $res->setResponse(200, null, $stockOut);
                return response()->json($res->getResponse());
            } catch (\Throwable $th) {
                //throw $th;
                DB::rollback();

                $res->setResponse(500, null, $th->getMessage());
                return response()->json($res->getResponse());
            }
        }
    }
}
