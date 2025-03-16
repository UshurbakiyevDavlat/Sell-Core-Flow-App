<?php

namespace AppModules\Strategies\Providers;

use AppModules\Strategies\Console\Commands\TestStrategyCommand;
use Illuminate\Support\ServiceProvider;

class StrategiesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../Routes/api.php');
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                TestStrategyCommand::class,
            ]);
        }
    }
}
