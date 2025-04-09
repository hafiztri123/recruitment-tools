<?php

namespace App\Domain\RecruitmentBatch\Interfaces;

use App\Domain\RecruitmentBatch\Models\RecruitmentBatch;

interface RecruitmentBatchRepositoryInterface
{
    public function create(RecruitmentBatch $recruitmentBatch): int;
    public function recruitmentBatchExistsByID(int $id): bool;
    public function findRecruitmentBatchByID (int $id): RecruitmentBatch;
    public function findRecruitmentBatchWithPosition(int $batchID): RecruitmentBatch;
}
