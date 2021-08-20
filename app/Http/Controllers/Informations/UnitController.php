<?php

namespace App\Http\Controllers\Informations;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GlobalConstant;
use App\Http\Controllers\Response\Response;
use App\UnitModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UnitController extends Controller
{
    public function editUnit(Request $request)
    {
        if ($request->ajax()) {

            $res = new Response;

            $msg = [
                'id.required' => 'กรุณากรอกรหัสหน่วยวัสดุ',
                'unit_name.required' => 'กรุณากรอกชื่อหน่วยวัสดุ',
                'unit_name.string' => 'ชื่อหน่วยวัสดุเป็นตัวหนังสือเท่านั้น',
            ];

            $valid = Validator::make($request->all(), [
                'id' => 'required',
                'unit_name' => 'required|string',
            ], $msg);

            if ($valid->fails()) {
                $res->setResponse(400, $valid->errors(), null);
                return response()->json($res->getResponse());
            }

            try {
                DB::beginTransaction();

                $id = $request->id;
                $unitName = $request->unit_name;
                $unit = UnitModel::where("id_unit", $id)->first();

                if (!$unit) {
                    $res->setResponse(500, GlobalConstant::$EMPTY_TITLE);
                    return response()->json($res->getResponse());
                }

                $unit->id_unit = $id;
                $unit->unit_name = $unitName;
                $unit->save();

                DB::commit();

                $res->setResponse(200, null, $unit);
                return response()->json($res->getResponse());
            } catch (\Throwable $th) {
                //throw $th;
                DB::rollback();

                $res->setResponse(500, $th->getMessage(), null);
                return response()->json($res->getResponse());
            }
        }
    }

    public function deleteUnitById(Request $request)
    {
        $id = $request->id;

        if ($id) {
            $unit = UnitModel::where("id_unit", $id)->first();
            if ($unit != null) {
                $res = $unit->delete();
                if ($res != null) {
                    return response()->json(['status' => '0', 'massage' => 'ลบหน่วยวัสดุสำเร็จ', 'data' => $unit]);
                } else {
                    return response()->json(['status' => '1', 'massage' => 'ลบหน่วยวัสดุไม่สำเร็จ']);
                }
            } else {
                return response()->json(['status' => '2', 'massage' => 'ไม่พบข้อมูล']);
            }
        }
    }

    public function addUnit(Request $request)
    {
        $unit = UnitModel::where('unit_name', $request->unit_name)->first();

        if ($unit === null) {
            $unitModel = new UnitModel;
            $unitModel->unit_name = $request->unit_name;
            $res = $unitModel->save();

            if ($res != null) {
                return response()->json(['status' => '0', 'massage' => 'เพิ่มหน่วยวัสดุสำเร็จ', 'data' => $unitModel]);
            } else {
                return response()->json(['status' => '1', 'massage' => 'เพิ่มหน่วยวัสดุไม่สำเร็จ']);
            }
        } else {
            return response()->json(['status' => '2', 'massage' => 'มีหน่วยวัสดุนี้อยู่แล้ว']);
        }
    }
}
