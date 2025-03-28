<?php

namespace App\Repositories;

use App\Models\CandidateProgress;

interface CandidateProgressRepository
{
    public function create(CandidateProgress $candidateProgress): void;
}
