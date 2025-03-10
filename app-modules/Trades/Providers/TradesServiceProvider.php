<?php

namespace AppModules\Trades\Providers;

use AppModules\Trades\Events\TradeCreated;
use AppModules\Trades\Listeners\HandleTradeExecution;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class TradesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../Routes/api.php');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->configureEvents();
    }

    private function configureEvents(): void
    {
        Event::listen(
            TradeCreated::class,
            HandleTradeExecution::class,
        );
    }
}
