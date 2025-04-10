<?php

namespace App\Providers;

use App\Domain\Approval\Interfaces\ApprovalServiceInterface;
use App\Domain\Approval\Services\ApprovalService;
use App\Domain\Candidate\Interfaces\CandidateServiceInterface;
use App\Domain\Candidate\Services\CandidateService;
use App\Domain\CandidateProgress\Interfaces\CandidateProgressServiceInterface;
use App\Domain\CandidateProgress\Services\CandidateProgressService;
use App\Domain\CandidateStage\Interfaces\CandidateStageServiceInterface;
use App\Domain\CandidateStage\Services\CandidateStageService;
use App\Domain\Interview\Interfaces\InterviewServiceInterface;
use App\Domain\Interview\Services\InterviewService;
use App\Domain\Interviewer\Interfaces\InterviewerServiceInterface;
use App\Domain\Interviewer\Services\InterviewerService;
use App\Domain\RecruitmentBatch\Interfaces\RecruitmentBatchServiceInterface;
use App\Domain\RecruitmentBatch\Services\RecruitmentBatchService;
use App\Domain\RecruitmentStage\Interfaces\RecruitmentStageServiceInterface;
use App\Domain\RecruitmentStage\Services\RecruitmentStageService;
use App\Domain\User\Interfaces\UserServiceInterface;
use App\Domain\User\Services\UserService;
use App\Shared\JobBatches\Interfaces\JobBatchServiceInterface;
use App\Shared\JobBatches\Services\JobBatchService;
use App\Shared\Services\PermissionService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {


        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(RecruitmentBatchServiceInterface::class, RecruitmentBatchService::class);
        $this->app->bind(CandidateServiceInterface::class, CandidateService::class);
        $this->app->bind(CandidateStageServiceInterface::class, CandidateStageService::class);
        $this->app->bind(RecruitmentStageServiceInterface::class, RecruitmentStageService::class);
        $this->app->bind(CandidateProgressServiceInterface::class, CandidateProgressService::class);
        $this->app->bind(JobBatchServiceInterface::class, JobBatchService::class);
        $this->app->bind(InterviewServiceInterface::class, InterviewService::class);
        $this->app->bind(InterviewerServiceInterface::class, InterviewerService::class);
        $this->app->bind(ApprovalServiceInterface::class, ApprovalService::class);


        $this->app->singleton(PermissionService::class, function ($app){
            return new PermissionService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //Event
        //polcies



    }
}
