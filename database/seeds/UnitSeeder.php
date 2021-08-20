<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $units = array(
            'ชิ้น',
            'กล่อง',
            'ขวด',
            'หลอด',
            'ชุด',
            'ซอง',
            'ม้วน',
            'ถุง',
            'กระป๋อง',
            'ตัว',
            'แกลลอน',
            'อัน',
            'ใบ',
            'กระสอบ',
            'โหล',
            'ปี๊บ',
            'เส้น/สาย',
            'เส้น',
            'แพค/ห่อ',
            'แพค',
            'ห่อ',
            'ไวแอล',
            'แอมพลู',
            'แผ่น',
            'ลัง',
            'ท่อน',
            'ดอก',
            'ซี่',
            'ถัง',
            'ผืน',
            'unit',
            'เครื่อง',
            'ถ้วย',
            'กิโลกรัม',
            'รถ/คัน',
            'คัน',
            'เมกะโวลต์แอมแปร์',
            'ตู้',
            'vials',
            'ด้าม',
            'คู่',
            'ลิตร',
            'ท่อ',
            'เม็ด',
            'แผง',
            'ตลับ',
            'หน่วย',
            'ลูก',
            'กระปุก',
            'หัว',
            'แท่ง',
            'ก้อน',
            'ไวแอม',
            'ปอนด์'
        );

        foreach ($units as $unit) {
            DB::table('unit')->insert(['unit_name' => $unit]);
        }
    }
}
