<?php

namespace App\Repositories;

use App\Models\RecruitmentBatch;

interface RecruitmentBatchRepository
{
    public function create(RecruitmentBatch $recruitmentBatch): int;
    public function recruitmentBatchExistsByID(int $id): bool;
}
