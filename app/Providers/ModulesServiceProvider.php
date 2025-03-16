<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ModulesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $modules = config('modules.enabled', []);

        foreach ($modules as $module) {
            $this->app->register("AppModules\\$module\Providers\\{$module}ServiceProvider");
        }
    }

    public function boot() {}
}
