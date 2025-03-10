<?php

use AppModules\Trades\Http\Controllers\TradesController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/', [TradesController::class, 'store']);
    Route::patch('/{id}/execute', [TradesController::class, 'execute']);
});
