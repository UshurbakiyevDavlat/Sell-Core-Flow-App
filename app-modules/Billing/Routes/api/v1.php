<?php

use AppModules\Billing\Http\Controllers\BillingController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/deposit', [BillingController::class, 'deposit']); // Пополнение баланса
    Route::get('/balance', [BillingController::class, 'balance']); // Получение текущего баланса
    Route::get('/history', [BillingController::class, 'history']); // История транзакций

    Route::patch('/charge', [BillingController::class, 'charge']); // Списание при создании ордера
    Route::patch('/release', [BillingController::class, 'release']); // Возврат при отмене ордера
    Route::patch('/profit', [BillingController::class, 'profit']); // Начисление прибыли после сделки
});
