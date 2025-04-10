<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DepartmentHeadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get IT department ID
        $itDepartmentId = DB::table('departments')
            ->where('name', 'IT')
            ->value('id');

        if (!$itDepartmentId) {
            $this->command->error('IT Department not found. Please run PositionSeeder first.');
            return;
        }

        // Get department head role ID
        $departmentHeadRoleId = DB::table('roles')
            ->where('slug', 'department-head')
            ->value('id');

        if (!$departmentHeadRoleId) {
            // Create the role if it doesn't exist
            $departmentHeadRoleId = DB::table('roles')->insertGetId([
                'label' => 'Department Head',
                'slug' => 'department-head',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $this->command->info('Created department-head role with ID: ' . $departmentHeadRoleId);
        }

        // Check if we already have an IT department head
        $itHeadExists = DB::table('users')
            ->join('role_user', 'users.id', '=', 'role_user.user_id')
            ->where('role_user.role_id', $departmentHeadRoleId)
            ->where('users.department_id', $itDepartmentId)
            ->exists();

        if (!$itHeadExists) {
            // Create IT department head
            $userId = DB::table('users')->insertGetId([
                'name' => 'IT Department Head',
                'email' => 'it.head@example.com',
                'password' => Hash::make('Password123!'),
                'department_id' => $itDepartmentId,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Assign department head role
            DB::table('role_user')->insert([
                'user_id' => $userId,
                'role_id' => $departmentHeadRoleId,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $this->command->info('Created IT Department Head user with ID: ' . $userId);
        }
    }
}
