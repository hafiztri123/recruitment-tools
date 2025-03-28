<?php

namespace App\Services;

use App\Http\Requests\CreateRecruitmentStageRequest;

interface RecruitmentStageService
{
    public function createRecruitmentStage(CreateRecruitmentStageRequest $request): void;
}
