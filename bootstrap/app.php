<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Railway runs the app behind a reverse proxy; trust it so HTTPS
        // detection, secure cookies and generated URLs work correctly.
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'user' => \App\Http\Middleware\UserMiddleware::class,
            'hrd' => \App\Http\Middleware\HrdMiddleware::class,
            'atasan' => \App\Http\Middleware\AtasanDivisiMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
