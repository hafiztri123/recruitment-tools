<?php

namespace App\Domain\RecruitmentStage\Interfaces;

use App\Domain\RecruitmentStage\Requests\CreateRecruitmentStageRequest;

interface RecruitmentStageServiceInterface
{
    public function createRecruitmentStage(CreateRecruitmentStageRequest $request): void;
}
