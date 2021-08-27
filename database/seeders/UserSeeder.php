<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('departments')->insert([
            'name' => 'Logistica'
        ]);

        DB::table('users')->insert([
            'name' => 'Super',
            'surname' => 'Admin',
            'email' => 'root@test.com',
            'role' => 'root',
            'department_id' => 1,
            'password' => Hash::make('root'),
        ]);
    }
}
