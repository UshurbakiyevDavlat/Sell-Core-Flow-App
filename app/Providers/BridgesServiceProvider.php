<?php

namespace App\Providers;

use App\Bridges\Auth\UserService;
use App\Bridges\AuthBridgeClient;
use App\Contracts\Auth\UserServiceInterface;
use App\Contracts\AuthBridgeInterface;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class BridgesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(UserServiceInterface::class, fn (Application $app) => $app->make(UserService::class));
        $this->app->singleton(AuthBridgeInterface::class, fn (Application $app) => $app->make(AuthBridgeClient::class));
    }
}
