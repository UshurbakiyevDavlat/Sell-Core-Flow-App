<?php

namespace AppModules\Orders\Providers;

use AppModules\Orders\Consumers\ExecuteLimitOrdersByPriceUpdate;
use AppModules\Orders\Consumers\ExecuteLimitPendingOrder;
use AppModules\Orders\Events\OrderExecuted;
use AppModules\Orders\Listeners\CreateTradeFromOrder;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class OrdersServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../Routes/api.php');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->configureEvents();

        if ($this->app->runningInConsole()) {
            //todo make automatically loading all commands
            $this->commands([
                ExecuteLimitPendingOrder::class,
                ExecuteLimitOrdersByPriceUpdate::class,
            ]);
        }
    }

    private function configureEvents(): void
    {
        Event::listen(
            OrderExecuted::class,
            CreateTradeFromOrder::class
        );
    }
}
