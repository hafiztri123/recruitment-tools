<?php


namespace App\Repositories;

use App\Models\RecruitmentStage;

interface RecruitmentStageRepository
{
    public function findByOrder(int $order): RecruitmentStage;
    public function create(RecruitmentStage $recruitmentStage): void;
    public function existsByOrderAndActive(int $order): bool;
}
