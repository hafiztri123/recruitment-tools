<?php

namespace App\Services;

use App\Http\Requests\CreateCandidateRequest;
use App\Http\Requests\CreateMultipleCandidatesRequest;
use App\Http\Requests\CreateMultipleCandidatesRequests;
use App\Models\Candidate;

interface CandidateService
{
    public function createCandidate(array $candidateData, int $batchID): void;
    public function createCandidates(CreateMultipleCandidatesRequest $request, int $batchID): string;
}
