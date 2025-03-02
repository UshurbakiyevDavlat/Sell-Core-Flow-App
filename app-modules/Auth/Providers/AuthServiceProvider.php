<?php

namespace AppModules\Auth\Providers;

use AppModules\Auth\Repositories\UserRepository;
use AppModules\Auth\Services\AuthService;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../Routes/api.php');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/migrations');
    }

    public function register(): void
    {
        $this->app->singleton(UserRepository::class, fn() => new UserRepository());
        $this->app->singleton(AuthService::class, fn($app) => new AuthService($app->make(UserRepository::class)));
    }
}
