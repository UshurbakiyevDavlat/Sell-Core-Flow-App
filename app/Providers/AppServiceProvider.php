<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this
            ->configureDatabase()
            ->configureHealthRoutes();
    }

    protected function configureDatabase(): static
    {
        Model::shouldBeStrict(! $this->app->environment('production'));
        DB::prohibitDestructiveCommands((bool) $this->app->environment('production'));

        return $this;
    }

    protected function configureHealthRoutes(): static
    {
        if ($this->app->runningInConsole()) {
            return $this;
        }

        Route::prefix('up')
            ->group(base_path('routes/up.php'));

        return $this;
    }
}
