<?php

namespace App\Providers;


use App\Domain\Candidate\Events\CandidateCreated;
use App\Domain\Candidate\Listeners\AssignCandidateToInitialStage;
use App\Domain\Candidate\Listeners\SendEmailToCandidate;
use App\Domain\CandidateStage\Events\CandidateNextStageCreated;
use App\Domain\CandidateStage\Events\CandidateStageUpdated;
use App\Domain\CandidateStage\Listeners\CreateCandidateNextProgress;
use App\Domain\CandidateStage\Listeners\CreateCandidateNextStage;
use App\Domain\CandidateStage\Listeners\RequestApprovalForNextStage;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppEventProvider extends ServiceProvider
{
    public function boot(): void
    {
        Event::listen(
            CandidateCreated::class,
            AssignCandidateToInitialStage::class
        );

        Event::listen(
            CandidateCreated::class,
            SendEmailToCandidate::class
        );

        Event::listen(
            CandidateNextStageCreated::class,
            CreateCandidateNextProgress::class,
        );

        Event::listen(
            CandidateStageUpdated::class,
            CreateCandidateNextStage::class
        );

        Event::listen(
            CandidateStageUpdated::class,
            RequestApprovalForNextStage::class
        );
    }
}
