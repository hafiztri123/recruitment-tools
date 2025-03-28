<?php

namespace App\Repositories\Implementation;

use App\Models\RecruitmentStage;
use App\Repositories\RecruitmentStageRepository;

class RecruitmentStageRepositoryImpl implements RecruitmentStageRepository
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
}
