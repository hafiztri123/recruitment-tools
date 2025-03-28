<?php

namespace App\Services;


interface CandidateProgressService
{
    public function createCandidateProgress(int $candidateID, int $recruitmentBatchID, int $candidateStageID): void;
}
