<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $permissions = array(
            'admin',
            'board',
        );

        foreach ($permissions as $permission) {
            DB::table('user_permission')->insert(['permission_name' => $permission]);
        }
    }
}
