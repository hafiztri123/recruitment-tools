<?php

namespace App\Repositories\Implementation;

use App\Models\CandidateStage;
use App\Repositories\CandidateStageRepository;

class CandidateStageRepositoryImpl implements CandidateStageRepository
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
