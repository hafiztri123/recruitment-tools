<?php

namespace App\Domain\Candidate\Interfaces;

use App\Domain\Candidate\Models\Candidate;

interface CandidateRepositoryInterface
{
    public function create(Candidate $candidate): void;
    public function candidateExistsByID(int $id): bool;
}
