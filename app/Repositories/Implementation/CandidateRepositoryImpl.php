<?php

namespace App\Repositories\Implementation;

use App\Models\Candidate;
use App\Repositories\CandidateRepository;

class CandidateRepositoryImpl implements CandidateRepository
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
