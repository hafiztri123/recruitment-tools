<?php

namespace App\Domain\CandidateProgress\Services;

use App\Domain\Candidate\Interfaces\CandidateRepositoryInterface;
use App\Domain\CandidateProgress\Interfaces\CandidateProgressRepositoryInterface;
use App\Domain\CandidateProgress\Interfaces\CandidateProgressServiceInterface;
use App\Domain\CandidateProgress\Models\CandidateProgress;
use App\Domain\CandidateStage\Interfaces\CandidateStageRepositoryInterface as InterfacesCandidateStageRepositoryInterface;
use App\Domain\RecruitmentBatch\Interfaces\RecruitmentBatchRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CandidateProgressService implements CandidateProgressServiceInterface
{
    public function __construct(
        protected CandidateProgressRepositoryInterface $candidateProgressRepository,
        protected CandidateRepositoryInterface $candidateRepository,
        protected RecruitmentBatchRepositoryInterface $recruitmentBatchRepository,
        protected InterfacesCandidateStageRepositoryInterface $candidateStageRepository
    ){}

    public function createCandidateProgress(int $candidateID, int $recruitmentBatchID, int $candidateStageID): void
    {
        if (!$this->candidateRepository->candidateExistsByID($candidateID)){
            throw new ModelNotFoundException('Candidate not found', 404);
        }

        if (!$this->recruitmentBatchRepository->recruitmentBatchExistsByID($recruitmentBatchID)){
            throw new ModelNotFoundException('Recruitment batch not found', 404);
        }

        if (!$this->candidateStageRepository->candidateStageExistsByID($candidateStageID)){
            throw new ModelNotFoundException('Candidate stage not found', 404);
        }


        $candidateProgress = CandidateProgress::make([
            'recruitment_batch_id' => $recruitmentBatchID,
            'candidate_id' => $candidateID,
            'candidate_stage_id' => $candidateStageID
        ]);

        $this->candidateProgressRepository->create($candidateProgress);
    }
}
