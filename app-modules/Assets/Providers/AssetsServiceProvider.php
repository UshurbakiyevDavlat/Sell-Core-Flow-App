<?php

namespace AppModules\Assets\Providers;

use AppModules\Assets\Console\Commands\InitializeAssetCommand;
use AppModules\Assets\Console\Commands\UpdateAssetPricesCommand;
use AppModules\Assets\Factories\MarketDataProviderFactory;
use Illuminate\Support\ServiceProvider;

class AssetsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../Routes/api.php');
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                InitializeAssetCommand::class,
                UpdateAssetPricesCommand::class,
            ]);
        }
    }

    public function register(): void
    {
        $this->app->singleton(MarketDataProviderFactory::class, function ($app) {
            return new MarketDataProviderFactory($app['config']['market']);
        });
    }
}
