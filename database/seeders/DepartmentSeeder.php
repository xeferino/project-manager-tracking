<?php

namespace Database\Seeders;

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
        DB::table('departments')->insert([
            'name' => 'Logistica'
        ]);
        DB::table('departments')->insert([
            'name' => 'Financiera'
        ]);
        DB::table('departments')->insert([
            'name' => 'Administracion'
        ]);
    }
}
