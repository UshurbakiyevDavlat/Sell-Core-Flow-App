<?php

namespace AppModules\Orders\Providers;

use AppModules\Orders\Consumers\ExecuteLimitOrdersByPriceUpdate;
use AppModules\Orders\Consumers\ExecuteLimitPendingOrder;
use AppModules\Orders\Events\OrderCanceledEvent;
use AppModules\Orders\Events\OrderExecutedEvent;
use AppModules\Orders\Listeners\CreateTradeFromOrderListener;
use AppModules\Orders\Listeners\HandleOrderExecutionListener;
use AppModules\Orders\Listeners\ReleaseOrderListener;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class OrdersServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../Routes/api.php');
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
        $this->configureEvents();

        if ($this->app->runningInConsole()) {
            // todo make automatically loading all commands
            $this->commands([
                ExecuteLimitPendingOrder::class,
                ExecuteLimitOrdersByPriceUpdate::class,
            ]);
        }
    }

    private function configureEvents(): void
    {
        Event::listen(
            OrderExecutedEvent::class,
            CreateTradeFromOrderListener::class
        );

        Event::listen(
            OrderExecutedEvent::class,
            HandleOrderExecutionListener::class,
        );

        Event::listen(
            OrderCanceledEvent::class,
            ReleaseOrderListener::class,
        );
    }
}
