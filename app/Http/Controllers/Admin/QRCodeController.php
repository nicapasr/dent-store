<?php

namespace App\Http\Controllers\Admin;

use App\BalanceModel;
use App\Http\Controllers\GlobalConstant;
use App\Http\Controllers\Response\Response;
use App\MaterialModel;
use App\MemberModel;
use App\Rules\GreaterThanEqualBalance;
// use App\ImportantModel;
use App\StockInModel;
use App\StockOutModel;
use App\WareHouseModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\RequiredIf;

class QRCodeController extends BaseController
{

    public function showQrcode()
    {
        $materials = MaterialModel::orderBy('m_name', 'asc')
            ->paginate(30);
        return view('qrcode.qrcode_show')->with('materials', $materials);
    }

    public function scanOutView()
    {
        return view('qrcode.qrcode_scan_out');
    }

    public function scanInView()
    {
        $warehouses = WareHouseModel::all();
        return view('qrcode.qrcode_scan_in')->with('warehouses', $warehouses);
    }

    public function search(Request $request)
    {
        if ($request->ajax()) {
            $res = new Response;

            try {
                $searchText = $request->get('searchText');

                $materials = MaterialModel::where('m_code', 'like', "%$searchText%")
                    ->orWhere('m_name', 'like', "%$searchText%")
                    ->orderBy('m_name', 'asc')
                    ->paginate(30);

                $html = view('table.qrcode_table_body')->with('materials', $materials)->render();
                $res->setResponse(200, null, $html);
                return response()->json($res->getResponse());
            } catch (\Throwable $th) {
                //throw $th;
                $res->setResponse(500, $th->getMessage(), null);
                return response()->json($res->getResponse());
            }
        }
    }

    public function getMaterialByCode(Request $request, $m_code = null)
    {
        if ($request->ajax() && $m_code) {
            $material = MaterialModel::where('m_code', $m_code)->first();

            if ($material) {
                return response()->json(['status' => 200, 'body' => $material]);
            }
            return response()->json(['status' => 500, 'message' => 'material not found'], 500);
        }
    }

    public function getStockOutsList(Request $request)
    {
        if ($request->ajax()) {
            $res = new Response;

            $msg = [
                'm_code.required' => 'กรุณากรอกรหัสวัสดุ',
            ];

            $valid = Validator::make($request->all(), [
                'm_code' => 'required',
            ], $msg);

            if ($valid->fails()) {
                return response()->json(['status' => 400, 'message' => 'Validator error', 'errors' => $valid->errors()]);
            }

            $m_code = $request->m_code;
            $balances = BalanceModel::where('material_id', $m_code)
                ->where('b_value', '!=', null)
                ->whereHas('materials', function ($q) {
                    $q->orderBy('m_name', 'asc');
                })
                ->with('materials.materialUnit')
                ->orderBy('b_exp_date', 'asc')
                ->first();

            $res->setResponse(200, null, $balances);
            return response()->json($res->getResponse());
        }
    }

    public function updateStockInExp(Request $request)
    {
        if ($request->ajax()) {
            $message = [
                'm_code.required' => 'กรุณาการอกรหัสของวัสดุ',
                'm_code.string' => 'รหัสวัสดุ : ข้อมูลเป็นตัวเลขเท่านั้น',
                'm_in.required' => 'กรุณาการอกจำนวนนำเข้า',
                'm_in.numeric' => 'จำนวนนำเข้า : ข้อมูลเป็นตัวเลขเท่านั้น',
                'warehouse.required' => 'กรุณาการอกคลังที่เบิกมา',
                'warehouse.integer' => 'คลัง : ข้อมูลเป็นตัวเลขเท่านั้น',
                'date_exp.required_if' => 'กรุณาเลือกวันหมดอายุ',
                'date_exp.date' => 'วันที่หมดอายุรูปแบบไม่ถูกต้อง',
                'date_exp.after' => 'วันที่หมดอายุต้องมากกว่าปัจุบัน',
            ];

            $valid = Validator::make($request->all(), [
                'm_code' => 'required|string',
                'm_in' => 'required|numeric',
                'warehouse' => 'required|integer',
                'date_exp' => [new RequiredIf(function () use ($request) {
                    $temp = MaterialModel::where('m_code', $request->m_code)->first();
                    return $temp->m_exp_date != null && $temp->m_exp_date === 1 ? true : false;
                }), 'date', 'after:now'],
            ], $message);

            if ($valid->fails()) {
                return response()->json(['status' => 400, 'message' => 'Validator error', 'errors' => $valid->errors()]);
            }

            try {
                DB::beginTransaction();

                $res = new Response;

                $code = $request->m_code;
                $value = $request->m_in;
                $warehouse = $request->warehouse;

                $material = MaterialModel::where('m_code', $code)->first();

                if (!$material) {
                    throw new Exception(GlobalConstant::$EMPTY_TITLE);
                }

                if ($request->date_exp != null && $request->date_exp != '') {
                    $expDate = date('Y-m-d', strtotime($request->date_exp));

                    $balance = BalanceModel::where('material_id', $request->m_code)->where('b_exp_date', $expDate)->first();

                    if (!$balance) {
                        $balance = new BalanceModel;
                        $balance->material_id = $request->m_code;
                        $balance->b_value = $balance->b_value + $request->m_in;
                        $balance->b_exp_date = $expDate;
                        $balance->save();
                    } else {
                        $balance->material_id = $request->m_code;
                        $balance->b_value = $balance->b_value + $request->m_in;
                        $balance->b_exp_date = $expDate;
                        $balance->save();
                    }

                    $material->m_exp_date = true;
                    $material->save();
                }

                $stockIn = new StockInModel;
                $stockIn->ware_house = $warehouse;
                $stockIn->balance_id = $balance->id;
                $stockIn->value_in = $value;
                $stockIn->date_in = date('Y-m-d');
                $stockIn->total_price_in = $request->m_price_unit * $value;
                $stockIn->save();

                DB::commit();

                $res->setResponse(200, null, $stockIn);
                return response()->json($res->getResponse());
            } catch (\Throwable $th) {
                //throw $th;
                DB::rollback();

                $res->setResponse(500, $th->getMessage(), null);
                return response()->json($res->getResponse());
            }
        }
    }

