<?php

namespace App\Repositories;

use App\Models\Candidate;

interface CandidateRepository
{
    public function create(Candidate $candidate): void;
    public function candidateExistsByID(int $id): bool;
}
