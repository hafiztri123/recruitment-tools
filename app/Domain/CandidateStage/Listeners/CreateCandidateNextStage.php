<?php

namespace App\Domain\CandidateStage\Listeners;

use App\Domain\CandidateStage\Events\CandidateNextStageCreated;
use App\Domain\CandidateStage\Events\CandidateStageUpdated;
use App\Domain\CandidateStage\Interfaces\CandidateStageServiceInterface;
use App\Domain\RecruitmentStage\Interfaces\RecruitmentStageRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;

class CreateCandidateNextStage implements ShouldQueue
{
    /**
     * Create the event listener.
     */


    public function __construct(
        protected CandidateStageServiceInterface $candidateStageService,
        protected RecruitmentStageRepositoryInterface $recruitmentStageRepository

    )
    {}

    /***************************************************
     *
     *
     *
     * * Purpose:
     *
     *
     *
     **************************************************/

    public function handle(CandidateStageUpdated $event): void
    {
        DB::transaction(function () use ($event) {
            $candidateStage = $event->candidateStage;
            $currentStageOrder = $candidateStage->recruitmentStage->order;
            $finalStageOrder = $this->recruitmentStageRepository->getFinalStageOrder();

            if($currentStageOrder < $finalStageOrder){
                $nextStageOrder = $currentStageOrder + 1;
                $stageID = $this->candidateStageService->createCandidateStage(order: $nextStageOrder);

                CandidateNextStageCreated::dispatch(
                    $event->candidateID,
                    $event->batchID,
                    $stageID
                );
            }

        });
    }
}
