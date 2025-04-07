<?php

namespace App\Providers;

use App\Domain\User\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use App\Domain\Approval\Models\Approval;
use App\Domain\Approval\Policies\ApprovalPolicy;
use App\Domain\Candidate\Models\Candidate;
use App\Domain\Candidate\Policies\CandidatePolicy;
use App\Domain\Interview\Models\Interview;
use App\Domain\Interview\Models\Interviewer;
use App\Domain\Interview\Policies\InterviewPolicy;
use App\Domain\Interviewer\Policies\InterviewerPolicy;
use App\Domain\RecruitmentBatch\Models\RecruitmentBatch;
use App\Domain\RecruitmentBatch\Policies\RecruitmentBatchPolicy;



class AppPolicyProvider extends ServiceProvider
{
    public function boot(): void
    {

        Gate::before(function (User $user, string $ability) {
            if ($user->hasRole(config('role.HEAD_OF_HR'))) {
                return true;
            };

            return null;
        });
        Gate::policy(
            Candidate::class,
            CandidatePolicy::class
        );

        Gate::policy(
            RecruitmentBatch::class,
            RecruitmentBatchPolicy::class
        );

        Gate::policy(
            Interview::class,
            InterviewPolicy::class
        );

        Gate::policy(
            Interviewer::class,
            InterviewerPolicy::class
        );

        Gate::policy(
            Approval::class,
            ApprovalPolicy::class
        );
    }
}
