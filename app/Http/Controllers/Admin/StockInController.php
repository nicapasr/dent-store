<?php

namespace App\Http\Controllers\Admin;

use App\BalanceModel;
use App\Http\Controllers\Controller;
use App\Http\Controllers\GlobalConstant;
use App\Http\Controllers\Response\Response;
use App\MaterialModel;
use App\StockInModel;
use App\WareHouseModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\RequiredIf;

class StockInController extends Controller
{
    //
    public function index()
    {
        $wareHouses = WareHouseModel::all();
        return view('admin.material.stock_in')->with('wareHouses', $wareHouses);
    }

    public function search(Request $request)
    {
        if ($request->ajax()) {
            $res = new Response;

            try {
                $searchText = $request->search;

                if ($searchText == "") {
                    $res->setResponse(200, null, null);
                    return response()->json($res->getResponse());
                }

                $materials = MaterialModel::where('m_code', 'like', "%$searchText%")
                    ->orWhere('m_name', 'like', "%$searchText%")
                    ->get(['m_code', 'm_name', 'm_exp_date'])
                    ->take(10);

                $html = view('search.list_material')->with('materials', $materials)->render();
                // $data = collect($materials)->map(function($collection, $key)
                // {
                //     $collect = (object)$collection;
                //     $text = $collect->m_code . ' ' . $collect->m_name . ' ' . $collect->m_exp_date;
                //     return [
                //         'value' => $collect->m_code,
                //         'text' => $text,
                //         'exp' => $collect->m_exp_date
                //     ];
                // });
                $res->setResponse(200, null, $html);
                return response()->json($res->getResponse());
            } catch (\Throwable $th) {
                $res->setResponse(500, $th->getMessage(), null);
                return response()->json($res);
            }
        }
    }

    public function updateMaterial(Request $request)
    {
        $message = [
            'm_code.required' => 'กรุณากรอกรหัสวัสดุ',
            'm_warehouses.required' => 'กรุณาเลือกคลัง',
            // 'm_price_unit.required' => 'กรุณากรอกราคาต่อหน่วยวัสดุ',
            // 'm_price_unit.integer' => 'ราคาต่อหน่วย : ข้อมูลเป็นตัวเลขจำนวนเต็มบวกเท่านั้น',
            // 'm_price_unit.min' => 'ราคาต่อหน่วย : ข้อมูลน้อยสุดคือ 1',
            // 'm_price_unit.digits' => 'ราคาต่อหน่วย : ข้อมูลมากสุดได้ 10 หลัก',
            'm_in.required' => 'กรุณากรอกจำนวนวัสดุที่นำเข้า',
            'm_in.integer' => 'จำนวนนำเข้า : ข้อมูลเป็นตัวเลขจำนวนเต็มบวกเท่านั้น',
            'm_in.min' => 'จำนวนนำเข้า : ข้อมูลน้อยสุดคือ 1',
            'm_in.digits' => 'จำนวนนำเข้า : ข้อมูลมากสุดได้ 10 หลัก',
            'date_exp.required_if' => 'กรุณาเลือกวันหมดอายุ',
            'date_exp.date' => 'วันที่หมดอายุรูปแบบไม่ถูกต้อง',
            'date_exp.after' => 'วันที่หมดอายุต้องมากกว่าปัจุบัน',
        ];

        $valid = Validator::make($request->all(), [
            'm_code' => 'required|string|max:128',
            'm_warehouses' => 'required|integer',
            'm_in' => 'required|integer|min:1',
            'date_exp' => [new RequiredIf(function () use ($request) {
                $temp = MaterialModel::where('m_code', $request->m_code)->first();
                return $temp->m_exp_date != null && $temp->m_exp_date === 1 ? true : false;
            }), 'date', 'after:now'],
        ], $message);

        if ($valid->fails()) {
            return redirect()->route('admin.material.stock.in.update')
                ->withErrors($valid)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $material = MaterialModel::where('m_code', $request->m_code)->with('materialUnit')->first();

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
            } else {
                $balance = BalanceModel::where('material_id', $request->m_code)->where('b_exp_date', null)->first();

                if (!$balance) {
                    $balance = new BalanceModel;
                    $balance->material_id = $request->m_code;
                    $balance->b_value = $balance->b_value + $request->m_in;
                    $balance->save();
                } else {
                    $balance->material_id = $request->m_code;
                    $balance->b_value = $balance->b_value + $request->m_in;
                    $balance->save();
                }

                $material->m_exp_date = false;
                $material->save();
            }

            $stockIn = new StockInModel;
            $stockIn->ware_house = $request->m_warehouses;
            $stockIn->balance_id = $balance->id;
            $stockIn->value_in = $request->m_in;
            $stockIn->date_in = date('Y-m-d');
            $stockIn->total_price_in = $request->m_price_unit * $request->m_in;

            $stockIn->save();

            DB::commit();

            alert()->success(GlobalConstant::$SUCCESS_TITLE, "เพิ่มวัสดุ " . $material->m_code . " " . $material->m_name . " จำนวน " . $request->m_in . " " . $material->materialUnit->unit_name . " เรียบร้อยแล้ว");
            return redirect()->route('admin.material.stock.in.update');

        } catch (\Throwable $th) {
            DB::rollBack();
            alert()->error(GlobalConstant::$ERROR_TITLE, $th->getMessage());
            return redirect()->route('admin.material.stock.in.update');
        }

