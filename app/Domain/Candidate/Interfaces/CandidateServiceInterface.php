<?php

namespace App\Domain\Candidate\Interfaces;

use App\Domain\Candidate\Requests\CreateMultipleCandidatesRequest;

interface CandidateServiceInterface
{
    public function createCandidate(array $candidateData, int $batchID): void;
    public function createCandidates(CreateMultipleCandidatesRequest $request, int $batchID): string;
}
