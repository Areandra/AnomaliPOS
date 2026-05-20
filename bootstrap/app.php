<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
        $middleware->validateCsrfTokens(except: ['*']);
        $middleware->alias([
            'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
            'auth.restaurant' => \App\Http\Middleware\AuthenticateRestaurantMiddleware::class,
            'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
            'trusted.device' => \App\Http\Middleware\TrustedDeviceMiddleware::class,
            'plan.acsess' => \App\Http\Middleware\PlanAccessMiddleware::class,
            'tenant.context' => \App\Http\Middleware\TenantMiddleware::class,
            'costumer' => \App\Http\Middleware\CostumerMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
