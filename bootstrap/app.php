<?php

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\EnsureBiodataIsComplete;
use App\Http\Middleware\SecurityHeaders;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            RateLimiter::for('login', function (Request $request) {
                $key = strtolower((string) $request->input('email', '')) . '|' . $request->ip();

                return Limit::perMinute(5)->by($key);
            });

            RateLimiter::for('register', function (Request $request) {
                return Limit::perMinute(3)->by($request->ip());
            });
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth' => Authenticate::class,
            'role' => CheckRole::class,
            'auth.biodata' => EnsureBiodataIsComplete::class,
        ]);

        // Trust Nginx/Certbot so HTTPS detection and secure cookies work
        $middleware->trustProxies(at: '*');

        $middleware->append(SecurityHeaders::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
