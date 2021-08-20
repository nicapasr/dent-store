<?php

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'username' => 'admin',
            'password' => Hash::make('1234567890'),
            'permission' => '1',
            // 'department' => '1',
            'phone' => '1234567890',
            'first_name' => 'admin',
            'last_name' => 'admin'
        ]);

        User::create([
            'username' => 'board',
            'password' => Hash::make('1234567890'),
            'permission' => '2',
            // 'department' => '2',
            'phone' => '1234567890',
            'first_name' => 'board',
            'last_name' => 'board'
        ]);
    }
}
