<?php
namespace App\Http\Controllers\ExcelClass;

use App\Http\Controllers\Util\ConvertDateThai;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportPriceData implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{

    protected $year;

    public function __construct($year)
    {
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
            'วันที่รายงาน',
            'ราคานำเข้าวัสดุ',
            'ราคาเบิกวัสดุ',
        ];
    }

    public function collection()
    {
        try {
            $stockIn = DB::table('stock_in')
                ->whereYear('date_in', $this->year)
                ->sum('total_price_in');

            $stockOut = DB::table('stock_out')
                ->whereYear('date_out', $this->year)
                ->sum('total_price_out');

            $date = ConvertDateThai::toDateThai(date('Y-m-d'));
            return new Collection([['date' => $date, 'stockIn' => $stockIn, 'stockOut' => $stockOut]]);
        } catch (\Throwable $th) {
            //throw $th;
            // return response()->json(['status' => 500, 'message' => $th->getMessage()], 500);
        }
    }

}
