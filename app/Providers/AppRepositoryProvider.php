<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Approval\Interfaces\ApprovalRepositoryInterface;
use App\Domain\Approval\Repositories\EloquentApprovalRepository;
use App\Domain\Candidate\Interfaces\CandidateRepositoryInterface;
use App\Domain\Candidate\Repositories\EloquentCandidateRepository;
use App\Domain\CandidateProgress\Interfaces\CandidateProgressRepositoryInterface;
use App\Domain\CandidateProgress\Repositories\EloquentCandidateProgressRepository;
use App\Domain\CandidateStage\Interfaces\CandidateStageRepositoryInterface;
use App\Domain\CandidateStage\Repositories\EloquentCandidateStageRepository;
use App\Domain\Department\Interfaces\DepartmentRepositoryInterface;
use App\Domain\Department\Repositories\EloquentDepartmentRepository;
use App\Domain\Interview\Interfaces\InterviewRepositoryInterface;
use App\Domain\Interview\Repositories\EloquentInterviewRepository;
use App\Domain\Interviewer\Interfaces\InterviewerRepositoryInterface;
use App\Domain\Interviewer\Repositories\EloquentInterviewerRepository;
use App\Domain\Position\Interfaces\PositionRepositoryInterface;
use App\Domain\Position\Repositories\EloquentPositionRepository;
use App\Domain\RecruitmentBatch\Interfaces\RecruitmentBatchRepositoryInterface;
use App\Domain\RecruitmentBatch\Repositories\EloquentRecruitmentBatchRepository;
use App\Domain\RecruitmentStage\Interfaces\RecruitmentStageRepositoryInterface;
use App\Domain\RecruitmentStage\Repositories\EloquentRecruitmentStageRepository;
use App\Domain\Role\Interfaces\RoleRepositoryInterface;
use App\Domain\Role\Repositories\EloquentRoleRepository;
use App\Domain\User\Interfaces\UserRepositoryInterface;
use App\Domain\User\Repositories\EloquentUserRepository;


class AppRepositoryProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(DepartmentRepositoryInterface::class, EloquentDepartmentRepository::class);
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, EloquentRoleRepository::class);
        $this->app->bind(RecruitmentBatchRepositoryInterface::class, EloquentRecruitmentBatchRepository::class);
        $this->app->bind(PositionRepositoryInterface::class, EloquentPositionRepository::class);
        $this->app->bind(CandidateRepositoryInterface::class, EloquentCandidateRepository::class);
        $this->app->bind(CandidateStageRepositoryInterface::class, EloquentCandidateStageRepository::class);
        $this->app->bind(CandidateProgressRepositoryInterface::class, EloquentCandidateProgressRepository::class);
        $this->app->bind(RecruitmentStageRepositoryInterface::class, EloquentRecruitmentStageRepository::class);
        $this->app->bind(InterviewRepositoryInterface::class, EloquentInterviewRepository::class);
        $this->app->bind(InterviewerRepositoryInterface::class, EloquentInterviewerRepository::class);
        $this->app->bind(ApprovalRepositoryInterface::class, EloquentApprovalRepository::class);
    }
}
