<?php

namespace App\Http\Controllers\ExcelClass;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportStockInData implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $from;
    protected $to;

    public function __construct($from, $to)
    {
        $fromDate = date('Y-m-d', strtotime($from));
        $toDate = date('Y-m-d', strtotime($to));
        $this->from_date = $fromDate;
        $this->to_date = $toDate;
        error_log($this->from_date);
        error_log($this->to_date);
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
            'หน่วย',
            // 'รวมราคา(บาท)',
        ];
    }

    public function collection()
    {
        try {
            $stockIn = DB::table('stock_in')
                ->join('balance', 'stock_in.balance_id', '=', 'balance.id')
                ->join('material', 'balance.material_id', '=', 'material.m_code')
                ->join('warehouse', 'stock_in.ware_house', '=', 'warehouse.id_warehouse')
                ->join('unit', 'unit.id_unit', '=', 'material.m_unit')
                ->selectRaw('stock_in.date_in, balance.b_exp_date, material.m_code, material.m_name, warehouse.warehouse_name, SUM(stock_in.value_in) as sum, unit.unit_name')
                ->whereDate('stock_in.date_in', '>=', $this->from_date)
                ->whereDate('stock_in.date_in', '<=', $this->to_date)
                ->groupBy('stock_in.date_in', 'balance.id')
                ->get();
            return new Collection($stockIn);
        } catch (\Throwable $th) {
            throw $th;
            // return response()->json(['status' => 500, 'message' => $th->getMessage()], 500);
        }
    }
}
