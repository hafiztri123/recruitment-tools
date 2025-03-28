<?php


namespace App\Repositories\Implementation;

use App\Models\CandidateProgress;
use App\Repositories\CandidateProgressRepository;

class CandidateProgressRepositoryImpl implements CandidateProgressRepository
{
    public function create(CandidateProgress $candidateProgress): void
    {
        $candidateProgress->saveOrFail();
    }
}
