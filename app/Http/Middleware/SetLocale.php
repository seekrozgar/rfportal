<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Helpers\LanguageHelper;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // ✅ Check if session has locale
        if ($request->hasSession() && $request->session()->has('locale')) {
            $locale = $request->session()->get('locale');
            app()->setLocale($locale);
        }

        // ✅ Check if URL has lang parameter
        if ($request->has('lang')) {
            $locale = $request->input('lang');
            $availableLocales = LanguageHelper::getAvailableLocales();

            if (in_array($locale, $availableLocales)) {
                if ($request->hasSession()) {
                    $request->session()->put('locale', $locale);
                }
                app()->setLocale($locale);
            }
        }

        return $next($request);
    }
}
