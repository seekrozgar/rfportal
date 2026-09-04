<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Email Verification Routes
|--------------------------------------------------------------------------
*/

Route::get('/verify-email', function () {
    Log::info('📧 VERIFY EMAIL ROUTE HIT (CLOSURE)');

    if (session()->has('unverified_user_id')) {
        $user = User::find(session('unverified_user_id'));
        if ($user) {
            Log::info('👤 USER FOUND IN SESSION', ['email' => $user->email]);
            return view('auth.verify-email', [
                'unverifiedEmail' => $user->email,
                'userId' => $user->id,
            ]);
        }
    }

    if (auth()->check()) {
        $user = auth()->user();
        if (!$user->hasVerifiedEmail()) {
            return view('auth.verify-email', [
                'unverifiedEmail' => $user->email,
                'userId' => $user->id,
            ]);
        }
        return redirect()->route('dashboard');
    }

    Log::warning('⚠️ NO USER IN SESSION, REDIRECT TO LOGIN');
    return redirect()->route('login')->with('error', 'Please login first.');
})->name('verification.notice');

Route::post('/verify-email/resend', function () {
    Log::info('📧 RESEND ROUTE HIT (CLOSURE)');

    if (session()->has('unverified_user_id')) {
        $user = User::find(session('unverified_user_id'));
        if ($user) {
            Log::info('👤 SENDING EMAIL TO SESSION USER', ['email' => $user->email]);
            $user->sendEmailVerificationNotification();
            return back()->with('status', 'verification-link-sent');
        }
    }

    if (auth()->check()) {
        $user = auth()->user();
        if (!$user->hasVerifiedEmail()) {
            Log::info('👤 SENDING EMAIL TO LOGGED IN USER', ['email' => $user->email]);
            $user->sendEmailVerificationNotification();
            return back()->with('status', 'verification-link-sent');
        }
    }

    return redirect()->route('login')->with('error', 'User not found.');
})->name('verification.send');

Route::get('/verify-email/confirm/{id}/{hash}', function ($id, $hash) {
    Log::info('📧 CONFIRM ROUTE HIT', ['id' => $id]);

    $user = User::find($id);
    if (!$user) {
        return redirect()->route('login')->with('error', 'User not found.');
    }

    if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        return redirect()->route('login')->with('error', 'Invalid verification link.');
    }

    if ($user->markEmailAsVerified()) {
        event(new \Illuminate\Auth\Events\Verified($user));
    }

    session()->forget('unverified_user_id');
    return redirect()->route('login')->with('success', 'Email verified successfully! You can now login.');
})->name('verification.verify');

Route::get('/email/verify', function () {
    Log::info('📧 OLD EMAIL/VERIFY REDIRECT');
    return redirect()->route('verification.notice');
});
