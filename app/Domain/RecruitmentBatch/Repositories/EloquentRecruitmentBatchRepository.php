<?php

namespace App\Domain\RecruitmentBatch\Repositories;

use App\Domain\RecruitmentBatch\Interfaces\RecruitmentBatchRepositoryInterface;
use App\Domain\RecruitmentBatch\Models\RecruitmentBatch;

class EloquentRecruitmentBatchRepository implements RecruitmentBatchRepositoryInterface
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
