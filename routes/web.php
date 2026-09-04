<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| All route files are organized separately for better maintainability.
| No routes are defined here - only includes.
|
*/

// ============================================================
// 📧 EMAIL VERIFICATION ROUTES (Closure based)
// ============================================================
require __DIR__ . '/email-verification.php';

// ============================================================
// 🔑 AUTHENTICATION ROUTES
// ============================================================
require __DIR__ . '/auth.php';
require __DIR__ . '/auth-breeze.php';

// ============================================================
// 🏠 FRONTEND / PUBLIC ROUTES
// ============================================================
require __DIR__ . '/frontend.php';

// ============================================================
// 📊 DASHBOARD & GLOBAL ROUTES
// ============================================================
require __DIR__ . '/dashboard.php';

// ============================================================
// 👑 ADMIN ROUTES
// ============================================================
require __DIR__ . '/admin.php';

// ============================================================
// ✍️ AUTHOR ROUTES
// ============================================================
require __DIR__ . '/author.php';

// ============================================================
// 🏢 EMPLOYER ROUTES
// ============================================================
require __DIR__ . '/employer.php';

// ============================================================
// 👤 SEEKER ROUTES
// ============================================================
require __DIR__ . '/seeker.php';

// ============================================================
// 💳 PAYMENT ROUTES
// ============================================================
require __DIR__ . '/payment.php';

// ============================================================
// 🚪 LOGOUT (GET)
// ============================================================
Route::get('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout.get');

// ============================================================
// 🌐 LANGUAGE SWITCH ROUTE
// ============================================================
Route::get('/language/switch/{locale}', function ($locale) {
    $availableLocales = ['en', 'ur', 'ar', 'fr', 'es', 'de', 'zh', 'hi'];
    if (in_array($locale, $availableLocales)) {
        session()->put('locale', $locale);
        app()->setLocale($locale);
    }
    return redirect()->back();
})->name('language.switch');

// ============================================================
// 🔍 DEBUG ROUTE (Development only)
// ============================================================
if (app()->environment('local')) {
    Route::get('/debug-session', function () {
        return [
            'session_all' => session()->all(),
            'session_has_unverified' => session()->has('unverified_user_id'),
            'unverified_user_id' => session('unverified_user_id'),
            'is_logged_in' => auth()->check(),
            'user' => auth()->user() ? [
                'id' => auth()->id(),
                'email' => auth()->user()->email,
                'verified' => auth()->user()->email_verified_at
            ] : null,
        ];
    });
}
