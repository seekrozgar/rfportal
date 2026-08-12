<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. If not logged in, boot them to the login screen
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        // 2. If logged in but lacks admin roles, show the 403 screen
        if (!Auth::user()->hasAnyRole(['superadmin', 'admin'])) {
            abort(403, 'Unauthorized access. Contact Administrator.');
        }

        return $next($request);
    }
}
