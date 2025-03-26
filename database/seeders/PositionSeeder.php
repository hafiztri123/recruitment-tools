<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ITDepartmentExists = DB::table('departments')
            ->where('name', 'IT')
            ->exists();

        if(!$ITDepartmentExists){
            DB::table('departments')
                ->insert([
                    'name' => 'IT',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
        }

        $ITDepartmentID = DB::table('departments')
            ->where('name', 'IT')
            ->value('id');

        if(!DB::table('positions')->exists()){
            DB::table('positions')
                ->insert([
                    'title' => 'Software Engineer',
                    'description' => 'Software engineer for project A',
                    'requirements' => '2 Years of experience',
                    'created_at' => now(),
                    'updated_at' => now(),
                    'department_id' => $ITDepartmentID
                ]);

        }

    }
}
