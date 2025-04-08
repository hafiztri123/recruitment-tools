<?php

namespace App\Domain\CandidateStage\Interfaces;

use App\Domain\CandidateStage\Models\CandidateStage;
use App\Domain\CandidateStage\Requests\CandidatesStageUpdateStatusRequest;

interface CandidateStageServiceInterface
{
    public function createCandidateStage(int $order): int;

    public function moveCandidateToNextStage(
        int $candidateID,
        int $recruitmentBatchID,
    ):void;

    public function moveCandidatesToNextStage(
        CandidatesStageUpdateStatusRequest $request,
        int $batchID
    ): void;

    public function rejectCandidates(
        int $candidateID,
        int $recruitmentBatchID
    ): void;

    public function getCurrentCandidateStage(
        int $candidateID,
        int $batchID
    ): CandidateStage;

}
