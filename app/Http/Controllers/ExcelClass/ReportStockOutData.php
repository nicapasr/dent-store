<?php

namespace App\Http\Controllers\ExcelClass;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportStockOutData implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
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
            'วันที่ทำการเบิก',
            'ชื่อ',
            'นามสกุล',
            'วันหมดอายุ',
            'รหัส',
            'ชื่อวัสดุ',
            'จำนวน',
            'หน่วย',
            // 'รวมราคา(บาท)',
        ];
    }

    public function collection()
    {
        try {
            $stockOut = DB::table('stock_out')
                ->join('balance', 'stock_out.balance_id', '=', 'balance.id')
                ->join('material', 'balance.material_id', '=', 'material.m_code')
                ->join('members', 'stock_out.member_id', '=', 'members.id')
                ->join('unit', 'unit.id_unit', '=', 'material.m_unit')
                ->selectRaw('stock_out.date_out, members.fname, members.lname, balance.b_exp_date, material.m_code, material.m_name, SUM(stock_out.value_out) as sum, unit.unit_name')
                ->whereDate('stock_out.date_out', '>=', $this->from_date)
                ->whereDate('stock_out.date_out', '<=', $this->to_date)
                ->groupBy('stock_out.date_out', 'members.id', 'material.m_code')
                ->get();
            return new Collection($stockOut);
        } catch (\Throwable $th) {
            throw $th;
            // return response()->json(['status' => 500, 'message' => $th->getMessage()], 500);
        }
    }
}
