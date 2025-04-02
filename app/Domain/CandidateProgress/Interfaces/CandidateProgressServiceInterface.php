<?php

namespace App\Domain\CandidateProgress\Interfaces;


interface CandidateProgressServiceInterface
{
    public function createCandidateProgress(int $candidateID, int $recruitmentBatchID, int $candidateStageID): void;
}
