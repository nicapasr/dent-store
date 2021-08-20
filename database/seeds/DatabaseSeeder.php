<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UnitSeeder::class,
            // MaterialTypeSeeder::class,
            WareHouseSeeder::class,
            MaterialSeeder::class,
            // ImportantSeeder::class,
            PermissionSeeder::class,
            // DepartmentSeeder::class,
            UsersSeeder::class,
            MembersSeeder::class
        ]);
    }
}
