<?php

namespace App\Listeners;

use App\Events\CandidateCreated;
use App\Models\CandidateProgress;
use App\Repositories\CandidateProgressRepository;
use App\Services\CandidateProgressService;
use App\Services\CandidateStageService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AssignCandidateToInitialStage
{
    /**
     * Create the event listener.
     */
    public function __construct(
        private CandidateProgressService $candidateProgressService,
        private CandidateStageService $candidateStageService
    ){}

    /**
     * Handle the event.
     */
    public function handle(CandidateCreated $event): void
    {
        $candidateStageID = $this->candidateStageService->createInitialCandidateStage();
        $this->candidateProgressService->createCandidateProgress(
            candidateID: $event->candidate->id,
            recruitmentBatchID: $event->batchID,
            candidateStageID: $candidateStageID
        );
    }
}
