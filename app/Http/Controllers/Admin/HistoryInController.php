<?php

namespace App\Http\Controllers\Admin;

use App\BalanceModel;
use App\Http\Controllers\Controller;
use App\Http\Controllers\GlobalConstant;
use App\Http\Controllers\Response\Response;
use App\StockInModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class HistoryInController extends Controller
{
    public function historyInView()
    {
        $stockIn = StockInModel::with('balances.materials.materialUnit')
            ->orderBy('date_in', 'desc')
            ->orderBy('id_stock_in', 'desc')
            ->paginate(15);
        return view('reports.history_in')->with('stockIn', $stockIn);
    }

    public function searchStockIn(request $request)
    {
        if ($request->ajax()) {
            $searchText = $request->search;

            $stockIn = StockInModel::where('id_stock_in', 'like', '%' . $searchText . '%')
                ->orWhere('date_in', 'like', '%' . $searchText . '%')
                ->orWhereHas('warehouses', function ($query) use ($searchText) {
                    $query->where('warehouse_name', 'like', '%' . $searchText . '%');
                })
                ->orWhereHas('materials', function ($query) use ($searchText) {
                    $query->where('m_name', 'like', '%' . $searchText . '%');
                })->paginate(15);

            $html = view('table.history_in_table_body')->with('stockIn', $stockIn)->render();
            // return view('table.history_table_body')->with('stockIn', $stockIn)->with('stockOut', $stockOut);
            return response()->json(['status' => 200, 'data' => $html]);
        }
    }

    public function stockInFetchData(Request $request)
    {
        if ($request->ajax()) {
            $res = new Response;
            try {
                $searchText = $request->get('searchText');

                $stockIn = StockInModel::where('id_stock_in', 'like', '%' . $searchText . '%')
                    ->orWhere('date_in', 'like', '%' . $searchText . '%')
                    ->orWhereHas('warehouses', function ($query) use ($searchText) {
                        $query->where('warehouse_name', 'like', '%' . $searchText . '%');
                    })
                    ->orWhereHas('materials', function ($query) use ($searchText) {
                        $query->where('m_name', 'like', '%' . $searchText . '%');
                    })->paginate(15);

                $html = view('table.history_in_table_body')->with('stockIn', $stockIn)->render();
                $res->setResponse(200, null, $html);
                return response()->json($res->getResponse());
            } catch (\Throwable $th) {
                // throw $th;
                $res->setResponse(500, $th->getMessage(), null);
                return response()->json($res->getResponse());
            }
        }
    }

    public function update(Request $request)
    {
        $message = [
            'id_stock_in.required' => 'กรุณากรอกรหัสใบนำเข้าวัสดุ',
            'id_stock_in.integer' => 'รหัสใบนำเข้าวัสดุ : ข้อมูลเป็นตัวเลขจำนวนเต็มบวกเท่านั้น',
            'value.required' => 'กรุณากรอกจำนวนที่ต้องการนำเข้า',
            'value.numeric' => 'จำนวนที่ต้องการนำเข้า : ข้อมูลเป็นตัวเลขจำนวนเต็มบวกเท่านั้น',
            'value.min' => 'จำนวนที่ต้องการนำเข้า : ข้อมูลน้อยสุดคือ 1',
        ];

        $valid = Validator::make($request->all(), [
            'id_stock_in' => 'required|integer',
            'value' => ['required', 'numeric', 'min:1'],
        ], $message);

        if ($valid->fails()) {
            return redirect()->route('admin.history.in.view')
                ->withErrors($valid)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $res = new Response;
            $stockIn = StockInModel::where('id_stock_in', $request->id_stock_in)->first();
            $balance = BalanceModel::where('id', $stockIn->balance_id)->with('materials')->first();

            if (!$stockIn || !$balance) {
                throw new Exception(GlobalConstant::$EMPTY_TITLE);
            }

            $oldBalance = $balance->b_value - $stockIn->value_in;
            $newBalance = $oldBalance + $request->value;
            $balance->b_value = $newBalance;
            $balance->save();

            $stockIn->value_in = $request->value;
            // $stockIn->balance_id = $balance->id;
            $stockIn->total_price_in = $request->value * $balance->materials->m_price_unit;
            $stockIn->save();

            DB::commit();

            alert()->success(GlobalConstant::$SUCCESS_TITLE, 'แก้ไขรายการนำเข้าวัสดุ ' . $balance->materials->m_name . ' เรียบร้อยแล้ว');
            return redirect()->route('admin.history.in.view');
        } catch (\Throwable $th) {
            // throw $th;
            DB::rollback();
            $res->setResponse(500, $th->getMessage(), null);
            alert()->error(GlobalConstant::$ERROR_TITLE, $res->getResponse());
            return redirect()->route('admin.history.in.view');
        }
    }

    public function cancel(Request $request, $stockId = null)
    {
        if ($request->ajax()) {
            $res = new Response;
            try {
                DB::beginTransaction();

                $stockIn = StockInModel::where('id_stock_in', $stockId)->with('balances.materials.materialUnit')->first();
                $balance = BalanceModel::where('id', $stockIn->balance_id)->first();

                if (!$stockIn || !$balance) {
                    throw new Exception(GlobalConstant::$EMPTY_TITLE);
                }

                // $balance->b_value = $balance->b_value - $stockIn->value_in;

                $stockIn->delete();
                // $balance->save();
                $balance->delete();

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
}
