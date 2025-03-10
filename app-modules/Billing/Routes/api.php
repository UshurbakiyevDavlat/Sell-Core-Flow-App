<?php

use Illuminate\Support\Facades\Route;

Route::prefix('api/billing')
    ->group(function () {
        Route::prefix('v1')->group(base_path('app-modules/Billing/Routes/api/v1.php'));
    })
    ->middleware('api');
