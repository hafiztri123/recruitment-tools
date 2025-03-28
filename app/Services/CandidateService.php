<?php

namespace App\Services;

use App\Http\Requests\CreateCandidateRequest;

interface CandidateService
{
    public function createCandidate(CreateCandidateRequest $request, int $batchID): void;
}
