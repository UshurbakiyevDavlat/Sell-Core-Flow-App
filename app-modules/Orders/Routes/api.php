<?php

use Illuminate\Support\Facades\Route;

Route::prefix('api/orders')
    ->group(function () {
        Route::prefix('v1')
            ->group(base_path('app-modules/Orders/Routes/api/v1.php'));
    })
    ->middleware('api');
