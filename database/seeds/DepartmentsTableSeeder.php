<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('departments')->insert([
            'name' => 'Dev',
            'director_user_id' => 1,
        ]);

        DB::table('departments')->insert([
            'name' => 'Test',
            'director_user_id' => 1,
        ]);

        DB::table('departments')->insert([
            'name' => 'Social',
            'director_user_id' => 1,
        ]);

        DB::table('departments')->insert([
            'name' => 'Assistance',
            'director_user_id' => 1,
        ]);
    }
}
