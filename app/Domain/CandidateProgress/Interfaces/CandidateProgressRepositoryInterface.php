<?php

namespace App\Domain\CandidateProgress\Interfaces;

use App\Domain\CandidateProgress\Models\CandidateProgress;
use Illuminate\Database\Eloquent\Collection;

interface CandidateProgressRepositoryInterface
{
    public function create(CandidateProgress $candidateProgress): void;
    public function findByCandidateIDAndRecruitmentBatchID(int $candidateID, int $recruitmentBatchID): Collection;
    public function findByBatchIDAndExcludingByCandidateIds(int $batchID, array $candidateIDs);
}
