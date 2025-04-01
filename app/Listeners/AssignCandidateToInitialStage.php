<?php

namespace App\Listeners;

use App\Events\CandidateCreated;
use App\Models\CandidateProgress;
use App\Repositories\CandidateProgressRepository;
use App\Services\CandidateProgressService;
use App\Services\CandidateStageService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AssignCandidateToInitialStage implements ShouldQueue
{

    /**
     * Create the event listener.
     */

     public $tries = 3;
     public $backoff = 60;

    public function __construct(
        private CandidateProgressService $candidateProgressService,
        private CandidateStageService $candidateStageService
    ){}

    /**
     * Handle the event.
     */
    public function handle(CandidateCreated $event): void
    {
        $FIRST_STAGE = 1;

        $candidateStageID = $this->candidateStageService->createCandidateStage($FIRST_STAGE);
        $this->candidateProgressService->createCandidateProgress(
            candidateID: $event->candidate->id,
            recruitmentBatchID: $event->batchID,
            candidateStageID: $candidateStageID
        );
    }
}
