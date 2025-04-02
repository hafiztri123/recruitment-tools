<?php

namespace App\Domain\CandidateStage\Repositories;

use App\Domain\CandidateStage\Interfaces\CandidateStageRepositoryInterface;
use App\Domain\CandidateStage\Models\CandidateStage;

class EloquentCandidateStageRepository implements CandidateStageRepositoryInterface
{
    public function candidateStageExistsByID(int $id): bool
    {
        return CandidateStage::where('id', $id)->exists();
    }

    public function create(CandidateStage $candidateStage): int
    {
        $candidateStage->saveOrFail();
        return $candidateStage->id;
    }

    public function updateCandidateStage(CandidateStage $candidateStage, array $data): void
    {
        $candidateStage->updateOrFail($data);

    }


    public function findById(int $id): CandidateStage
    {
        return CandidateStage::where('id', $id)->firstOrFail();
    }

}
