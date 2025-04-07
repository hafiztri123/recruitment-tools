<?php

namespace App\Domain\CandidateProgress\Services;

use App\Domain\Candidate\Exceptions\CandidateNotFoundException;
use App\Domain\Candidate\Interfaces\CandidateRepositoryInterface;
use App\Domain\CandidateProgress\Interfaces\CandidateProgressRepositoryInterface;
use App\Domain\CandidateProgress\Interfaces\CandidateProgressServiceInterface;
use App\Domain\CandidateProgress\Models\CandidateProgress;
use App\Domain\CandidateStage\Exceptions\CandidateStageNotFoundException;
use App\Domain\CandidateStage\Interfaces\CandidateStageRepositoryInterface as InterfacesCandidateStageRepositoryInterface;
use App\Domain\RecruitmentBatch\Exceptions\RecruitmentBatchNotFoundException;
use App\Domain\RecruitmentBatch\Interfaces\RecruitmentBatchRepositoryInterface;

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
            throw new CandidateNotFoundException(candidateId: $candidateID);
        }

        if (!$this->recruitmentBatchRepository->recruitmentBatchExistsByID($recruitmentBatchID)){
            throw new RecruitmentBatchNotFoundException(recruitmentBatchId: $recruitmentBatchID);
        }

        if (!$this->candidateStageRepository->candidateStageExistsByID($candidateStageID)){
            throw new CandidateStageNotFoundException(candidateStageId: $candidateStageID);
        }


        $candidateProgress = CandidateProgress::make([
            'recruitment_batch_id' => $recruitmentBatchID,
            'candidate_id' => $candidateID,
            'candidate_stage_id' => $candidateStageID
        ]);

        $this->candidateProgressRepository->create($candidateProgress);
    }
}
