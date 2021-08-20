<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImportantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $importants = array(
            'ปกติ',
            'ด่วน',
            'ด่วนมาก',
            'ด่วนที่สุด',
        );

        foreach ($importants as $important) {
            DB::table('important')->insert(['imp_name' => $important]);
        }
    }
}
