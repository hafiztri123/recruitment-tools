<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class HeadOfHRSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $HeadOfHRExists = DB::table('role_user')
            ->where('role_id', function ($query){
                $query->select('id')
                    ->from('roles')
                    ->where('slug', 'head-of-hr');
            })
            ->exists();

        $HRDepartmentExists = DB::table('departments')
            ->where('name', 'Human Resource')
            ->exists();

        if (!$HRDepartmentExists){
            DB::table('departments')
                ->insert([
                    'name' => 'Human Resource',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
        }

        if (!$HeadOfHRExists) {
            $departmentId = DB::table('departments')
                ->where('name', 'Human Resource')
                ->value('id');

            $userID = DB::table('users')->insertGetId([
                'name' => 'Head of HR 1',
                'email' => 'hafiz.triwahyu@gmail.com',
                'password' => Hash::make('Sudarmi12'),
                'department_id' => $departmentId,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $roleID = DB::table('roles')
                ->where('slug', 'head-of-hr')
                ->value('id');

            DB::table('role_user')->insert([
                'user_id' => $userID,
                'role_id' => $roleID,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
