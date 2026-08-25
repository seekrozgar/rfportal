<?php
// app/Http/Middleware/ClearSettingsCache.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ClearSettingsCache
{
    public function handle(Request $request, Closure $next)
    {
        // ✅ Clear cache on specific routes
        if ($request->is('admin/settings*') && $request->method() !== 'GET') {
            Cache::forget('site_settings_data');
        }

        return $next($request);
    }
}
