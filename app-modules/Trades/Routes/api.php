<?php

use Illuminate\Support\Facades\Route;

Route::prefix('api/trades')
    ->group(function () {
        Route::prefix('v1')
            ->group(base_path('app-modules/Trades/Routes/api/v1.php'));
    })
    ->middleware('api');
