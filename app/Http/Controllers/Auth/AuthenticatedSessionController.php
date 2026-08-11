<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
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
        Log::info('🔐 LOGIN ATTEMPT STARTED', ['email' => $request->email]);

        // 1. Extract exact inputs from the request array safely
        $email = $request->input('email');
        $password = $request->input('password');

        // 2. Find user by email first
        $user = User::where('email', $email)->first();

        // 3. Validate credentials manually using exact string variables
        if (!$user || !Hash::check($password, $user->password)) {
            Log::error('❌ AUTHENTICATION FAILED', ['email' => $email]);

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        Log::info('✅ CREDENTIALS VALIDATED SUCCESSFULLY', ['email' => $email]);

        // 4. 🔥 Check email verification BEFORE logging in
        if (is_null($user->email_verified_at) && !$user->provider) {
            Log::warning('⚠️ EMAIL NOT VERIFIED - BLOCKING LOGIN', ['user_id' => $user->id, 'email' => $user->email]);

            // Save user ID in session for your closure routes
            session()->put('unverified_user_id', $user->id);

            Log::info('➡️ REDIRECTING TO CLOSURE-BASED VERIFICATION ROUTE');
            return redirect()->route('verification.notice')
                ->with('error', 'Please verify your email address first. Check your inbox.');
        }

        // 5. ✅ If verified, login user officially
        Auth::login($user, $request->boolean('remember'));

        $request->session()->regenerate();
        Log::info('🔄 SESSION REGENERATED & USER LOGGED IN', ['user_id' => $user->id]);

        // Clear unverified session garbage if any
        session()->forget('unverified_user_id');

        // 6. ✅ Role-based redirect
        Log::info('🔍 CHECKING USER ROLES', ['user_id' => $user->id, 'role_column' => $user->role]);

        if ($user->hasRole('superadmin') || $user->hasRole('admin') || $user->hasRole('author')) {
            Log::info('➡️ REDIRECTING TO ADMIN DASHBOARD');
            return redirect()->route('admin.dashboard');
        }

        if ($user->hasRole('employer')) {
            Log::info('➡️ REDIRECTING TO EMPLOYER DASHBOARD');
            return redirect()->route('employer.dashboard');
        }

        if ($user->hasRole('seeker')) {
            Log::info('➡️ REDIRECTING TO SEEKER DASHBOARD');
            return redirect()->route('seeker.dashboard');
        }

        Log::warning('⚠️ NO ROLE FOUND - REDIRECT TO HOME');
        return redirect()->route('home')->with('warning', 'No role assigned. Please contact admin.');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Log::info('🚪 LOGOUT ATTEMPT', ['user_id' => Auth::id()]);

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Log::info('✅ LOGOUT SUCCESSFUL');
        return redirect('/');
    }
}
