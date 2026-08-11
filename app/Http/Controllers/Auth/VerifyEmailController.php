<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\User;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(Request $request): RedirectResponse
    {
        // ✅ Check if user is logged in
        if (auth()->check()) {
            $user = $request->user();
        } else {
            // ✅ Check session for unverified user ID
            $userId = session('unverified_user_id');
            if ($userId) {
                $user = User::find($userId);
            } else {
                return redirect()->route('login')
                    ->with('error', 'User not found. Please login again.');
            }
        }

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'User not found. Please login again.');
        }

        // ✅ Check if already verified
        if ($user->hasVerifiedEmail()) {
            session()->forget('unverified_user_id');
            return redirect()->route('login')
                ->with('success', 'Your email is already verified. Please login.');
        }

        // ✅ Verify the email
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        // ✅ Clear session
        session()->forget('unverified_user_id');

        // ✅ Redirect to login with success message
        return redirect()->route('login')
            ->with('success', 'Email verified successfully! You can now login.');
    }
}
