<?php

namespace App\Jobs;

use App\Services\CandidateStageService;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateCandidateStageJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected int $candidateID,
        protected int $batchID
    ){}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->batch() && $this->batch()->cancelled()) {
            return;
        }

        $candidateStageService = app(CandidateStageService::class);

        $candidateStageService->moveCandidateToNextStage(
            candidateID: $this->candidateID,
            recruitmentBatchID: $this->batchID
        );

    }
}
