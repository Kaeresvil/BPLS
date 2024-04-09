<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->delete();
        DB::table('roles')->insert([
            'name' => 'Admin',
            'description' => 'Administrator have an access all features.',

        ]);
        DB::table('roles')->insert([
            'name' => 'Staff',
            'description' => 'Staff have an access to approve and return application',

        ]);
        DB::table('roles')->insert([
            'name' => 'Applicant',
            'description' => 'Applicant have an access only to application and appointment features.',

        ]);
    }
}