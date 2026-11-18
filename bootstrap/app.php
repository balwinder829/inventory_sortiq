<?php

use Illuminate\Foundation\Application;
use App\Http\Middleware\SuperAdminMiddleware;
use App\Http\Middleware\CheckActiveUser;
use App\Http\Middleware\SafePermissionMiddleware;
use App\Http\Middleware\SafeRoleMiddleware;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'superadmin' => SuperAdminMiddleware::class,
            'permission' => SafePermissionMiddleware::class,
            'role'       => SafeRoleMiddleware::class,
            'active'     => CheckActiveUser::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
