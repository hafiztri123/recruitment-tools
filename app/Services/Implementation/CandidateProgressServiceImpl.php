<?php

namespace App\Services\Implementation;

use App\Models\CandidateProgress;
use App\Repositories\CandidateProgressRepository;
use App\Repositories\CandidateRepository;
use App\Repositories\CandidateStageRepository;
use App\Repositories\RecruitmentBatchRepository;
use App\Services\CandidateProgressService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CandidateProgressServiceImpl implements CandidateProgressService
{
    public function __construct(
        protected CandidateProgressRepository $candidateProgressRepository,
        protected CandidateRepository $candidateRepository,
        protected RecruitmentBatchRepository $recruitmentBatchRepository,
        protected CandidateStageRepository $candidateStageRepository

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
