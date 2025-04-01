<?php

namespace App\Repositories;

use App\Models\CandidateProgress;
use Illuminate\Database\Eloquent\Collection;

interface CandidateProgressRepository
{
    public function create(CandidateProgress $candidateProgress): void;
    public function findByCandidateIDAndRecruitmentBatchID(int $candidateID, int $recruitmentBatchID): Collection;
}
