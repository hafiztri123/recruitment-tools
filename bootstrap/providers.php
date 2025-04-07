<?php

use App\Providers\AppEventProvider;
use App\Providers\AppPolicyProvider;
use App\Providers\AppRepositoryProvider;

return [
    App\Providers\AppServiceProvider::class,
    AppRepositoryProvider::class,
    AppPolicyProvider::class,
    AppEventProvider::class
];
