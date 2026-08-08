<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\SuperAdminMiddleware;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\AuthorMiddleware;
use App\Http\Middleware\EmployerMiddleware;
use App\Http\Middleware\SeekerMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        // api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // ✅ Middleware aliases (role-based middleware)
        $middleware->alias([
            'superadmin' => SuperAdminMiddleware::class,
            'admin' => AdminMiddleware::class,
            'author' => AuthorMiddleware::class,
            'employer' => EmployerMiddleware::class,
            'seeker' => SeekerMiddleware::class,
        ]);

        // ✅ Global middleware (sab requests par chalein)
        $middleware->append([
            // \App\Http\Middleware\TrustHosts::class,
            // \App\Http\Middleware\TrustProxies::class,
        ]);

        // ✅ Web middleware group mein additional middleware
        $middleware->web(append: [
            // \App\Http\Middleware\VerifyCsrfToken::class,
        ]);

        // ✅ API middleware group
        $middleware->api(append: [
            // 'throttle:api',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
