<?php

namespace App\Domain\Candidate\Jobs;

use App\Domain\Candidate\Interfaces\CandidateServiceInterface;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateCandidateJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * Create a new job instance.
     */
    public function __construct(
        protected array $candidateData,
        protected int $batchID,
    )    {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->batch() && $this->batch()->cancelled()){
            return;
        }

        $candidateService = app(CandidateServiceInterface::class);


        $candidateService->createCandidate(
            candidateData: $this->candidateData,
            batchID: $this->batchID
        );
    }
}
