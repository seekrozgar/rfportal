<?php
// app/Http/Middleware/CheckMaintenanceMode.php

namespace App\Http\Middleware;

use App\Helpers\SiteHelper;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    /**
     * ✅ Handle an incoming request - Laravel 13+
     */
    public function handle(Request $request, Closure $next): Response
    {
        // ✅ Skip maintenance mode for admin routes
        if ($request->is('admin/*') || $request->is('login')) {
            return $next($request);
        }

        if (SiteHelper::isMaintenanceMode()) {
            return response()->view('maintenance', [
                'message' => SiteHelper::maintenanceMessage(),
            ], 503);
        }

        return $next($request);
    }
}
