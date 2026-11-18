<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Middleware\RoleMiddleware;

class SafeRoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $role, $guard = null)
    {
        $user = auth($guard)->user();

        // 1. Super Admin has full access to all areas
        if ($user && ($user->is_superadmin ?? false)) {
            return $next($request);
        }

        // 2. Graceful bypass if roles table is not yet migrated
        try {
            if (!Schema::hasTable('roles')) {
                return $next($request);
            }
        } catch (\Throwable $e) {
            return $next($request);
        }

        // 3. Delegate to Spatie Role Middleware
        return app(RoleMiddleware::class)->handle($request, $next, $role, $guard);
    }
}
