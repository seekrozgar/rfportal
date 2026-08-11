<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class EmailVerificationPromptController extends Controller
{
    public function __invoke(Request $request): RedirectResponse|View
    {
        Log::info('📧 VERIFICATION PAGE ACCESSED', [
            'is_logged_in' => auth()->check(),
            'session_unverified_id' => session('unverified_user_id'),
        ]);

        // 1. Check session for unverified user (Logged-out state)
        if (session()->has('unverified_user_id')) {
            $userId = session('unverified_user_id');
            $user = User::find($userId);

            if ($user) {
                // Agar user email pehle hi verify kar chuka ho kisi aur tab me
                if ($user->hasVerifiedEmail()) {
                    session()->forget('unverified_user_id');
                    return redirect()->route('login')->with('success', 'Email already verified. Please login.');
                }

                Log::info('👤 UNVERIFIED GUEST USER FOUND', ['user_id' => $user->id]);
                return view('auth.verify-email', [
                    'unverifiedEmail' => $user->email,
                    'userId' => $user->id,
                ]);
            } else {
                session()->forget('unverified_user_id');
            }
        }

        // 2. Fallback: If user is somehow logged in
        if (auth()->check()) {
            if (auth()->user()->hasVerifiedEmail()) {
                return redirect()->route('home');
            }

            return view('auth.verify-email', [
                'unverifiedEmail' => auth()->user()->email,
                'userId' => auth()->id(),
            ]);
        }

        // 3. No session, no login -> Go back to login
        Log::warning('⚠️ NO SESSION OR AUTH FOUND - REDIRECT TO LOGIN');
        return redirect()->route('login')
            ->with('error', 'Please login to verify your email.');
    }
}
