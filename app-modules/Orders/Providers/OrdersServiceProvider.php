<?php

namespace AppModules\Orders\Providers;

use AppModules\Assets\Repositories\AssetRepository;
use AppModules\Orders\Consumers\ProcessPendingOrders;
use AppModules\Orders\Repositories\OrderRepository;
use AppModules\Orders\Services\OrderService;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class OrdersServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../Routes/api.php');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                ProcessPendingOrders::class, //todo make automatically loading all commands
            ]);
        }
    }

    public function register(): void
    {
        $this->app->singleton(OrderRepository::class, fn() => new OrderRepository());
        $this->app->singleton(OrderService::class, fn(Application $app) => new OrderService(
            $app->make(OrderRepository::class),
            $app->make(AssetRepository::class),
        ));
    }
}
