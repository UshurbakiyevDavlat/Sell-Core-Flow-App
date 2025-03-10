<?php

use AppModules\Orders\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/', [OrderController::class, 'index']); // Список ордеров

    Route::post('/', [OrderController::class, 'store']); // Создать ордер
    Route::post('/{id}/execute-market-order', [OrderController::class, 'executeMarketOrder']);

    Route::delete('/{id}', [OrderController::class, 'cancelOrder']); // Отмена ордера
});
