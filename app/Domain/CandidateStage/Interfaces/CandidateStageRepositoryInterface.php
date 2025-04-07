<?php

namespace App\Domain\CandidateStage\Interfaces;

use App\Domain\CandidateStage\Models\CandidateStage;

interface CandidateStageRepositoryInterface
{
    public function candidateStageExistsByID(int $id): bool;
    public function create(CandidateStage $candidateStage): int;
    public function updateCandidateStage(CandidateStage $candidateStage, array $data): void;
    public function findById(int $id): CandidateStage;
    public function lockForUpdate(int $id): CandidateStage;
}
