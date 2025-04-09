<?php

namespace App\Domain\Candidate\Interfaces;

use App\Domain\Candidate\Models\Candidate;
use Illuminate\Database\Eloquent\Collection;

interface CandidateRepositoryInterface
{
    public function create(Candidate $candidate): void;
    public function candidateExistsByID(int $id): bool;
    public function findCandidateById(int $candidateId): Candidate;
    public function findCandidateWithProgressAndBatch(int $candidateId): Candidate;
}
