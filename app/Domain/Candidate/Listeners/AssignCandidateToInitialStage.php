<?php

namespace App\Domain\Candidate\Listeners;

use App\Domain\Candidate\Events\CandidateCreated;
use App\Domain\CandidateProgress\Interfaces\CandidateProgressServiceInterface;
use App\Domain\CandidateStage\Interfaces\CandidateStageServiceInterface;
use Illuminate\Contracts\Queue\ShouldQueue;

class AssignCandidateToInitialStage implements ShouldQueue
{

    /**
     * Create the event listener.
     */

     public $tries = 3;
     public $backoff = 60;

    public function __construct(
        private CandidateProgressServiceInterface $candidateProgressService,
        private CandidateStageServiceInterface $candidateStageService
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
