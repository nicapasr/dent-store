<?php

namespace App\Http\Controllers\Informations;

use App\Http\Controllers\Controller;
use App\DepartmentModel;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    //
    public function editDep(Request $request)
    {
        if($request->dep_name && $request->id){
            $id = $request->id;
            $depName = $request->dep_name;
            $dep = DepartmentModel::where("id_dep", $id)->first();
            error_log($dep);
            if($dep != null){
                $dep->dep_name = $depName;
                $res = $dep->save();

                if($res != null){
                    return response()->json(['status' => '0', 'massage' => 'แก้ไขสำเร็จ', 'data' => $dep]);
                }else {
                    return response()->json(['status' => '1', 'massage' => 'แก้ไขไม่สำเร็จ']);
                }
            }else {
                return response()->json(['status' => '2', 'massage' => 'ไม่พบข้อมูล']);
            }
        }
    }
    public function deleteDepById(Request $request)
    {
        $id = $request->id;
        if($id){
            $dep = DepartmentModel::where("id_dep", $id)->first();
            if($dep != null){
                $res = $dep->delete();
                if($res != null){
                    return response()->json(['status' => '0', 'massage' => 'ลบหน่วยงานสำเร็จ', 'data' => $dep]);
                }else {
                    return response()->json(['status' => '1', 'massage' => 'ลบหน่วยงานไม่สำเร็จ']);
                }
            }else {
                return response()->json(['status' => '2', 'massage' => 'ไม่พบข้อมูล']);
            }
        }
    }
    public function addDep(Request $request)
    {
        $dep = DepartmentModel::where('dep_name', $request->dep_name)->first();

        if ($dep === null) {
            $depModel = new DepartmentModel;
            $depModel->dep_name = $request->dep_name;
            $res = $depModel->save();

            if ($res != null) {
                return response()->json(['status' => '0', 'massage' => 'เพิ่มหน่วยงานสำเร็จ', 'data' => $depModel]);
            } else {
                return response()->json(['status' => '1', 'massage' => 'เพิ่มหน่วยงานไม่สำเร็จ']);
            }
        }else {
                return response()->json(['status' => '2', 'massage' => 'มีหน่วยงานนี้อยู่แล้ว']);
        }
    }
}
