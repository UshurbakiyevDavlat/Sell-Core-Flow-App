<?php

declare(strict_types=1);

use AppModules\Auth\Http\Controllers\AuthController;
use AppModules\Auth\Http\Controllers\PermissionController;
use AppModules\Auth\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'can:manage_roles'])->group(function () {
    Route::prefix('roles')->group(function () {
        Route::get('/', [RoleController::class, 'index']);
        Route::post('/', [RoleController::class, 'create']);
        Route::delete('/{role}', [RoleController::class, 'delete']);
        Route::post('/{role}/assign/{user}', [RoleController::class, 'assignRole']);
        Route::post('/{role}/permissions', [RoleController::class, 'assignPermissions']);
    });

    Route::prefix('permissions')->group(function () {
        Route::get('/', [PermissionController::class, 'index']);
        Route::post('/', [PermissionController::class, 'create']);
        Route::delete('/{permission}', [PermissionController::class, 'delete']);
    });
});
