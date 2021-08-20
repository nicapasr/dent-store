<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class WareHouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $wareHouses = array(
            'ส่วนกลาง',
            'จัดซื้อ',
            'คลัง'
        );

        foreach ($wareHouses as $wareHouse) {
            DB::table('warehouse')->insert(['warehouse_name' => $wareHouse]);
        }
    }
}
