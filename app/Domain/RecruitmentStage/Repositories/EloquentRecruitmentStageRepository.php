<?php

namespace App\Domain\RecruitmentStage\Repositories;

use App\Domain\RecruitmentStage\Interfaces\RecruitmentStageRepositoryInterface;
use App\Domain\RecruitmentStage\Models\RecruitmentStage;

class EloquentRecruitmentStageRepository implements RecruitmentStageRepositoryInterface
{
    public function findByOrder(int $order): RecruitmentStage
    {
        return RecruitmentStage::where('order', $order)->firstOrFail();

    }

    public function create(RecruitmentStage $recruitmentStage): void
    {
        $recruitmentStage->saveOrFail();
    }

    public function existsByOrderAndActive(int $order): bool
    {
        return RecruitmentStage::where('order', $order)->exists();
    }

    public function getTotalStages(): int
    {
        return RecruitmentStage::where('is_active', true)->count();
    }


}
