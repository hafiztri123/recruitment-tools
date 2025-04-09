<?php

namespace App\Domain\Candidate\Repositories;

use App\Domain\Candidate\Exceptions\CandidateNotFoundException;
use App\Domain\Candidate\Interfaces\CandidateRepositoryInterface;
use App\Domain\Candidate\Models\Candidate;
use Illuminate\Database\Eloquent\Collection;

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

    public function findCandidateById(int $candidateId): Candidate
    {
        $candidate = Candidate::find($candidateId);

        if (!$candidate) {
            throw new CandidateNotFoundException(candidateId: $candidateId);
        }

        return $candidate;
    }

    public function findCandidateWithProgressAndBatch(int $candidateId): Candidate
    {
        $candidate = Candidate::with([
            'candidateProgresses.recruitmentBatch.position.department',
            'candidateProgresses.candidateStage.recruitmentStage'
        ])
            ->where('id', $candidateId)
            ->first();

        if (!$candidate) {
            throw new CandidateNotFoundException(candidateId: $candidateId);
        }

        return $candidate;
    }








}
