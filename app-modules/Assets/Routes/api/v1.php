<?php

use AppModules\Assets\Http\Controllers\AssetController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/', [AssetController::class, 'index']);
    Route::get('/{asset}', [AssetController::class, 'show']);

    Route::middleware(['can:manage_assets'])->group(function () {
        Route::post('/', [AssetController::class, 'store']);
        Route::put('/{asset}', [AssetController::class, 'update']);
        Route::patch('/{id}/price', [AssetController::class, 'updatePrice']);
        Route::delete('/{asset}', [AssetController::class, 'destroy']);
    });
});
