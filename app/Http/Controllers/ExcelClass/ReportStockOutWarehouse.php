<?php

namespace App\Http\Controllers\ExcelClass;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportStockOutWarehouse implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
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
            'วันที่ทำการเบิก',
            'วันที่หมดอายุ',
            'ชื่อ',
            'นามสกุล',
            'รหัส',
            'ชื่อวัสดุ',
            'จำนวน',
            'หน่วย'
        ];
    }

    public function collection()
    {
        try {
            $stockIn = DB::table('stock_out')
                ->join('balance', 'balance.id', '=', 'stock_out.balance_id')
                ->join('material', 'material.m_code', '=', 'balance.material_id')
                ->join('members', 'stock_out.member_id', '=', 'members.id')
                ->join('unit', 'unit.id_unit', '=', 'material.m_unit')
                ->select('stock_out.date_out', 'balance.b_exp_date','members.fname', 'members.lname', 'material.m_code', 'material.m_name', 'stock_out.value_out', 'unit.unit_name')
                ->whereYear('stock_out.date_out', $this->year)
                ->get();

            return new Collection($stockIn);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
