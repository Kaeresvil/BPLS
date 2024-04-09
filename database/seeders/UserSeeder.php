<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();
        DB::table('users')->insert([
            'first_name' => 'Admin',
            'middle_name' => null,
            'last_name' => 'Admin',
            'extension_name' => null,
            
            'role_id' => '1', /// Admin
            'email' => 'admin@bpls.com',
            'password' => bcrypt('password.123'),
            'is_verified' => 1
        ]);
        DB::table('users')->insert([
            'first_name' => 'Staff',
            'middle_name' => null,
            'last_name' => 'Staff',
            'extension_name' => null,
            
            'role_id' => '2', /// Staff
            'email' => 'staff@bpls.com',
            'password' => bcrypt('password.123'),
            'is_verified' => 1
        ]);
        DB::table('users')->insert([
            'first_name' => 'Applicant',
            'middle_name' => null,
            'last_name' => 'Applicant',
            'extension_name' => null,
            'role_id' => '3', /// Applicant
            'email' => 'applicant@bpls.com',
            'password' => bcrypt('password.123'),
            'is_verified' => 1
        ]);
    }
}