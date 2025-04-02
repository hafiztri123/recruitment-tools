<?php

namespace App\Domain\RecruitmentBatch\Interfaces;

use App\Domain\RecruitmentBatch\Requests\CreateRecruitmentBatchRequest;

interface RecruitmentBatchServiceInterface
{
    public function createRecruitmentBatch(CreateRecruitmentBatchRequest $request, int $positionID): int;
}
