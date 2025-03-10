<?php

namespace AppModules\Billing\Providers;

use AppModules\Billing\Events\BalanceUpdatedEvent;
use AppModules\Billing\Listeners\UpdateBalanceListener;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class BillingServiceProvider extends ServiceProvider
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
            BalanceUpdatedEvent::class,
            UpdateBalanceListener::class
        );
    }
}
