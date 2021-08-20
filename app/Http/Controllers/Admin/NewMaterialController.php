<?php

namespace App\Http\Controllers\Admin;

use App\BalanceModel;
use App\Http\Controllers\Controller;
use App\Http\Controllers\GlobalConstant;
use App\Http\Controllers\Response\Response;
use App\MaterialModel;
use App\StockInModel;
use App\UnitModel;
use App\WareHouseModel;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class NewMaterialController extends Controller
{
    public function index()
    {
        $wareHouses = WareHouseModel::orderBy('warehouse_name', 'asc')->get();
        $units = UnitModel::orderBy('unit_name', 'asc')->get();

        return view('admin.material.add')->with('wareHouses', $wareHouses)->with('units', $units);
    }

    public function addMaterial(Request $request)
    {
        $message = [
            'm_code.required' => 'กรุณากรอกรหัสวัสดุ',
            'm_code.unique' => 'มีรหัสวัสดุนี้แล้ว',
            // 'm_type.required' => 'กรุณาเลือกประเภทวัสดุ',
            // 'date_in.required' => 'กรุณาเลือกวันที่นำเข้า',
            'm_warehouses.required' => 'กรุณาเลือกคลัง',
            'm_name.required' => 'กรุณากรอกชื่อวัสดุ',
            'm_unit.required' => 'กรุณาเลือกหน่วยวัสดุ',
            // 'm_price_unit.required' => 'กรุณากรอกราคาต่อหน่วยวัสดุ',
            // 'm_price_unit.min' => 'ราคาต่อหน่วย : ข้อมูลเป็นตัวเลขจำนวนเต็มบวกเท่านั้น',
            'm_in.required' => 'กรุณากรอกจำนวนวัสดุที่นำเข้า',
            'm_in.min' => 'จำนวนนำเข้า : ข้อมูลเป็นตัวเลขจำนวนเต็มบวกเท่านั้น',
        ];

        $valid = Validator::make($request->all(), [
            'm_code' => ['required', 'string', 'unique:material,m_code'],
            // 'm_type' => 'required',
            // 'date_in' => 'required',
            'm_warehouses' => 'required',
            'm_name' => 'required',
            'm_unit' => 'required',
            // 'm_price_unit' => 'required|integer|min:1',
            'm_in' => 'required|integer|min:1',
            'checkbox_exp' => 'string',
            'm_exp_date' => 'required_if:checkbox_exp,==,true',
        ], $message);

        if ($valid->fails()) {
            return redirect()->route('admin.material.new.view')
                ->withErrors($valid)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            //ไม่มีวัสดุ
            $material = new MaterialModel;
            $material->m_code = $request->m_code;
            $material->m_name = $request->m_name;
            $material->m_unit = $request->m_unit;

            if ($request->checkbox_exp == 'true') {
                $material->m_exp_date = true;
            }

            $material->save();

            $balance = new BalanceModel;
            $balance->material_id = $request->m_code;
            $balance->b_value = $request->m_in;

            if ($request->checkbox_exp == 'true') {
                $date = str_replace('/', '-', $request->m_exp_date);
                $balance->b_exp_date = date("Y-m-d", strtotime($date));
            }

            $balance->save();

            $stockIn = new StockInModel;
            $stockIn->ware_house = $request->m_warehouses;
            $stockIn->balance_id = $balance->id;
            $stockIn->date_in = date("Y-m-d");
            $stockIn->value_in = $request->m_in;

            $stockIn->save();

            DB::commit();

            alert()->success(GlobalConstant::$SUCCESS_TITLE, 'เพิ่มวัสดุ ' . $request->m_name . ' จำนวน ' . $request->m_in . ' ' . $material->materialUnit->unit_name . ' เรียบร้อยแล้ว');
            return redirect()->route('admin.material.new.view');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();

            alert()->error(GlobalConstant::$ERROR_TITLE, $th->getMessage());
            return redirect()->route('admin.material.new.view');
        }
    }
}