    public function updateStockIn(Request $request)
    {
        if ($request->ajax()) {
            $message = [
                'm_code.required' => 'กรุณาการอกรหัสของวัสดุ',
                'm_code.string' => 'รหัสวัสดุ : ข้อมูลเป็นตัวเลขเท่านั้น',
                'm_in.required' => 'กรุณาการอกจำนวนนำเข้า',
                'm_in.numeric' => 'จำนวนนำเข้า : ข้อมูลเป็นตัวเลขเท่านั้น',
                'warehouse.required' => 'กรุณาการอกคลังที่เบิกมา',
                'warehouse.integer' => 'คลัง : ข้อมูลเป็นตัวเลขเท่านั้น',
            ];

            $valid = Validator::make($request->all(), [
                'm_code' => 'required|string',
                'm_in' => 'required|numeric',
                'warehouse' => 'required|integer',
            ], $message);

            if ($valid->fails()) {
                return response()->json(['status' => 400, 'message' => 'Validator error', 'errors' => $valid->errors()]);
            }

            try {
                DB::beginTransaction();

                $res = new Response;

                $code = $request->m_code;
                $value = $request->m_in;
                $warehouse = $request->warehouse;

                $material = MaterialModel::where('m_code', $code)->first();

                if (!$material) {
                    throw new Exception(GlobalConstant::$EMPTY_TITLE);
                }

                $balance = BalanceModel::where('material_id', $code)->where('b_exp_date', null)->first();

                if (!$balance) {
                    $balance = new BalanceModel;
                    $balance->material_id = $request->m_code;
                    $balance->b_value = $balance->b_value + $value;
                    $balance->save();
                } else {
                    $balance->material_id = $request->m_code;
                    $balance->b_value = $balance->b_value + $value;
                    $balance->save();
                }

                $material->m_exp_date = false;
                $material->save();

                $stockIn = new StockInModel;
                $stockIn->ware_house = $warehouse;
                $stockIn->balance_id = $balance->id;
                $stockIn->value_in = $value;
                $stockIn->date_in = date('Y-m-d');
                $stockIn->total_price_in = $request->m_price_unit * $value;
                $stockIn->save();

                DB::commit();

                $res->setResponse(200, null, $stockIn);
                return response()->json($res->getResponse());
            } catch (\Throwable $th) {
                //throw $th;
                DB::rollback();

                $res->setResponse(500, $th->getMessage(), null);
                return response()->json($res->getResponse());
            }
        }
    }

    public function getMembers(Request $request)
    {
        if ($request->ajax()) {
            $res = new Response;
            $members = MemberModel::all();
            $data = collect($members)->map(function ($collection, $key) {
                $collect = (object) $collection;
                $fullName = $collect->fname . ' ' . $collect->lname;
                return $fullName;
            });

            $res->setResponse(200, null, $data);
            return response()->json($res->getResponse());
        }
    }

    public function updateStockOut(Request $request)
    {
        if ($request->ajax()) {

            $res = new Response;

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
                $res->setResponse(400, $valid->errors(), null);
                return response()->json($res->getResponse());
            }

            try {
                DB::beginTransaction();

                $id = $request->id;
                $value_out = $request->width_draw_value;
                $exp_date = $request->exp_date;
                $room = $request->room;
                $member = explode(" ", $request->member);

                $balance = BalanceModel::where('id', $id)->where('b_exp_date', $exp_date)->first();
                $member_db = MemberModel::where('fname', $member[0])->where('lname', $member[1])->first();

                if ($balance && $member_db) {
                    $newBalance = $balance->b_value - $value_out;
                    $balance->b_value = $newBalance;
                    $balance->save();

                    $stockOut = new StockOutModel;
                    $stockOut->member_id = $member_db->id;
                    $stockOut->balance_id = $balance->id;
                    $stockOut->room = $room;
                    $stockOut->value_out = $value_out;
                    $stockOut->total_price_out = $balance->materials->m_price_unit * $value_out;
                    $stockOut->date_out = date('Y-m-d');
                    $stockOut->save();
                }

                DB::commit();

                $res->setResponse(200, null, $stockOut);
                return response()->json($res->getResponse());
            } catch (\Throwable $th) {
                //throw $th;
                DB::rollback();

                $res->setResponse(500, $th->getMessage(), null);
                return response()->json($res->getResponse());
            }
        }
    }
}
