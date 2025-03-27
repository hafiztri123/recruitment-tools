<?php

namespace App\Providers;

use App\Policies\UserPolicy;
use App\Repositories\DepartmentRepositoryInterface;
use App\Repositories\Implementation\EloquentDepartmentRepository;
use App\Repositories\Implementation\EloquentUserRepository;
use App\Repositories\UserRepositoryInterface;
use App\Services\UserServiceInterface;
use Illuminate\Support\ServiceProvider;
use App\Services\Implementation\UserService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(DepartmentRepositoryInterface::class, EloquentDepartmentRepository::class);
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

    }
}
