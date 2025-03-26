<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hrExists = DB::table('roles')->where('slug', '=', 'hr')->exists();
        $userExists = DB::table('roles')->where('slug', '=', 'user')->exists();
        $headOfHrExists = DB::table('roles')->where('slug', '=', 'head-of-hr')->exists();

        if(!$hrExists){
            DB::table('roles')
                ->insert([
                    'label' => 'Human Resource',
                    'slug' => 'hr',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
        }


        if(!$userExists){
            DB::table('roles')
                ->insert([
                    'label' => "User",
                    'slug' => 'user',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()

                ]);
        }

        $headOfHRID = DB::table('roles')
            ->where('slug', 'head-of-hr')
            ->value('id');

        if(!$headOfHrExists){
            $userID = DB::table('roles')
                ->insertGetId([
                    'label' => 'Head of Human Resource',
                    'slug' => 'head-of-hr',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
            ]);
        }
    }
}
