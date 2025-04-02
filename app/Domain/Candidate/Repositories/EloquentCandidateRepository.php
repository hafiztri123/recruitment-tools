<?php

namespace App\Domain\Candidate\Repositories;

use App\Domain\Candidate\Interfaces\CandidateRepositoryInterface;
use App\Domain\Candidate\Models\Candidate;

class EloquentCandidateRepository implements CandidateRepositoryInterface
{
    public function create(Candidate $candidate): void
    {
        $candidate->saveOrFail();
    }

    public function candidateExistsByID(int $id): bool
    {
        return Candidate::where('id', $id)->exists();
    }
}
