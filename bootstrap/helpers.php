<?php

declare(strict_types=1);

use Illuminate\Support\Facades\View;

function tryAndReport(callable $callback): Illuminate\Http\Response
{
    $exception = null;

    try {
        $callback();
    } catch (Throwable $e) {

        if (app()->hasDebugModeEnabled()) {
            throw $e;
        }

        report($e);

        $exception = $e->getMessage();
    }

    return response(View::file(
        base_path('vendor/laravel/framework/src/Illuminate/Foundation/resources/health-up.blade.php'),
        [
            'exception' => $exception,
        ]
    ), status: $exception ? 500 : 200);
}
