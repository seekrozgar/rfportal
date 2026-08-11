<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class EmailVerificationNotificationController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        Log::info('📧 RESEND VERIFICATION ATTEMPT', [
            'session_unverified_id' => session('unverified_user_id'),
            'is_logged_in' => auth()->check(),
        ]);

        // 1. Handle resend for unverified user from session
        if (session()->has('unverified_user_id')) {
            $user = User::find(session('unverified_user_id'));
            if ($user) {
                if ($user->hasVerifiedEmail()) {
                    session()->forget('unverified_user_id');
                    return redirect()->route('login')->with('success', 'Email already verified.');
                }

                Log::info('👤 SENDING EMAIL TO UNVERIFIED SESSION USER', ['user_id' => $user->id]);
                $user->sendEmailVerificationNotification();
                return back()->with('status', 'verification-link-sent');
            }
        }

        // 2. Handle fallback for logged in user
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->hasVerifiedEmail()) {
                return redirect()->route('login')->with('success', 'Email already verified.');
            }
            $user->sendEmailVerificationNotification();
            return back()->with('status', 'verification-link-sent');
        }

        Log::warning('⚠️ NO USER FOUND TO RESEND EMAIL');
        return redirect()->route('login')->with('error', 'Please try logging in again.');
    }
}
