<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\JobController;
use App\Http\Controllers\Frontend\CompanyController;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\JobPostingController as AdminJobPostingController;
use App\Http\Controllers\Admin\CompanyJobController as AdminCompanyJobController;
use App\Http\Controllers\Admin\CompanyController as AdminCompanyController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ScholarshipController as AdminScholarshipController;
use App\Http\Controllers\Admin\AdmissionController as AdminAdmissionController;
use App\Http\Controllers\Admin\ResultController as AdminResultController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\FaqController as AdminFaqController;
use App\Http\Controllers\Admin\SeoController as AdminSeoController;
use App\Http\Controllers\Admin\LanguageController as AdminLanguageController;
use App\Http\Controllers\Admin\CountryController as AdminCountryController;
use App\Http\Controllers\Admin\StateController as AdminStateController;
use App\Http\Controllers\Admin\CityController as AdminCityController;
use App\Http\Controllers\Admin\PackageController as AdminPackageController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\AttributeController as AdminAttributeController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\PasswordController;
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
// 🔑 PASSWORD RESET ROUTES (Laravel 13 Compatible)
// ============================================================

// ✅ Forgot Password
Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
    ->middleware('guest')
    ->name('password.request');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

// ✅ Reset Password
Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
    ->middleware('guest')
    ->name('password.reset');

