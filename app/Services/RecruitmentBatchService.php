<?php

namespace App\Services;

use App\Http\Requests\CreateRecruitmentBatchRequest;

interface RecruitmentBatchService
{
    public function createRecruitmentBatch(CreateRecruitmentBatchRequest $request, int $positionID): int;
}
