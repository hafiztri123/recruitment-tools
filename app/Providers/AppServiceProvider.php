<?php

namespace App\Providers;

use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('CreateRecruitmentBatches', [UserPolicy::class, 'CreateRecruitmentBatches']);
        Gate::define('AssignRoles', [UserPolicy::class, 'AssignRoles']);
        Gate::define('IsHeadOfHR', [UserPolicy::class, 'IsHeadOfHR']);
    }
}
