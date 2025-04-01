<?php

namespace App\Services;

use App\Http\Requests\CandidatesStageUpdateStatusRequest;

interface CandidateStageService
{
    public function createCandidateStage(int $order): int;
    public function moveCandidateToNextStage(
        int $candidateID,
        int $recruitmentBatchID,
    ):void;
    public function moveCandidatesToNextStage(CandidatesStageUpdateStatusRequest $request, int $batchID): string;

}
