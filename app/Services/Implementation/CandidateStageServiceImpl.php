<?php


namespace App\Services\Implementation;

use App\Models\CandidateStage;
use App\Repositories\CandidateStageRepository;
use App\Repositories\RecruitmentStageRepository;
use App\Services\CandidateStageService;

class CandidateStageServiceImpl implements CandidateStageService
{

    private $FIRST_STAGE = 1;

    public function __construct(
        protected CandidateStageRepository $candidateStageRepository,
        protected RecruitmentStageRepository $recruitmentStageRepository
    ){}

    public function createInitialCandidateStage(): int
    {
        $candidateStage = CandidateStage::make([
            'status' => 'pending',
        ]);

        $candidateStage->stage_id = $this->recruitmentStageRepository->findByOrder($this->FIRST_STAGE)->id;
        return $this->candidateStageRepository->create(candidateStage: $candidateStage);
    }

}
