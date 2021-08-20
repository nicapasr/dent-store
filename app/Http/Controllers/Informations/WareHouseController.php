<?php

namespace App\Http\Controllers\Informations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\GlobalConstant;
use App\Http\Controllers\Response\Response;
use App\WareHouseModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class WareHouseController extends Controller
{
    public function editWarehouse(Request $request)
    {
        if ($request->ajax()) {
            $res = new Response;

            $valid = Validator::make($request->all(), [
                'id' => 'required',
                'warehouse_name' => 'required',
            ]);

            if ($valid->fails()) {
                $res->setResponse(400, $valid->errors(), null);
                return response()->json($res->getResponse());
            }

            try {
                DB::beginTransaction();

                $id = $request->id;
                $warehouseName = $request->warehouse_name;
                $warehouse = WareHouseModel::where("id_warehouse", $id)->first();

                if (!$warehouse) {
                    $res->setResponse(400, GlobalConstant::$EMPTY_TITLE, null);
                    return response()->json($res->getResponse());
                }

                $warehouse->warehouse_name = $warehouseName;
                $warehouse->save();

                DB::commit();

                return response()->json(['status' => '0', 'massage' => 'แก้ไขสำเร็จ', 'data' => $warehouse]);
            } catch (\Throwable $th) {
                //throw $th;
                DB::rollback();

                $res->setResponse(500, $th->getMessage(), null);
                return response()->json($res->getResponse());
            }
        }
    }
    public function deleteWarehouseById(Request $request)
    {
        if ($request->ajax()) {
            $res = new Response;

            $valid = Validator::make($request->all(), [
                'id' => 'required',
            ]);

            if ($valid->fails()) {
                $res->setResponse(400, $valid->errors(), null);
                return response()->json($res->getResponse());
            }

            try {
                DB::beginTransaction();

                $id = $request->id;
                $warehouse = WareHouseModel::where("id_warehouse", $id)->first();

                if (!$warehouse) {
                    $res->setResponse(400, GlobalConstant::$EMPTY_TITLE, null);
                    return response()->json($res->getResponse());
                }

                $warehouse->delete();

                DB::commit();

                return response()->json(['status' => '0', 'massage' => 'ลบคลังวัสดุสำเร็จ', 'data' => $warehouse]);
            } catch (\Throwable $th) {
                //throw $th;
                DB::rollback();

                $res->setResponse(500, $th->getMessage(), null);
                return response()->json($res->getResponse());
            }
        }
    }
    public function addWarehouse(Request $request)
    {
        if ($request->ajax()) {
            $res = new Response;

            $msg = [
                'warehouse_name.required' => 'กรุณากรอกชื่อคลัง',
                'warehouse_name.unique' => 'มีคลังนี้แล้ว',
            ];

            $valid = Validator::make($request->all(), [
                'warehouse_name' => 'required|unique:warehouse,warehouse_name',
            ], $msg);

            if ($valid->fails()) {
                $res->setResponse(400, $valid->errors(), null);
                return response()->json($res->getResponse());
            }

            try {
                DB::beginTransaction();

                $warehouse = WareHouseModel::where('warehouse_name', $request->warehouse_name)->first();

                if ($warehouse === null) {
                    $warehouseModel = new WareHouseModel;
                    $warehouseModel->warehouse_name = $request->warehouse_name;
                    $warehouseModel->save();

                    DB::commit();

                    return response()->json(['status' => '0', 'massage' => 'เพิ่มคลังวัสดุสำเร็จ', 'data' => $warehouseModel]);
                }
            } catch (\Throwable $th) {
                //throw $th;
                DB::rollback();

                $res->setResponse(500, $th->getMessage(), null);
                return response()->json($res->getResponse());
            }
        }
    }
}
