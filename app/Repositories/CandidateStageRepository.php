<?php

namespace App\Repositories;

use App\Models\CandidateStage;

interface CandidateStageRepository
{
    public function candidateStageExistsByID(int $id): bool;
    public function create(CandidateStage $candidateStage): int;
    public function updateCandidateStage(CandidateStage $candidateStage, array $data): void;
    public function findById(int $id): CandidateStage;
}
