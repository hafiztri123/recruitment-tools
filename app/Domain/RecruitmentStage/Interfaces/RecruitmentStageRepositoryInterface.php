<?php

namespace App\Domain\RecruitmentStage\Interfaces;

use App\Domain\RecruitmentStage\Models\RecruitmentStage;

interface RecruitmentStageRepositoryInterface
{
    public function findByOrder(int $order): RecruitmentStage;
    public function create(RecruitmentStage $recruitmentStage): void;
    public function existsByOrderAndActive(int $order): bool;
}
