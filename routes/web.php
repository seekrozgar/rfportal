<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\JobController;
use App\Http\Controllers\Frontend\CompanyController;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\JobController as AdminJobController;
use App\Http\Controllers\Admin\CompanyController as AdminCompanyController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ScholarshipController as AdminScholarshipController;
use App\Http\Controllers\Admin\AdmissionController as AdminAdmissionController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\FaqController as AdminFaqController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Author\DashboardController as AuthorDashboardController;
use App\Http\Controllers\Author\JobController as AuthorJobController;
use App\Http\Controllers\Author\NewsController as AuthorNewsController;
use App\Http\Controllers\Author\ScholarshipController as AuthorScholarshipController;
use App\Http\Controllers\Author\AdmissionController as AuthorAdmissionController;
use App\Http\Controllers\Employer\DashboardController as EmployerDashboardController;
use App\Http\Controllers\Employer\JobController as EmployerJobController;
use App\Http\Controllers\Employer\ApplicationController as EmployerApplicationController;
use App\Http\Controllers\Employer\ProfileController as EmployerProfileController;
use App\Http\Controllers\Employer\PackageController as EmployerPackageController;
use App\Http\Controllers\Seeker\DashboardController as SeekerDashboardController;
use App\Http\Controllers\Seeker\ProfileController as SeekerProfileController;
use App\Http\Controllers\Seeker\ResumeController as SeekerResumeController;
use App\Http\Controllers\Seeker\ApplicationController as SeekerApplicationController;
use App\Http\Controllers\Seeker\FavouriteController as SeekerFavouriteController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;

// ============================================================
// 🔥 VERIFICATION ROUTES - CLOSURE BASED (NO CONTROLLERS)
// ============================================================

// ✅ Main verification page (accessible without auth)
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

// ✅ Resend verification email
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

// ✅ Verify email confirmation
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

// ✅ Redirect old email/verify to new route
Route::get('/email/verify', function () {
    Log::info('📧 OLD EMAIL/VERIFY REDIRECT');
    return redirect()->route('verification.notice');
});

// ============================================================
// 🔥 DEBUG ROUTE
// ============================================================
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

// ============================================================
// PASSWORD CONFIRMATION
// ============================================================
Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])
    ->middleware('auth')
    ->name('password.confirm');

Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store'])
    ->middleware('auth');

// ============================================================
// SOCIAL LOGIN
// ============================================================
Route::get('/auth/{provider}/redirect', [SocialiteController::class, 'redirect'])->name('social.redirect');
Route::get('/auth/{provider}/callback', [SocialiteController::class, 'callback'])->name('social.callback');

// ============================================================
// FRONTEND
// ============================================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{slug}', [JobController::class, 'show'])->name('jobs.show');
Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
Route::get('/companies/{slug}', [CompanyController::class, 'show'])->name('companies.show');
Route::get('/about-us', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');

// ============================================================
// AUTH (Breeze)
// ============================================================
require __DIR__ . '/auth.php';

// ============================================================
// DASHBOARD
// ============================================================
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->hasRole('superadmin') || $user->hasRole('admin') || $user->hasRole('author')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('employer')) {
            return redirect()->route('employer.dashboard');
        } elseif ($user->hasRole('seeker')) {
            return redirect()->route('seeker.dashboard');
        }
        return redirect()->route('home');
    })->name('dashboard');
});

// ============================================================
// ADMIN
// ============================================================
Route::prefix('admin')->middleware(['auth', 'verified', 'admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('jobs', AdminJobController::class)->except(['show']);
    Route::post('jobs/import', [AdminJobController::class, 'import'])->name('jobs.import');
    Route::resource('companies', AdminCompanyController::class)->except(['show']);
    Route::resource('users', AdminUserController::class);
    Route::resource('scholarships', AdminScholarshipController::class)->except(['show']);
    Route::resource('admissions', AdminAdmissionController::class)->except(['show']);
    Route::resource('news', AdminNewsController::class)->except(['show']);
    Route::resource('faq', AdminFaqController::class)->except(['show']);
    Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::put('/settings/{key}', [AdminSettingController::class, 'update'])->name('settings.update');
});

// ============================================================
// SUPER ADMIN
// ============================================================
Route::prefix('admin')->middleware(['auth', 'verified', 'superadmin'])->name('admin.')->group(function () {
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    Route::delete('/admin-users/{admin}', [AdminUserController::class, 'destroyAdmin'])->name('admin-users.destroy');
});

// ============================================================
// AUTHOR
// ============================================================
Route::prefix('author')->middleware(['auth', 'verified', 'author'])->name('author.')->group(function () {
    Route::get('/dashboard', [AuthorDashboardController::class, 'index'])->name('dashboard');
    Route::resource('jobs', AuthorJobController::class)->except(['show']);
    Route::resource('news', AuthorNewsController::class)->except(['show']);
    Route::resource('scholarships', AuthorScholarshipController::class)->except(['show']);
    Route::resource('admissions', AuthorAdmissionController::class)->except(['show']);
});

// ============================================================
// EMPLOYER
// ============================================================
Route::prefix('employer')->middleware(['auth', 'verified', 'employer'])->name('employer.')->group(function () {
    Route::get('/dashboard', [EmployerDashboardController::class, 'index'])->name('dashboard');
    Route::resource('jobs', EmployerJobController::class);
    Route::get('/applications', [EmployerApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/{application}', [EmployerApplicationController::class, 'show'])->name('applications.show');
    Route::patch('/applications/{application}/status', [EmployerApplicationController::class, 'updateStatus'])->name('applications.status');
    Route::get('/profile', [EmployerProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [EmployerProfileController::class, 'update'])->name('profile.update');
    Route::get('/packages', [EmployerPackageController::class, 'index'])->name('packages.index');
    Route::post('/packages/subscribe', [EmployerPackageController::class, 'subscribe'])->name('packages.subscribe');
});

// ============================================================
// SEEKER
// ============================================================
Route::prefix('seeker')->middleware(['auth', 'verified', 'seeker'])->name('seeker.')->group(function () {
    Route::get('/dashboard', [SeekerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [SeekerProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [SeekerProfileController::class, 'update'])->name('profile.update');
    Route::resource('resumes', SeekerResumeController::class);
    Route::get('/applications', [SeekerApplicationController::class, 'index'])->name('applications.index');
    Route::post('/jobs/{job}/apply', [SeekerApplicationController::class, 'store'])->name('applications.store');
    Route::delete('/applications/{application}', [SeekerApplicationController::class, 'destroy'])->name('applications.destroy');
    Route::get('/favourites', [SeekerFavouriteController::class, 'index'])->name('favourites.index');
    Route::post('/favourites/{job}', [SeekerFavouriteController::class, 'store'])->name('favourites.store');
    Route::delete('/favourites/{job}', [SeekerFavouriteController::class, 'destroy'])->name('favourites.destroy');
});

// ============================================================
// LOGOUT
// ============================================================
Route::get('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout.get');