// ✅ Update Password (PUT method - Laravel 13)
Route::put('/reset-password', [NewPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.update');


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
// ADMIN ROUTES (Complete - All Sidebar Links)
// ============================================================
Route::prefix('admin')->middleware(['auth', 'verified', 'admin'])->name('admin.')->group(function () {

    // ✅ Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // ✅ Jobs Management (General Jobs - PPSC/FPSC)
    Route::resource('jobs', AdminJobPostingController::class)->except(['show']);
    Route::post('jobs/import', [AdminJobPostingController::class, 'import'])->name('jobs.import');

    // ✅ Company Jobs Management
    Route::resource('company-jobs', AdminCompanyJobController::class)->except(['show']);

    // ✅ Companies Management
    Route::resource('companies', AdminCompanyController::class)->except(['show']);

    // ✅ Scholarships
    Route::resource('scholarships', AdminScholarshipController::class)->except(['show']);

    // ✅ Admissions
    Route::resource('admissions', AdminAdmissionController::class)->except(['show']);

    // ✅ Results
    Route::resource('results', AdminResultController::class)->except(['show']);

    // ✅ News / Announcements
    Route::resource('news', AdminNewsController::class)->except(['show']);

    // ✅ SEO
    Route::get('/seo', [AdminSeoController::class, 'index'])->name('seo.index');
    Route::post('/seo/update', [AdminSeoController::class, 'update'])->name('seo.update');

    // ✅ FAQ
    Route::resource('faq', AdminFaqController::class)->except(['show']);

    // ✅ Languages
    Route::get('/languages', [AdminLanguageController::class, 'index'])->name('languages.index');
    Route::post('/languages', [AdminLanguageController::class, 'store'])->name('languages.store');
    Route::put('/languages/{language}', [AdminLanguageController::class, 'update'])->name('languages.update');
    Route::delete('/languages/{language}', [AdminLanguageController::class, 'destroy'])->name('languages.destroy');

    // ✅ Locations (SuperAdmin only)
    Route::prefix('locations')->middleware(['superadmin'])->name('locations.')->group(function () {
        Route::resource('countries', AdminCountryController::class)->except(['show']);
        Route::resource('states', AdminStateController::class)->except(['show']);
        Route::resource('cities', AdminCityController::class)->except(['show']);
    });

    // ✅ Packages
    Route::resource('packages', AdminPackageController::class)->except(['show']);

    // ✅ Payments
    Route::get('/payments/company', [AdminPaymentController::class, 'companyPayments'])->name('payments.company');
    Route::get('/payments/seeker', [AdminPaymentController::class, 'seekerPayments'])->name('payments.seeker');
    Route::get('/payments/{payment}', [AdminPaymentController::class, 'show'])->name('payments.show');

    // ✅ Job Attributes
    Route::prefix('attributes')->name('attributes.')->group(function () {
        $attributeRoutes = [
            'language-levels' => 'languageLevels',
            'career-levels' => 'careerLevels',
            'functional-areas' => 'functionalAreas',
            'genders' => 'genders',
            'industries' => 'industries',
            'job-experience' => 'jobExperience',
            'job-skills' => 'jobSkills',
            'job-types' => 'jobTypes',
            'job-shifts' => 'jobShifts',
            'degree-levels' => 'degreeLevels',
            'degree-types' => 'degreeTypes',
            'major-subjects' => 'majorSubjects',
            'result-types' => 'resultTypes',
            'marital-status' => 'maritalStatus',
            'ownership-types' => 'ownershipTypes',
            'salary-periods' => 'salaryPeriods',
        ];

        foreach ($attributeRoutes as $route => $method) {
            Route::get('/' . $route, [AdminAttributeController::class, $method])->name($route);
        }
    });

    // ✅ Settings
    Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::post('/settings/update', [AdminSettingController::class, 'update'])->name('settings.update');

    // ✅ Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // ✅ Change Password
    Route::get('/change-password', [PasswordController::class, 'index'])->name('change-password');
    Route::post('/change-password', [PasswordController::class, 'update'])->name('change-password.update');

    // ✅ Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::post('/notifications/mark-read', [NotificationController::class, 'markRead'])->name('notifications.mark-read');
});

// ============================================================
// 🛡️ SUPER ADMIN ONLY ROUTES (Users Management - HYBRID)
// ============================================================
Route::prefix('admin')->middleware(['auth', 'verified', 'superadmin'])->name('admin.')->group(function () {

    // ✅ Traditional Routes (Page Reload)
    Route::resource('users', AdminUserController::class);
    Route::get('/user-profiles', [AdminUserController::class, 'profiles'])->name('users.profiles');
    Route::post('/users/{user}/resend-verification', [AdminUserController::class, 'resendVerification'])->name('users.resend-verification');

    // ✅ AJAX Routes (No Page Reload)
    Route::post('/users/{user}/toggle-status-ajax', [AdminUserController::class, 'toggleStatusAjax'])->name('users.toggle-status-ajax');
    Route::post('/users/{user}/mark-fraud-ajax', [AdminUserController::class, 'markFraudAjax'])->name('users.mark-fraud-ajax');
    Route::delete('/users/{user}/delete-ajax', [AdminUserController::class, 'destroyAjax'])->name('users.destroy-ajax');
    Route::post('/users/{user}/resend-verification-ajax', [AdminUserController::class, 'resendVerificationAjax'])->name('users.resend-verification-ajax');

    // ✅ Delete User (SuperAdmin only)
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    Route::delete('/admin-users/{admin}', [AdminUserController::class, 'destroyAdmin'])->name('admin-users.destroy');

    // ✅ Reset user password (SuperAdmin only)
    Route::get('/users/{user}/reset-password', [AdminUserController::class, 'resetPasswordForm'])->name('users.reset-password-form');
    Route::post('/users/{user}/reset-password', [AdminUserController::class, 'resetPassword'])->name('users.reset-password');
    Route::post('/users/{user}/force-reset-password', [AdminUserController::class, 'forceResetPassword'])->name('users.force-reset-password');
});


// ============================================================
// AUTHOR ROUTES
// ============================================================
Route::prefix('author')->middleware(['auth', 'verified', 'author'])->name('author.')->group(function () {
    Route::get('/dashboard', [AuthorDashboardController::class, 'index'])->name('dashboard');
    Route::resource('jobs', AuthorJobController::class)->except(['show']);
    Route::resource('news', AuthorNewsController::class)->except(['show']);
    Route::resource('scholarships', AuthorScholarshipController::class)->except(['show']);
    Route::resource('admissions', AuthorAdmissionController::class)->except(['show']);
});

// ============================================================
// EMPLOYER ROUTES
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
// SEEKER ROUTES
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


// ============================================================
// JOB ATTRIBUTES ROUTES
// ============================================================

// Route::prefix('attributes')->name('attributes.')->group(function () {
//     $attributes = [
//         'language-levels',
//         'career-levels',
//         'functional-areas',
//         'genders',
//         'industries',
//         'job-experience',
//         'job-skills',
//         'job-types',
//         'job-shifts',
//         'degree-levels',
//         'degree-types',
//         'major-subjects',
//         'result-types',
//         'marital-status',
//         'ownership-types',
//         'salary-periods',
//     ];

//     foreach ($attributes as $attr) {
//         Route::get('/' . $attr, [AdminAttributeController::class, 'index'])->name($attr);
//         Route::post('/' . $attr, [AdminAttributeController::class, 'store'])->name($attr . '.store');
//         Route::put('/' . $attr . '/{id}', [AdminAttributeController::class, 'update'])->name($attr . '.update');
//         Route::delete('/' . $attr . '/{id}', [AdminAttributeController::class, 'destroy'])->name($attr . '.destroy');
//         Route::post('/' . $attr . '/{id}/toggle', [AdminAttributeController::class, 'toggleStatus'])->name($attr . '.toggle');
//     }
// });
