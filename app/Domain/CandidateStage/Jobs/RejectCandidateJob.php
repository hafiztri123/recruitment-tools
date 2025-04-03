<?php

namespace App\Domain\CandidateStage\Jobs;

use App\Domain\CandidateStage\Interfaces\CandidateStageServiceInterface;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RejectCandidateJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public function __construct(
        protected int $candidateID,
        protected int $batchID
    ) {}

    public function handle(): void
    {
        if ($this->batch() && $this->batch()->cancelled()) {
            return;
        }

        $candidateStageService = app(CandidateStageServiceInterface::class);

        $candidateStageService->rejectCandidates(
            candidateID: $this->candidateID,
            recruitmentBatchID: $this->batchID
        );

        //TODO: ADD DISPATCH TO SEND REJECTION EMAIL
    }
}
