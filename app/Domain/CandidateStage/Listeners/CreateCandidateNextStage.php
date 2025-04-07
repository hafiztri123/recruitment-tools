<?php

namespace App\Domain\CandidateStage\Listeners;

use App\Domain\CandidateStage\Events\CandidateNextStageCreated;
use App\Domain\CandidateStage\Events\CandidateStageUpdated;
use App\Domain\CandidateStage\Interfaces\CandidateStageServiceInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;

class CreateCandidateNextStage implements ShouldQueue
{
    /**
     * Create the event listener.
     */


    public function __construct(
        protected CandidateStageServiceInterface $candidateStageService
    )
    {}

    /**
     * Handle the event.
     */
    public function handle(CandidateStageUpdated $event): void
    {
        DB::transaction(function () use ($event) {
            $NEXT_STAGE = 1;
            $stageID = $this->candidateStageService->createCandidateStage($event->candidateStage->recruitmentStage->order + $NEXT_STAGE);

            CandidateNextStageCreated::dispatch(
                $event->candidateID,
                $event->batchID,
                $stageID
            );
        });




    }
}
