<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            [
                'created_at' => '2023-07-08 05:42:25',
                'email' => 'it@ptmkm.co.id',
                'email_verified_at' => null,
                'is_active' => 1,
                'last_login' => '2023-08-15 11:38:49',
                'login_counter' => 1,
                'name' => 'IT',
                'password' => bcrypt('password'),
                'remember_token' => '123',
                'role' => 'IT',
                'updated_at' => '2023-08-15 11:38:49'
            ],
            [
                'created_at' => '2023-07-08 05:42:25',
                'email' => 'admin@ptmkm.co.id',
                'email_verified_at' => null,
                'is_active' => 1,
                'last_login' => '2023-08-15 11:38:49',
                'login_counter' => 1,
                'name' => 'Admin',
                'password' => bcrypt('password'),
                'remember_token' => '123',
                'role' => 'Super Admin',
                'updated_at' => '2023-08-15 11:38:49'
            ],
            [
                'created_at' => '2023-07-08 05:42:25',
                'email' => 'user@ptmkm.co.id',
                'email_verified_at' => null,
                'is_active' => 1,
                'last_login' => '2023-08-15 11:38:49',
                'login_counter' => 1,
                'name' => 'User',
                'password' => bcrypt('password'),
                'remember_token' =>'123',
                'role' => 'User',
                'updated_at' => '2023-08-15 11:38:49'
            ]
        ]);
      
    }
}
