<?php

namespace Database\Seeders;

use App\Domain\RecruitmentStage\Models\RecruitmentStage;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class RecruitmentStageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RecruitmentStage::create([
            'name' => 'CV Screening',
            'order' => '1',
            'is_active' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        RecruitmentStage::create([
            'name' => 'Psychological test',
            'order' => '2',
            'is_active' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        RecruitmentStage::create([
            'name' => 'HR Interview',
            'order' => '3',
            'is_active' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        RecruitmentStage::create([
            'name' => 'User Interview',
            'order' => '4',
            'is_active' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        RecruitmentStage::create([
            'name' => 'Offering Letter',
            'order' => '5',
            'is_active' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
