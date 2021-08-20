<?php

namespace App\Http\Controllers\Boards;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ExcelClass\ReportStockInData;
use App\Http\Controllers\ExcelClass\ReportStockInType;
use App\Http\Controllers\ExcelClass\ReportStockInWarehouse;
use App\Http\Controllers\ExcelClass\ReportStockOutData;
use App\Http\Controllers\ExcelClass\ReportStockOutType;
use App\Http\Controllers\ExcelClass\ReportStockOutWarehouse;
use App\StockInModel;
use App\StockOutModel;
use App\WareHouseModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ReportsController extends Controller
{
    public function index()
    {
        $month = ['01' => 'มกราคม',
            '02' => 'กุมภาพันธ์',
            '03' => 'มีนาคม',
            '04' => 'เมษายน',
            '05' => 'พฤษภาคม',
            '06' => 'มิถุนายน',
            '07' => 'กรกฎาคม',
            '08' => 'สิงหาคม',
            '09' => 'กันยายน',
            '10' => 'ตุลาคม',
            '11' => 'พฤศจิกายน',
            '12' => 'ธันวาคม',
        ];

        $year = date("Y");
        $years = [];
        array_push($years, $year);
        for ($i = 1; $i < 5; $i++) {
            $year -= 1;
            array_push($years, $year);
        }
        $stockIn = StockInModel::get();
        return view('reports.reports')->with('stocksIn', $stockIn)->with('months', $month)->with('years', $years);
    }

    public function listDataForChart()
    {
        $stockIn = StockInModel::get();
        $list = array();
        $count = 0;

        for ($i = 0; $i < 12; $i++) {
            $count = 0;
            foreach ($stockIn as $stock) {
                if (substr($stock->date_in, 5, 2) == ('0'+($i + 1))) {
                    $month = $this->convertDateToThai($stock->date_in);
                    $count += $stock->value_in;
                }
            }
            if ($count != 0) {
                array_push($list, ['month' => $month, 'count' => $count]);
            }
        }
        return response()->json(['status' => 0, 'message' => 'load data success', 'list' => $list]);
    }

    public function reportView(Request $request)
    {
        $msg = [
            'report.required' => 'กรุณาเลือกรูปแบบรายงาน',
        ];

        $valid = Validator::make($request->all(), [
            'report' => 'required',
        ], $msg);

        if ($valid->fails()) {
            return redirect()->route('reports.home')->withErrors($valid);
        }

        if ($request->report == 'date') {
            return view('reports.report_date');
        }
        // else if ($request->report == 'year') {
        //     return view('reports.report_year');
        // }
        else if ($request->report == 'warehouse') {
            $warehouse = WareHouseModel::all();
            return view('reports.report_warehouse')->with('warehouses', $warehouse);
        }
        // else if ($request->report == 'type') {
        //     $type = MaterialTypeModel::all();
        //     return view('reports.report_type')->with('types', $type);
        // } else if ($request->report == 'compare_price') {
        //     return view('reports.report_price');
        // }
    }

    //ทดสอยเรียกไฟล์ excel
    // public function test()
    // {
    //     try {
    //         $csvFile = public_path('file/' . 'materialList.csv');
    //         $fileProvider = new FileProvider;
    //         $lists = $fileProvider->csvToArray($csvFile, ',');
    //         $count = 0;
    //         for ($i=$count; $i < 4132; $i++) {
    //             $unitName = str_replace(' ', '', $lists[$i]['m_unit']);
    //             $units = UnitModel::where('unit_name', $unitName)->get('id_unit')->first();
    //             $count += 1;
    //             error_log($count);
    //             if ($units->id_unit == '' || $units->id_unit == null) {
    //                 error_log('NO ID');
    //             }
    //         }
    //     } catch (\Throwable $th) {
    //         error_log('ERROR');
    //         throw $th;
    //     }
    // }

    public function excelReportDateStockIn(Request $request)
    {
        $from = $request->excel_from;
        $to = $request->excel_to;
        return Excel::download(new ReportStockInData($from, $to), 'StockIn_' . $from . '-' . $to . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    public function excelReportDateStockOut(Request $request)
    {
        $from = $request->from_excel;
        $to = $request->to_excel;
        return Excel::download(new ReportStockOutData($from, $to), 'StockOut_' . $from . '-' . $to . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    public function excelReportWarehouseStockIn(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'stock_in_warehouse_excel' => 'required',
            'stock_in_year_excel' => 'required',
        ]);

        if ($valid->fails()) {
            return response()->json(['status' => 400, 'error_message' => 'Validator error', 'data' => $valid->errors()]);
        }

        try {
            $warehouse = $request->stock_in_warehouse_excel;
            $year = $request->stock_in_year_excel;

            $warehouseName = WareHouseModel::where('id_warehouse', $warehouse)->first('warehouse_name');

            if ($warehouseName) {
                return Excel::download(new ReportStockInWarehouse($warehouse, $year), 'StockIn_จาก_' . $warehouseName->warehouse_name . '_ของปี_' . $year . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
            }
        } catch (\Throwable $th) {
            //throw $th;
            error_log($th->getMessage());
        }
    }

    public function excelReportWarehouseStockOut(Request $request)
    {
        $warehouse = $request->warehouse_excel;
        $year = $request->year_excel;

        $valid = Validator::make($request->all(), [
            'stock_out_warehouse_excel' => 'required',
            'stock_out_year_excel' => 'required',
        ]);

        if ($valid->fails()) {
            return response()->json(['status' => 400, 'error_message' => 'Validator error', 'data' => $valid->errors()]);
        }

        try {
            $warehouse = $request->stock_out_warehouse_excel;
            $year = $request->stock_out_year_excel;

            $warehouseName = WareHouseModel::where('id_warehouse', $warehouse)->first('warehouse_name');

            if ($warehouseName) {
                return Excel::download(new ReportStockOutWarehouse($warehouse, $year), 'StockOut_จาก_ส่วนกลาง_ของปี_' . $year . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
            }
        } catch (\Throwable $th) {
            //throw $th;
            error_log($th->getMessage());
        }
    }

    public function excelReportTypeStockIn(Request $request)
    {
        $type = $request->type_excel;
        $year = $request->year_excel;
        if ($type == '1') {
            return Excel::download(new ReportStockInType($type, $year), 'StockIn_วัสดุสิ้นเปลือง_' . $year . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        } else if ($type == '2') {
            return Excel::download(new ReportStockInType($type, $year), 'StockIn_วัสดุมีวันหมดอายุ_' . $year . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        } else {
            return Excel::download(new ReportStockInType($type, $year), 'StockIn_เครื่องมือ_' . $year . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        }
    }

    public function excelReportTypeStockOut(Request $request)
    {
        $type = $request->excel_type;
        $year = $request->excel_year;
        if ($type == '1') {
            return Excel::download(new ReportStockOutType($type, $year), 'StockIn_วัสดุสิ้นเปลือง_' . $year . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        } else if ($type == '2') {
            return Excel::download(new ReportStockOutType($type, $year), 'StockIn_วัสดุมีวันหมดอายุ_' . $year . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        } else {
            return Excel::download(new ReportStockOutType($type, $year), 'StockIn_เครื่องมือ_' . $year . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        }
    }

    public function stockInlistAll(Request $request)
    {
        if ($request->ajax()) {
            $year = date('Y');
            $stockIn = StockInModel::whereYear('date_in', '=', $year)
                ->selectRaw('stock_in.balance_id, SUM(value_in) as sum')
                ->with('materials', 'materials.materialUnit')
                ->groupBy('balance_id')
                ->get();
            return response()->json(['status' => 200, 'data' => $stockIn]);
        }
    }

    public function stockInOrderBySum(Request $request)
    {
        if ($request->ajax()) {
            $year = $request->get('year');
            $orderBy = $request->get('orderBy');

            if (is_null($orderBy)) {
                $stockIn = StockInModel::whereYear('date_in', '=', $year)
                    ->selectRaw('stock_in.material_id, SUM(value_in) as sum')
                    ->with('materials', 'materials.materialUnit')
                    ->groupBy('material_id')
                    ->get();
            } else {
                $stockIn = StockInModel::whereYear('date_in', '=', $year)
                    ->selectRaw('stock_in.material_id, SUM(value_in) as sum')
                    ->with('materials', 'materials.materialUnit')
                    ->groupBy('material_id')
                    ->orderBy('sum', $orderBy)
                    ->get();
            }

            return response()->json(['status' => 200, 'data' => $stockIn]);
        }
    }

    public function stockOutlistAll(Request $request)
    {
        if ($request->ajax()) {
            $year = date('Y');
            $stockOut = StockOutModel::whereYear('date_out', '=', $year)
                ->selectRaw('stock_out.material_id, SUM(value_out) as sum')
                ->with('materials', 'materials.materialUnit')
                ->groupBy('material_id')
                ->get();
            return response()->json(['status' => 200, 'data' => $stockOut]);
        }
    }

    public function stockOutOrderBySum(Request $request)
    {
        if ($request->ajax()) {
            $year = $request->get('year');
            $orderBy = $request->get('orderBy');

            if (is_null($orderBy)) {
                $stockOut = StockOutModel::whereYear('date_out', '=', $year)
                    ->selectRaw('stock_out.material_id, SUM(value_out) as sum')
                    ->with('materials', 'materials.materialUnit')
                    ->groupBy('material')
                    ->get();
            } else {
                $stockOut = StockOutModel::whereYear('date_out', '=', $year)
                    ->selectRaw('stock_out.material_id, SUM(value_out) as sum')
                    ->with('materials', 'materials.materialUnit')
                    ->groupBy('material_id')
                    ->orderBy('sum', $orderBy)
                    ->get();
            }

            return response()->json(['status' => 200, 'data' => $stockOut]);
        }
    }

    public function reportByWarehouse(Request $request)
    {
        if ($request->ajax()) {
            $warehouse = $request->get('warehouse');
            $year = $request->get('year');

            try {
                $stockIn = StockInModel::where('ware_house', $warehouse)
                    ->whereYear('date_in', '=', $year)
                    ->with('balances.materials.materialUnit')
                    ->selectRaw('stock_in.balance_id, SUM(value_in) as sum')
                    ->groupBy('balance_id')
                    ->get();

                $stockOut = StockOutModel::whereYear('date_out', '=', $year)
                    ->leftjoin('balance', 'balance.id', '=', 'stock_out.balance_id')
                    ->leftjoin('material', 'material.m_code', '=', 'balance.material_id')
                    ->leftjoin('unit', 'unit.id_unit', '=', 'material.m_unit')
                    ->selectRaw('stock_out.balance_id, SUM(value_out) as sum, material.m_code, material.m_name, unit.unit_name')
                    ->groupBy('balance_id')
                    ->get();

                return response()->json(['status' => 200, 'data' => ['stockIn' => $stockIn, 'stockOut' => $stockOut]]);
            } catch (\Throwable $th) {
                // throw $th;
                error_log($th->getMessage());
            }
        }
    }

    public function reportByType(Request $request)
    {
        if ($request->ajax()) {
            $msg = [
                'type.required' => 'กรุณาเลือกประเภทวัสดุ',
                'year.required' => 'กรุณาเลือกปี',
            ];

            $valid = Validator::make($request->all(), [
                'type' => 'required',
                'year' => 'required',
            ], $msg);

            if ($valid->fails()) {
                return response()->json(['status' => 400, 'errors' => $valid->errors()], 400);
            }

            $type = $request->get('type');
            $year = $request->get('year');

            $stockIn = StockInModel::whereYear('date_in', '=', $year)
                ->selectRaw('stock_in.material_id, SUM(value_in) as sum')
                ->with('materials.materialUnit')
                ->whereHas('materials', function ($query) use ($type) {
                    return $query->where('m_type', $type);
                })
                ->groupBy('material_id')
                ->get();

            $stockOut = StockOutModel::whereYear('date_out', '=', $year)
                ->selectRaw('stock_out.material_id, SUM(value_out) as sum')
                ->with('materials.materialUnit', 'materials.materialType')
                ->whereHas('materials', function ($query) use ($type) {
                    return $query->where('m_type', $type);
                })
                ->groupBy('material_id')
                ->get();

            return response()->json(['status' => 200, 'data' => ['stockIn' => $stockIn, 'stockOut' => $stockOut]]);
        }
    }

    public function reportByPrice(Request $request)
    {
        if ($request->ajax()) {
            $year = $request->get('year');
            $totalIn = 0;
            $stockIn = StockInModel::whereYear('date_in', $year)
                ->selectRaw('SUM(total_price_in) as sum')
                ->groupBy('material_id')
                ->get();
            foreach ($stockIn as $key => $value) {
                $totalIn += $value->sum;
            }

            $totalOut = 0;
            $stockOut = StockOutModel::whereYear('date_out', $year)
                ->selectRaw('SUM(total_price_out) as sum')
                ->groupBy('material_id')
                ->get();
            foreach ($stockOut as $key => $value) {
                $totalOut += $value->sum;
            }
            return response()->json(['status' => 200, 'data' => ['stockIn' => $totalIn, 'stockOut' => $totalOut]]);
        }
    }

    public function reportByDate(Request $request)
    {
        if ($request->ajax()) {
            $msg = [
                'from.required' => 'กรุณาเลือกวันเริ่มต้น',
                'from.date_format' => 'รูปแบบวันเริ่มต้น : dd-mm-yyyy',
                'to.required' => 'กรุณาเลือกวันสิ้นสุด',
                'to.date_format' => 'รูปแบบวันสิ้นสุด : dd-mm-yyyy',
            ];

            $valid = Validator::make($request->all(), [
                'from' => 'required|date_format:d-m-Y',
                'to' => 'required|date_format:d-m-Y',
            ], $msg);

            if ($valid->fails()) {
                return response()->json(['status' => 400, 'message' => 'Validator errors', 'errors' => $valid->errors()], 400);
            }

            try {
                $from = date('y-m-d', strtotime($request->get('from')));
                $to = date('y-m-d', strtotime($request->get('to')));
                $stockIn = StockInModel::whereBetween('date_in', array($from, $to))
                    ->selectRaw('stock_in.balance_id, SUM(value_in) as sum')
                    ->with('balances.materials.materialUnit')
                    ->groupBy('balance_id')
                    ->get();
                $stockOut = StockOutModel::whereBetween('date_out', array($from, $to))
                    ->selectRaw('stock_out.balance_id, SUM(value_out) as sum')
                    ->with('balances.materials.materialUnit')
                    ->groupBy('balance_id')
                    ->get();
                return response()->json(['status' => 200, 'data' => ['stock_in' => $stockIn, 'stock_out' => $stockOut]]);
            } catch (\Throwable $th) {
                //throw $th;
                return response()->json(['status' => 500, 'message' => 'Errors', 'errors' => $th->getMessage()]);
            }

        }
    }

    private function convertDateToThai($date)
    {
        $month = substr($date, 5, 2);
        switch ($month) {
            case '01':
                return 'ม.ค.';
            case '02':
                return 'ก.พ.';
            case '03':
                return 'มี.ค.';
            case '04':
                return 'เม.ย.';
            case '05':
                return 'พ.ค.';
            case '06':
                return 'มิ.ย.';
            case '07':
                return 'ก.ค.';
            case '08':
                return 'ส.ค.';
            case '09':
                return 'ก.ย.';
            case '10':
                return 'ต.ค.';
            case '11':
                return 'พ.ย.';
            case '12':
                return 'ธ.ค.';
            default:
                break;
        }
    }

}
