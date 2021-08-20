<?php

namespace App\Http\Controllers\Informations;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GlobalConstant;
use App\Http\Controllers\Response\Response;
use App\MaterialModel;
use App\UnitModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MaterialController extends Controller
{
    public function editMaterial(Request $request)
    {
        if ($request->ajax()) {
            $res = new Response;

            $msg = [
                'm_code.required' => 'กรุณากรอกรหัสวัสดุ',
                'm_code.string' => 'รหัสวัสดุเป็นตัวหนังสือเท่านั้น',
                'm_name.required' => 'กรุณากรอกชื่อวัสดุ',
                'm_name.string' => 'ชื่อวัสดุเป็นตัวหนังสือเท่านั้น',
                'm_unit.required' => 'กรุณาเลือกหน่วยวัสดุ',
            ];

            $valid = Validator::make($request->all(), [
                'm_code' => 'required|string',
                'm_name' => 'required|string',
                'm_unit' => 'required',
            ], $msg);

            if ($valid->fails()) {
                $res->setResponse(400, $valid->errors(), null);
                return response()->json($res->getResponse());
            }

            try {
                DB::beginTransaction();

                $id = $request->m_code;
                $materialName = $request->m_name;
                $materialUnit = $request->m_unit;

                $material = MaterialModel::where("m_code", $id)->first();

                if (!$material) {
                    $res->setResponse(500, GlobalConstant::$EMPTY_TITLE);
                    return response()->json($res->getResponse());
                }

                $material->m_name = $materialName;
                $material->m_unit = $materialUnit;
                $material->save();

                DB::commit();

                $res->setResponse(200, null, $material);
                return response()->json($res->getResponse());

            } catch (\Throwable $th) {
                //throw $th;
                DB::rollback();

                $res->setResponse(500, $th->getMessage(), null);
                return response()->json($res->getResponse());
            }
        }
    }

    public function deleteMaterialById(Request $request)
    {
        $id = $request->m_code;
        if ($id) {
            $material = MaterialModel::where("m_code", $id)->first();
            if ($material != null) {
                $res = $material->delete();
                if ($res != null) {
                    return response()->json(['status' => '0', 'massage' => 'ลบประเภทวัสดุสำเร็จ', 'data' => $material]);
                } else {
                    return response()->json(['status' => '1', 'massage' => 'ลบประเภทวัสดุไม่สำเร็จ']);
                }
            } else {
                return response()->json(['status' => '2', 'massage' => 'ไม่พบข้อมูล']);
            }
        }
    }

    public function getUnitAll()
    {
        $unit = UnitModel::get();
        $date = Carbon::now();
        return view('modal.unitModal')->with('unit', $unit);
    }
}
