<?php

use AppModules\Strategies\Http\Controllers\StrategyController;
use Illuminate\Support\Facades\Route;

Route::post('run', [StrategyController::class, 'runStrategies']);
