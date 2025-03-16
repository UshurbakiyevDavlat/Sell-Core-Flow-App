<?php

use Illuminate\Support\Facades\Route;

Route::prefix('api/strategies')->group(function () {
    Route::prefix('v1')->group(base_path('app-modules/Strategies/Routes/api/v1.php'));
})->middleware('api');
