<?php

namespace App\Listeners;

use App\Events\CandidateNextStageCreated;
use App\Events\CandidateStageUpdated;
use App\Models\CandidateStage;
use App\Services\CandidateStageService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateCandidateNextStage implements ShouldQueue
{
    /**
     * Create the event listener.
     */


    public function __construct(
        protected CandidateStageService $candidateStageService
    )
    {}

    /**
     * Handle the event.
     */
    public function handle(CandidateStageUpdated $event): void
    {
        $NEXT_STAGE = 1;
        $stageID = $this->candidateStageService->createCandidateStage($event->candidateStage->recruitmentStage->order + $NEXT_STAGE);

        CandidateNextStageCreated::dispatch(
            $event->candidateID,
            $event->batchID,
            $stageID
        );




    }
}
