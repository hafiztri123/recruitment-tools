<?php

namespace App\Providers;

use App\Models\RecruitmentBatch;
use App\Models\User;
use App\Repositories\CandidateProgressRepository;
use App\Repositories\CandidateRepository;
use App\Repositories\CandidateStageRepository;
use App\Repositories\DepartmentRepository;
use App\Repositories\Implementation\CandidateProgressRepositoryImpl;
use App\Repositories\Implementation\CandidateRepositoryImpl;
use App\Repositories\Implementation\CandidateStageRepositoryImpl;
use App\Repositories\Implementation\DepartmentRepositoryImpl;
use App\Repositories\Implementation\PositionRepositoryImpl;
use App\Repositories\Implementation\RecruitmentBatchRepositoryImpl;
use App\Repositories\Implementation\RecruitmentStageRepositoryImpl;
use App\Repositories\Implementation\RoleRepositoryImpl;
use App\Repositories\Implementation\UserRepositoryImpl;
use App\Repositories\PositionRepository;
use App\Repositories\RecruitmentBatchRepository;
use App\Repositories\RecruitmentStageRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use App\Services\CandidateProgressService;
use App\Services\CandidateService;
use App\Services\CandidateStageService;
use App\Services\Implementation\CandidateProgressServiceImpl;
use App\Services\Implementation\CandidateServiceImpl;
use App\Services\Implementation\CandidateStageServiceImpl;
use App\Services\Implementation\RecruitmentBatchServiceImpl;
use App\Services\Implementation\RecruitmentStageServiceImpl;
use Illuminate\Support\ServiceProvider;
use App\Services\Implementation\UserServiceImpl;
use App\Services\RecruitmentBatchService;
use App\Services\RecruitmentStageService;
use App\Services\UserService;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //REPOSITORY
        $this->app->bind(DepartmentRepository::class, DepartmentRepositoryImpl::class);
        $this->app->bind(UserRepository::class, UserRepositoryImpl::class);
        $this->app->bind(RoleRepository::class, RoleRepositoryImpl::class);
        $this->app->bind(RecruitmentBatchRepository::class, RecruitmentBatchRepositoryImpl::class);
        $this->app->bind(PositionRepository::class, PositionRepositoryImpl::class);
        $this->app->bind(CandidateRepository::class, CandidateRepositoryImpl::class);
        $this->app->bind(CandidateStageRepository::class, CandidateStageRepositoryImpl::class);
        $this->app->bind(CandidateProgressRepository::class, CandidateProgressRepositoryImpl::class);
        $this->app->bind(RecruitmentStageRepository::class, RecruitmentStageRepositoryImpl::class);

        //SERVICES
        $this->app->bind(UserService::class, UserServiceImpl::class);
        $this->app->bind(RecruitmentBatchService::class, RecruitmentBatchServiceImpl::class);
        $this->app->bind(CandidateService::class, CandidateServiceImpl::class);
        $this->app->bind(CandidateStageService::class, CandidateStageServiceImpl::class);
        $this->app->bind(RecruitmentStageService::class, RecruitmentStageServiceImpl::class);
        $this->app->bind(CandidateProgressService::class, CandidateProgressServiceImpl::class);
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


    }
}
