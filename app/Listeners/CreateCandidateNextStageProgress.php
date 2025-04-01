<?php

namespace App\Listeners;

use App\Events\CandidateNextStageCreated;
use App\Services\CandidateProgressService;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateCandidateNextStageProgress implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct(
        private CandidateProgressService $candidateProgressService,
    )
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CandidateNextStageCreated $event): void
    {
        $this->candidateProgressService->createCandidateProgress(
            candidateID: $event->candidateID,
            recruitmentBatchID: $event->recruitmentBatchID,
            candidateStageID: $event->candidateStageID
        );
    }
}
