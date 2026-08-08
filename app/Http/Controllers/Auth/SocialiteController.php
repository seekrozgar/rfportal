<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    // Redirect to provider
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    // Callback from provider
    public function callback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();

            // Check if user exists
            $user = User::where('email', $socialUser->getEmail())->first();

            if (!$user) {
                // Create new user
                $user = User::create([
                    'name' => $socialUser->getName() ?? $socialUser->getNickname(),
                    'email' => $socialUser->getEmail(),
                    'password' => bcrypt(uniqid()), // Random password
                    'role' => 'seeker', // Default role
                    'email_verified_at' => now(), // Social users are verified
                ]);

                // Assign default role
                $user->assignRole('seeker');
            }

            // Safety check: Agar pehle se account bana tha aur unverified tha, to ab verify kar do
            if (!$user->hasVerifiedEmail()) {
                $user->markEmailAsVerified();
            }

            Auth::login($user);

            // Redirect based on role
            return $this->redirectBasedOnRole($user);

        } catch (\Exception $e) {

            return redirect('/login')->with('error', 'Something went wrong with ' . $provider);
        }
    }

    private function redirectBasedOnRole($user)
    {
        if ($user->hasRole('superadmin') || $user->hasRole('admin') || $user->hasRole('author')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('employer')) {
            return redirect()->route('employer.dashboard');
        } elseif ($user->hasRole('seeker')) {
            return redirect()->route('seeker.dashboard');
        }
        return redirect()->route('home');
    }
}
