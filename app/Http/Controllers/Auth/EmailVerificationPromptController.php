<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        // ✅ Check if user is logged in
        if (!auth()->check()) {
            // ✅ If not logged in, redirect to login with message
            return redirect()->route('login')
                ->with('info', 'Please login first to verify your email.');
        }

        // ✅ If already verified, redirect to dashboard
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        // ✅ Show verification notice
        return view('auth.verify-email');
    }
}
