<?php

namespace App\Repositories;

use App\Models\CandidateStage;

interface CandidateStageRepository
{
    public function candidateStageExistsByID(int $id): bool;
    public function create(CandidateStage $candidateStage): int;
}
