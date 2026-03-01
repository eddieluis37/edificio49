<?php

use Illuminate\Foundation\Application;
use App\Http\Middleware\CheckInstallation;
use App\Http\Middleware\PreventInstalledAccess;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Registrar alias de middleware
        $middleware->alias([
            'check.installation' => CheckInstallation::class,
            'prevent.installed' => PreventInstalledAccess::class,
        ]);

        // Muy Importante: Aplicar check.installation a todas las rutas web EXCEPTO setup
        $middleware->web(append: [
            CheckInstallation::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
