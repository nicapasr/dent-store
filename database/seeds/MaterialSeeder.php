<?php

use App\Http\Controllers\Util\FileProvider;
use App\MaterialModel;
use App\UnitModel;
use Illuminate\Database\Seeder;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $datas = array(
        //     //รหัส
        //     'code' =>
        //     [
        //         "090501003",
        //         "090501005",
        //         "090501020",
        //         "090501046",
        //         "090501047",
        //         "090501048",
        //         "090501049",
        //         "090501050",
        //         "090501051",
        //         "090501052",
        //         "090501053",
        //         "090501054",
        //         "090501055"
        //     ],
        //     //ชื่อ
        //     'name' =>
        //     [
        //         "Bonding (refill)",
        //         "Calcium hydroxide paste ยี่ห้อ Dycal",
        //         "Glass Ionomer LC for filling- ทั้งชุด ยี่ห้อ Fuji VII",
        //         "Matrix holder : Tofflemire",
        //         "Matrix holder : Tofflemire",
        //         "Matrix holder : Tofflemire",
        //         "Matrix holder : Tofflemire",
        //         "Matrix holder : Tofflemire",
        //         "Matrix holder : Tofflemire",
        //         "Matrix holder : Tofflemire",
        //         "Matrix holder : Tofflemire",
        //         "Matrix holder : Tofflemire",
        //         "Matrix holder : Tofflemire"
        //     ],
        //     //ราคา/หน่วย
        //     'price_unit' =>
        //     [
        //         "100",
        //         "300",
        //         "500",
        //         "1000",
        //         "1000",
        //         "1000",
        //         "1000",
        //         "1000",
        //         "1000",
        //         "1000",
        //         "1000",
        //         "1000",
        //         "1000"
        //     ],
        //     //ประเภท
        //     'type' =>
        //     [
        //         "1",
        //         "1",
        //         "1",
        //         "1",
        //         "1",
        //         "1",
        //         "1",
        //         "1",
        //         "1",
        //         "1",
        //         "1",
        //         "1",
        //         "1"
        //     ],
        //     //ทั้งหมด
        //     'total' =>
        //     [
        //         "100",
        //         "50",
        //         "40",
        //         "30",
        //         "30",
        //         "30",
        //         "30",
        //         "30",
        //         "30",
        //         "30",
        //         "30",
        //         "30",
        //         "30"
        //     ],
        //     //คงเหลือ
        //     'balance' =>
        //     [
        //         "80",
        //         "30",
        //         "20",
        //         "15",
        //         "15",
        //         "15",
        //         "15",
        //         "15",
        //         "15",
        //         "15",
        //         "15",
        //         "15",
        //         "15"
        //     ],
        //     //หน่วยนับ
        //     'unit' =>
        //     [
        //         "3",
        //         "5",
        //         "5",
        //         "12",
        //         "12",
        //         "12",
        //         "12",
        //         "12",
        //         "12",
        //         "12",
        //         "12",
        //         "12",
        //         "12"
        //     ]
        // );

        // for ($i=0; $i < 13; $i++) {
        //     DB::table('material')->insert(['m_code' => $datas['code'][$i], 'm_name' => $datas['name'][$i], 'm_type' => $datas['type'][$i], 'm_unit' => $datas['unit'][$i], 'm_balance' => $datas['balance'][$i], 'm_price_unit' => $datas['price_unit'][$i], 'm_total' => $datas['total'][$i]]);
        // }

        $csvFile = public_path('file/' . 'materialList.csv');
        $fileProvider = new FileProvider;
        $lists = $fileProvider->csvToArray($csvFile, ',');

        foreach ($lists as $item) {
            $code = str_replace(' ', '', $item['m_code']);
            $unitName = str_replace(' ', '', $item['m_unit']);
            $units = UnitModel::where('unit_name', $unitName)->get('id_unit')->first();
            $item['m_unit'] = $units->id_unit;

            MaterialModel::create([
                'm_code' => $code,
                'm_name' => $item['m_name'],
                // 'm_type' => 1,
                'm_unit' => $item['m_unit'],
                // 'm_balance' => null,
                // 'm_price_unit' => null,
                // 'm_total' => null,
            ]);
        }
    }
}
