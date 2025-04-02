<?php

namespace App\Domain\CandidateStage\Listeners;

use App\Domain\CandidateProgress\Interfaces\CandidateProgressServiceInterface;
use App\Domain\CandidateStage\Events\CandidateNextStageCreated;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateCandidateNextProgress implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct(
        private CandidateProgressServiceInterface $candidateProgressService,
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
