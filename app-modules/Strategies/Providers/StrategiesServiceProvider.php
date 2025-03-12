<?php

namespace AppModules\Strategies\Providers;

use AppModules\Strategies\Console\TestStrategyCommand;
use Illuminate\Support\ServiceProvider;

class StrategiesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                TestStrategyCommand::class,
            ]);
        }
    }
}
