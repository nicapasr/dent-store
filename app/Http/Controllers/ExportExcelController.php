<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportExcelController extends Controller
{
    function ExportDataStockIn(Request $request){
        $month = $request->month;
        $year = $request->year;
        return Excel::download(new DataExportStockIn($month, $year), 'Stock_in-'.$month.'-'.$year.'.xlsx');
    }
}


class DataExportStockIn implements FromCollection,WithHeadings{

    protected $id, $year;

    function __construct($id, $year) {
        $this->id = $id;
        $this->year = $year;
    }

    function headings():array{
        return [
            'วันที่',
            'เลขที่',
            'รหัสวัสดุ',
            'ชื่อวัสดุ',
            'จำนวน',
            'หน่วยนับ',
            'ราคา/หน่วย',
            'มูลค่า',
            'การนำเข้า'
        ];
    }

    function collection(){
        $stockIn = DB::table('stock_in')->select('stock_in.date_in', 'stock_in.id_stock_in', 'material.m_code', 'material.m_name', 'stock_in.value_in', 'unit.unit_name','material.m_price_unit',  'stock_in.total_price_in', 'warehouse.warehouse_name')
                       ->join('material', 'material.m_code', '=', 'stock_in.material')
                       ->join('unit', 'unit.id_unit', '=', 'material.m_unit')
                       ->join('warehouse', 'warehouse.id_warehouse' , '=', 'stock_in.ware_house')
                       ->whereYear('stock_in.date_in', '=', $this->year)
                       ->whereMonth('stock_in.date_in', '=', $this->id)
                       ->get();
        return $stockIn;
    }
}
