<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// Central Laravel bootstrap:
// - route files
// - middleware pipeline
// - exception handling
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        // HTTP routes for the web UI
        web: __DIR__.'/../routes/web.php',
        // Artisan console commands
        commands: __DIR__.'/../routes/console.php',
        // Lightweight health endpoint
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Register global/custom middleware here when needed.
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Register custom exception rendering/reporting here.
    })->create();
