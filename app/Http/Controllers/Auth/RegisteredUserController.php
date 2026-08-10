<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Company;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            // ✅ Log the request data (for debugging)
            Log::info('Registration attempt', $request->except('password'));

            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'role' => ['required', 'in:seeker,employer'], // ✅ Add role validation
                'company_name' => ['nullable', 'string', 'max:255', 'required_if:role,employer'],
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $validated['role'],
            ]);

            // ✅ Log success
            Log::info('User created successfully', ['user_id' => $user->id]);

            // ✅ Assign role using Spatie
            $user->assignRole($validated['role']);

            // ✅ If employer, create company profile
            if ($validated['role'] === 'employer' && !empty($validated['company_name'])) {
                $company = Company::create([
                    'user_id' => $user->id,
                    'company_name' => $validated['company_name'],
                    'slug' => Str::slug($validated['company_name']) . '-' . $user->id,
                    'is_active' => true,
                ]);

                // Update user's company_id
                $user->update(['company_id' => $company->id]);

                Log::info('Company created', ['company_id' => $company->id]);
            }


            // ✅ Email verification send karein
            $user->sendEmailVerificationNotification();

            // ✅ Fire Registered event
            event(new Registered($user));

            // // ✅ Store user ID in session for later use
            session()->put('registered_user_id', $user->id);

            // ❌ DON'T login user here (so they must verify email first)
            Auth::login($user);

            // ✅ Redirect to custom notice page (without auth middleware)
            return redirect()->route('verification.notice')->with('success', 'Registration successful! Please check your email to verify your account.');
        } catch (\Exception $e) {
            // ✅ Log the error
            Log::error('Registration failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            // ✅ Redirect back with error
            return back()
                ->withInput($request->except('password'))
                ->with('error', 'Registration failed: ' . $e->getMessage());
        }
    }
}
