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

class ReportStockOutType implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
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
            'วันที่ทำการเบิก',
            'ชื่อ',
            'นามสกุล',
            'รหัส',
            'ชื่อวัสดุ',
            'ประเภทวัสดุ',
            'จำนวน',
            'หน่วย',
            'รวมราคา(บาท)',
        ];
    }

    public function collection()
    {
        try {
            $stockOut = DB::table('stock_out')
                ->join('material', 'stock_out.material_id', '=', 'material.id')
                ->join('material_type', 'material.m_type', '=', 'id_type')
                ->join('unit', 'unit.id_unit', '=', 'material.m_unit')
                ->join('members', 'stock_out.member_id', '=', 'members.id')
                ->select('stock_out.date_out', 'members.fname', 'members.lname', 'material.m_code', 'material.m_name', 'material_type.type_name', 'stock_out.value_out', 'unit.unit_name', 'stock_out.total_price_out')
                ->where('material.m_type', '=', $this->type)
                ->whereYear('stock_out.date_out', $this->year)
                ->get();

            error_log($stockOut);
            return new Collection($stockOut);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
