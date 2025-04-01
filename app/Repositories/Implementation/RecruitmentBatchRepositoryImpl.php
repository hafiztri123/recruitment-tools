<?php

namespace App\Repositories\Implementation;

use App\Models\RecruitmentBatch;
use App\Repositories\RecruitmentBatchRepository;

class RecruitmentBatchRepositoryImpl implements RecruitmentBatchRepository
{
    public function create(RecruitmentBatch $recruitmentBatch): int
    {
        $recruitmentBatch->saveOrFail();
        return $recruitmentBatch->id;
    }

    public function recruitmentBatchExistsByID(int $id): bool
    {
        return RecruitmentBatch::where('id', $id)->exists();

    }

    public function findRecruitmentBatchByID(int $id): RecruitmentBatch
    {
        return RecruitmentBatch::where('id', $id)->firstOrFail();
    }
}
