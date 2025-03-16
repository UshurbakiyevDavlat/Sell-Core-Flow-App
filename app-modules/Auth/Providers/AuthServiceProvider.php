<?php

namespace AppModules\Auth\Providers;

use AppModules\Auth\Models\User;
use AppModules\Auth\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../Routes/api.php');
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
        $this->configureObservers();
    }

    public function configureObservers(): void
    {
        User::observe(UserObserver::class);
    }
}
