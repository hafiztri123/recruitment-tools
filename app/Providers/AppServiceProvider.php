<?php

namespace App\Providers;

use App\Repositories\DepartmentRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;
use App\Services\Implementation\UserServiceImpl;
use App\Services\UserService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserService::class, UserServiceImpl::class);
        $this->app->bind(DepartmentRepository::class, DepartmentRepository::class);
        $this->app->bind(UserRepository::class, UserRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

    }
}
