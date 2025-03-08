<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::prefix('api/assets')
    ->group(function () {
        Route::prefix('v1')
            ->group(base_path('app-modules/Assets/Routes/api/v1.php'));
    })
    ->middleware(['api']);
