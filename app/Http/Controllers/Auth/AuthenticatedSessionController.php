<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // ✅ Authenticate user
        $request->authenticate();

        // ✅ Regenerate session
        $request->session()->regenerate();

        // ✅ Get authenticated user
        $user = Auth::user();

        // ✅ Check if email is verified (skip for social login users)
        if (is_null($user->email_verified_at) && !$user->provider) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // ✅ Resend verification email
            $user->sendEmailVerificationNotification();

            return redirect()->route('login')
                ->with('error', 'Please verify your email address first. A new verification link has been sent to your email.');
        }

        // ✅ Role-based redirect
        return $this->redirectBasedOnRole($user);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Redirect user based on their role.
     */
    private function redirectBasedOnRole($user): RedirectResponse
    {
        // ✅ Super Admin & Admin & Author
        if ($user->hasRole('superadmin') || $user->hasRole('admin') || $user->hasRole('author')) {
            return redirect()->route('admin.dashboard');
        }

        // ✅ Employer
        if ($user->hasRole('employer')) {
            return redirect()->route('employer.dashboard');
        }

        // ✅ Seeker
        if ($user->hasRole('seeker')) {
            return redirect()->route('seeker.dashboard');
        }

        // ✅ Default fallback
        return redirect()->route('home');
    }

    /**
     * Show login form with custom view.
     * (Alternative to create() method)
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }
}
