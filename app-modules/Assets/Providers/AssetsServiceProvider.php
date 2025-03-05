<?php

namespace AppModules\Assets\Providers;

use AppModules\Assets\Repositories\AssetRepository;
use AppModules\Assets\Services\AssetService;
use Illuminate\Support\ServiceProvider;

class AssetsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../Routes/api.php');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    public function register(): void
    {
        $this->app->singleton(AssetRepository::class, fn() => new AssetRepository());
        $this->app->singleton(AssetService::class, fn($app) => new AssetService($app->make(AssetRepository::class)));
    }
}
