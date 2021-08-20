<?php

namespace App\Http\Controllers\ExcelClass;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportStockInWarehouse implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $warehouse;
    protected $year;

    public function __construct($warehouse, $year)
    {
        $this->warehouse = $warehouse;
        $this->year = $year;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function headings(): array
    {
        return [
            'วันที่นำเข้า',
            'วันที่หมดอายุ',
            'รหัส',
            'ชื่อวัสดุ',
            'นำเข้าจาก',
            'จำนวน',
            'หน่วย'
        ];
    }

    public function collection()
    {
        try {
            $stockIn = DB::table('stock_in')
                ->join('balance', 'balance.id', '=', 'stock_in.balance_id')
                ->join('material', 'material.m_code', '=', 'balance.material_id')
                ->join('warehouse', 'stock_in.ware_house', '=', 'warehouse.id_warehouse')
                ->join('unit', 'unit.id_unit', '=', 'material.m_unit')
                ->select('stock_in.date_in', 'balance.b_exp_date', 'material.m_code', 'material.m_name', 'warehouse.warehouse_name', 'stock_in.value_in', 'unit.unit_name')
                ->where('stock_in.ware_house', '=', $this->warehouse)
                ->whereYear('stock_in.date_in', $this->year)
                ->get();

            return new Collection($stockIn);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
