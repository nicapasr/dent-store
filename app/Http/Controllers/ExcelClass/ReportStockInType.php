<?php

namespace App\Http\Controllers\ExcelClass;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Util\ConvertDateThai;
use App\StockInModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Client\Request;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportStockInType implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $type;
    protected $year;

    public function __construct($type, $year)
    {
        $this->type = $type;
        $this->year = $year;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]],
        ];
    }

    public function headings(): array
    {
        return [
            'วันที่นำเข้า',
            'วันที่หมดอายุ',
            'รหัส',
            'ประเภทวัสดุ',
            'ชื่อวัสดุ',
            'นำเข้าจาก',
            'จำนวน',
            'หน่วย',
            'รวมราคา(บาท)',
        ];
    }

    public function collection()
    {
        try {
            $stockIn = DB::table('stock_in')
                ->join('material', 'stock_in.material_id', '=', 'material.id')
                ->join('warehouse', 'stock_in.ware_house', '=', 'warehouse.id_warehouse')
                ->join('material_type', 'material.m_type', '=', 'id_type')
                ->join('unit', 'unit.id_unit', '=', 'material.m_unit')
                ->select('stock_in.date_in', 'material.m_exp_date', 'material.m_code', 'material_type.type_name', 'material.m_name', 'warehouse.warehouse_name', 'stock_in.value_in', 'unit.unit_name', 'stock_in.total_price_in')
                ->where('material.m_type', '=', $this->type)
                ->whereYear('stock_in.date_in', $this->year)
                ->get();

            error_log($stockIn);
            return new Collection($stockIn);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
