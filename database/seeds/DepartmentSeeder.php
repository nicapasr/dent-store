<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $departments = array(
            'คลินิคพิเศษ',
            'ทันตศัลยกรรม',
        );

        foreach ($departments as $department) {
            DB::table('department')->insert(['dep_name' => $department]);
        }
    }
}
