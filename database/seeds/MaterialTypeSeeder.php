<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class MaterialTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = array(
            'วัสดุสิ้นเปลือง',
            // 'วัสดุมีวันหมดอายุ',
            'เครื่องมือ',
        );

        foreach ($types as $type) {
            DB::table('material_type')->insert(['type_name' => $type]);
        }
    }
}
