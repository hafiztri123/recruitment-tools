<?php

namespace App\Providers;

use App\Domain\Candidate\Events\CandidateCreated;
use App\Domain\Candidate\Interfaces\CandidateRepositoryInterface;
use App\Domain\Candidate\Interfaces\CandidateServiceInterface;
use App\Domain\Candidate\Listeners\AssignCandidateToInitialStage;
use App\Domain\Candidate\Listeners\SendEmailToCandidate;
use App\Domain\Candidate\Models\Candidate;
use App\Domain\Candidate\Policies\CandidatePolicy;
use App\Domain\Candidate\Repositories\EloquentCandidateRepository;
use App\Domain\Candidate\Services\CandidateService;
use App\Domain\CandidateProgress\Interfaces\CandidateProgressRepositoryInterface;
use App\Domain\CandidateProgress\Interfaces\CandidateProgressServiceInterface;
use App\Domain\CandidateProgress\Repositories\EloquentCandidateProgressRepository;
use App\Domain\CandidateProgress\Services\CandidateProgressService;
use App\Domain\CandidateStage\Events\CandidateNextStageCreated;
use App\Domain\CandidateStage\Events\CandidateStageUpdated;
use App\Domain\CandidateStage\Interfaces\CandidateStageRepositoryInterface;
use App\Domain\CandidateStage\Interfaces\CandidateStageServiceInterface;
use App\Domain\CandidateStage\Listeners\CreateCandidateNextProgress;
use App\Domain\CandidateStage\Listeners\CreateCandidateNextStage;
use App\Domain\CandidateStage\Repositories\EloquentCandidateStageRepository;
use App\Domain\CandidateStage\Services\CandidateStageService;
use App\Domain\Department\Interfaces\DepartmentRepositoryInterface;
use App\Domain\Department\Repositories\EloquentDepartmentRepository;
use App\Domain\Position\Interfaces\PositionRepositoryInterface;
use App\Domain\Position\Repositories\EloquentPositionRepository;
use App\Domain\RecruitmentBatch\Interfaces\RecruitmentBatchRepositoryInterface;
use App\Domain\RecruitmentBatch\Interfaces\RecruitmentBatchServiceInterface;
use App\Domain\RecruitmentBatch\Models\RecruitmentBatch;
use App\Domain\RecruitmentBatch\Policies\RecruitmentBatchPolicy;
use App\Domain\RecruitmentBatch\Repositories\EloquentRecruitmentBatchRepository;
use App\Domain\RecruitmentBatch\Services\RecruitmentBatchService;
use App\Domain\RecruitmentStage\Interfaces\RecruitmentStageRepositoryInterface;
use App\Domain\RecruitmentStage\Interfaces\RecruitmentStageServiceInterface;
use App\Domain\RecruitmentStage\Repositories\EloquentRecruitmentStageRepository;
use App\Domain\RecruitmentStage\Services\RecruitmentStageService;
use App\Domain\Role\Interfaces\RoleRepositoryInterface;
use App\Domain\Role\Repositories\EloquentRoleRepository;
use App\Domain\User\Interfaces\UserRepositoryInterface;
use App\Domain\User\Interfaces\UserServiceInterface;
use App\Domain\User\Models\User;
use App\Domain\User\Repositories\EloquentUserRepository;
use App\Domain\User\Services\UserService;
use App\Utils\Implementation\JobBatchService;
use App\Utils\JobBatchServiceInterface;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //REPOSITORY
        $this->app->bind(DepartmentRepositoryInterface::class, EloquentDepartmentRepository::class);
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, EloquentRoleRepository::class);
        $this->app->bind(RecruitmentBatchRepositoryInterface::class, EloquentRecruitmentBatchRepository::class);
        $this->app->bind(PositionRepositoryInterface::class, EloquentPositionRepository::class);
        $this->app->bind(CandidateRepositoryInterface::class, EloquentCandidateRepository::class);
        $this->app->bind(CandidateStageRepositoryInterface::class, EloquentCandidateStageRepository::class);
        $this->app->bind(CandidateProgressRepositoryInterface::class, EloquentCandidateProgressRepository::class);
        $this->app->bind(RecruitmentStageRepositoryInterface::class, EloquentRecruitmentStageRepository::class);

        //SERVICES
        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(RecruitmentBatchServiceInterface::class, RecruitmentBatchService::class);
        $this->app->bind(CandidateServiceInterface::class, CandidateService::class);
        $this->app->bind(CandidateStageServiceInterface::class, CandidateStageService::class);
        $this->app->bind(RecruitmentStageServiceInterface::class, RecruitmentStageService::class);
        $this->app->bind(CandidateProgressServiceInterface::class, CandidateProgressService::class);
        $this->app->bind(JobBatchServiceInterface::class, JobBatchService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(function (User $user, string $ability){
            if($user->hasRole(config('role.HEAD_OF_HR'))){
                return true;
            };

            return null;
        });

        //Policies registered
        Gate::policy(
            Candidate::class,
            CandidatePolicy::class
        );

        Gate::policy(
            RecruitmentBatch::class,
            RecruitmentBatchPolicy::class
        );

        //Event registered
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

    }
}
