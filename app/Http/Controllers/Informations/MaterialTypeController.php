<?php

namespace App\Http\Controllers\Informations;

use App\Http\Controllers\Controller;
use App\MaterialTypeModel;
use Illuminate\Http\Request;

use function PHPSTORM_META\type;

class MaterialTypeController extends Controller
{
    //
    public function editType(Request $request)
    {
        if($request->type_name && $request->id){
            $id = $request->id;
            $typeName = $request->type_name;
            $type = MaterialTypeModel::where("id_type", $id)->first();
            error_log($type);
            if($type != null){
                $type->type_name = $typeName;
                $res = $type->save();

                if($res != null){
                    return response()->json(['status' => '0', 'massage' => 'แก้ไขสำเร็จ', 'data' => $type]);
                }else {
                    return response()->json(['status' => '1', 'massage' => 'แก้ไขไม่สำเร็จ']);
                }
            }else {
                return response()->json(['status' => '2', 'massage' => 'ไม่พบข้อมูล']);
            }
        }
    }
    public function deleteTypeById(Request $request)
    {
        $id = $request->id;
        if($id){
            $type = MaterialTypeModel::where("id_type", $id)->first();
            if($type != null){
                $res = $type->delete();
                if($res != null){
                    return response()->json(['status' => '0', 'massage' => 'ลบประเภทวัสดุสำเร็จ', 'data' => $type]);
                }else {
                    return response()->json(['status' => '1', 'massage' => 'ลบประเภทวัสดุไม่สำเร็จ']);
                }
            }else {
                return response()->json(['status' => '2', 'massage' => 'ไม่พบข้อมูล']);
            }
        }
    }

    public function addType(Request $request)
    {
        $type = MaterialTypeModel::where('type_name', $request->type_name)->first();

        if ($type === null) {
            $typeModel = new MaterialTypeModel;
            $typeModel->type_name = $request->type_name;
            $res = $typeModel->save();

            if ($res != null) {
                return response()->json(['status' => '0', 'massage' => 'เพิ่มประเภทวัสดุสำเร็จ', 'data' => $typeModel]);
            } else {
                return response()->json(['status' => '1', 'massage' => 'เพิ่มประเภทวัสดุไม่สำเร็จ']);
            }
        }else {
                return response()->json(['status' => '2', 'massage' => 'มีประเภทวัสดุนี้อยู่แล้ว']);
        }
    }
}
