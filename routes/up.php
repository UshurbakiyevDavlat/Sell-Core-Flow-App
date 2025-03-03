<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('db', fn () => tryAndReport(fn () => DB::select('SELECT 1'))
);

Route::get('cache', fn () => tryAndReport(fn () => Cache::get('healthz'))
);