        // try {
        //     if ($request->date_exp != null && $request->date_exp != '') {

        //         $expDate = date('Y-m-d', strtotime($request->date_exp));
        //         $temp = MaterialModel::where('m_code', $request->m_code)->with('balances')->first();

        //         //หากเพิ่มเข้ามามีวันที่หมดอายุเดิมของวัสดุเดิมอยู่แล้ว
        //         if ($temp && $temp->m_exp_date && $temp->balances->first()->b_exp_date === $expDate) {
        //             $balance = BalanceModel::where('material_id', $temp->m_code)
        //                 ->where('b_exp_date', $expDate)->first();
        //             $balance->b_value = $balance->b_value + $request->m_in;

        //             $stockIn = new StockInModel;
        //             $stockIn->ware_house = $request->m_warehouses;
        //             $stockIn->material_id = $request->m_code;
        //             $stockIn->balance_id = $balance->id;
        //             $stockIn->date_in = date('Y-m-d');
        //             $stockIn->value_in = $request->m_in;
        //             $stockIn->total_price_in = $request->m_price_unit * $request->m_in;

        //             if ($balance->save() && $stockIn->save()) {
        //                 alert()->success("สำเร็จ", "เพิ่มวัสดุ " . $temp->m_code . " " . $temp->m_name . " จำนวน " . $request->m_in . " " . $temp->materialUnit->unit_name . " เรียบร้อยแล้ว");
        //                 return redirect()->route('admin.material.stock.in.update');
        //             }
        //         } else if ($temp && $temp->m_exp_date && $temp->balances->first()->b_exp_date != $expDate){
        //             $balance = new BalanceModel;
        //             $balance->material_id = $request->m_code;
        //             $balance->b_value = $request->m_in;
        //             $balance->b_exp_date = $expDate;

        //             $stockIn = new StockInModel;
        //             $stockIn->ware_house = $request->m_warehouses;
        //             $stockIn->material_id = $request->m_code;
        //             $stockIn->balance_id = $balance->id;
        //             $stockIn->date_in = date('Y-m-d');
        //             $stockIn->value_in = $request->m_in;
        //             $stockIn->total_price_in = $request->m_price_unit * $request->m_in;

        //             if ($balance->save() && $stockIn->save()) {
        //                 alert()->success("สำเร็จ", "เพิ่มวัสดุ " . $temp->m_code . " " . $temp->m_name . " จำนวน " . $request->m_in . " " . $temp->materialUnit->unit_name . " เรียบร้อยแล้ว");
        //                 return redirect()->route('admin.material.stock.in.update');
        //             }
        //         }

        //     } else {
        //         $temp = MaterialModel::where('m_code', $request->m_code)->first();
        //         $balance = BalanceModel::where('material_id', $request->m_code)->first();

        //         if ($temp != null && $balance != null) {
        //             //มีวัสดุแบบไม่หมดอายุ
        //             $balance->material_id = $temp->m_code;
        //             $balance->b_value = $balance->b_value + $request->m_in;
        //             $quryBalance = $balance->save();

        //             $stockIn = new StockInModel;
        //             $stockIn->ware_house = $request->m_warehouses;
        //             $stockIn->material_id = $request->m_code;
        //             $stockIn->balance_id = $balance->id;
        //             $stockIn->date_in = date('Y-m-d');
        //             $stockIn->value_in = $request->m_in;
        //             $stockIn->total_price_in = $request->m_price_unit * $request->m_in;
        //             $quryStockIn = $stockIn->save();

        //             if ($quryBalance && $quryStockIn) {
        //                 alert()->success("สำเร็จ", "เพิ่มวัสดุ " . $request->m_code . " " . $request->m_name . " จำนวน " . $request->m_in . " " . $temp->materialUnit->unit_name . " เรียบร้อยแล้ว");
        //                 return redirect()->route('admin.material.stock.in.update');
        //             }
        //         }else {
        //             $balance = new BalanceModel;
        //             $balance->material_id = $temp->m_code;
        //             $balance->b_value = $balance->b_value + $request->m_in;
        //             $quryBalance = $balance->save();

        //             $stockIn = new StockInModel;
        //             $stockIn->ware_house = $request->m_warehouses;
        //             $stockIn->material_id = $request->m_code;
        //             $stockIn->date_in = date('Y-m-d');
        //             $stockIn->value_in = $request->m_in;
        //             $stockIn->total_price_in = $request->m_price_unit * $request->m_in;
        //             $quryStockIn = $stockIn->save();

        //             if ($quryBalance && $quryStockIn) {
        //                 alert()->success("สำเร็จ", "เพิ่มวัสดุ " . $request->m_code . " " . $request->m_name . " จำนวน " . $request->m_in . " " . $temp->materialUnit->unit_name . " เรียบร้อยแล้ว");
        //                 return redirect()->route('admin.material.stock.in.update');
        //             }
        //         }
        //     }
        // } catch (\Throwable $th) {
        //     alert()->error("พบข้อผิดพลาด", $th->getMessage());
        //     return redirect()->route('admin.material.stock.in.update');
        // }
    }
}
