<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;

// ============================================================
// 🔥 AUTH CONTROLLERS
// ============================================================
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

// ============================================================
// 🏠 FRONTEND CONTROLLERS
// ============================================================
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\JobController;
use App\Http\Controllers\Frontend\CompanyController;
use App\Http\Controllers\Frontend\PageController;
use app\Http\Controllers\NotificationController;

// ============================================================
// 👑 ADMIN CONTROLLERS
// ============================================================
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\JobPostingController as AdminJobPostingController;
use App\Http\Controllers\Admin\CompanyController as AdminCompanyController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\JobCategoryController as AdminJobCategoryController;
use App\Http\Controllers\Admin\ScholarshipController as AdminScholarshipController;
use App\Http\Controllers\Admin\AdmissionController as AdminAdmissionController;
use App\Http\Controllers\Admin\ResultController as AdminResultController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\FaqController as AdminFaqController;
use App\Http\Controllers\Admin\FaqCategoryController as AdminFaqcategoryController;
use App\Http\Controllers\Admin\SeoController as AdminSeoController;
use App\Http\Controllers\Admin\LanguageController as AdminLanguageController;
use App\Http\Controllers\Admin\Location\CountryController as AdminCountryController;
use App\Http\Controllers\Admin\Location\StateController as AdminStateController;
use App\Http\Controllers\Admin\Location\CityController as AdminCityController;
use App\Http\Controllers\Admin\PackageController as AdminPackageController;
use App\Http\Controllers\Admin\AttributeController as AdminAttributeController;
use App\Http\Controllers\Admin\SiteSettingController as AdminSiteSettingController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\CompanyVerificationController as AdminCompanyVerificationController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\PasswordController;

// ============================================================
// ✍️ AUTHOR CONTROLLERS
// ============================================================
use App\Http\Controllers\Author\DashboardController as AuthorDashboardController;
use App\Http\Controllers\Author\JobController as AuthorJobController;
use App\Http\Controllers\Author\NewsController as AuthorNewsController;
use App\Http\Controllers\Author\ScholarshipController as AuthorScholarshipController;
use App\Http\Controllers\Author\AdmissionController as AuthorAdmissionController;

// ============================================================
// 🏢 EMPLOYER CONTROLLERS
// ============================================================
use App\Http\Controllers\Employer\DashboardController as EmployerDashboardController;
use App\Http\Controllers\Employer\JobPostingController as EmployerJobPostingController;
use App\Http\Controllers\Employer\ApplicationController as EmployerApplicationController;
use App\Http\Controllers\Employer\CompanyProfileController as EmployerProfileController;
use App\Http\Controllers\Employer\PersonalProfileController as EmployerPersonalProfileController;
use App\Http\Controllers\Employer\PackageController as EmployerPackageController;
use App\Http\Controllers\Employer\NotificationController as EmployerNotificationController;

// ============================================================
// 👤 SEEKER CONTROLLERS
// ============================================================
use App\Http\Controllers\Seeker\DashboardController as SeekerDashboardController;
use App\Http\Controllers\Seeker\ProfileController as EmployerSeekerProfileController;
use App\Http\Controllers\Seeker\ResumeController as SeekerResumeController;
use App\Http\Controllers\Seeker\ApplicationController as SeekerApplicationController;
use App\Http\Controllers\Seeker\FavouriteController as SeekerFavouriteController;

// ============================================================
// 💳 PAYMENT CONTROLLERS
// ============================================================
use App\Http\Controllers\PaymentController as FrontPaymentController;


// ============================================================
// 📧 EMAIL VERIFICATION ROUTES (CLOSURE BASED)
// ============================================================

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

Route::get('/email/verify', function () {
    Log::info('📧 OLD EMAIL/VERIFY REDIRECT');
    return redirect()->route('verification.notice');
});


// ============================================================
// 🔍 DEBUG ROUTE
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
// 🔑 PASSWORD RESET ROUTES
// ============================================================
Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
    ->middleware('guest')
    ->name('password.request');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
    ->middleware('guest')
    ->name('password.reset');

Route::put('/reset-password', [NewPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.update');


// ============================================================
// 🔐 PASSWORD CONFIRMATION
// ============================================================
Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])
    ->middleware('auth')
    ->name('password.confirm');

Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store'])
    ->middleware('auth');


// ============================================================
// 🐦 SOCIAL LOGIN
// ============================================================
Route::get('/auth/{provider}/redirect', [SocialiteController::class, 'redirect'])->name('social.redirect');
Route::get('/auth/{provider}/callback', [SocialiteController::class, 'callback'])->name('social.callback');


// ============================================================
// 🏠 FRONTEND ROUTES
// ============================================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{slug}', [JobController::class, 'show'])->name('jobs.show');
Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
Route::get('/companies/{slug}', [CompanyController::class, 'show'])->name('companies.show');
Route::get('/about-us', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');


// ============================================================
// 🔑 AUTH (Breeze)
// ============================================================
require __DIR__ . '/auth.php';


// ============================================================
// 📊 DASHBOARD
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

    Route::get('/notifications/latest', [NotificationController::class, 'latest'])->name('notifications.latest');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');

});


// ============================================================
// 👑 ADMIN ROUTES (Complete - All Sidebar Links)
// ============================================================
Route::prefix('admin')->middleware(['auth', 'verified', 'admin'])->name('admin.')->group(function () {

    // ✅ Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // ✅ Users Management (SuperAdmin only)
    Route::prefix('users')->name('users.')->middleware(['superadmin'])->group(function () {
        Route::get('/', [AdminUserController::class, 'index'])->name('index');
        Route::get('/create', [AdminUserController::class, 'create'])->name('create');
        Route::post('/', [AdminUserController::class, 'store'])->name('store');
        Route::get('/profiles', [AdminUserController::class, 'profiles'])->name('profiles');
        Route::get('/{user}/edit', [AdminUserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [AdminUserController::class, 'update'])->name('update');
        Route::delete('/{user}', [AdminUserController::class, 'destroy'])->name('destroy');

        // ✅ AJAX Routes
        Route::post('/{user}/toggle-status-ajax', [AdminUserController::class, 'toggleStatusAjax'])->name('toggle-status-ajax');
        Route::post('/{user}/mark-fraud-ajax', [AdminUserController::class, 'markFraudAjax'])->name('mark-fraud-ajax');
        Route::delete('/{user}/delete-ajax', [AdminUserController::class, 'destroyAjax'])->name('destroy-ajax');
        Route::post('/{user}/resend-verification-ajax', [AdminUserController::class, 'resendVerificationAjax'])->name('resend-verification-ajax');

        // ✅ Admin Users (SuperAdmin only)
        Route::get('/admin-users', [AdminUserController::class, 'adminUsers'])->name('admin-users');
        Route::delete('/admin-users/{admin}', [AdminUserController::class, 'destroyAdmin'])->name('admin-users.destroy');

        // ✅ Reset password (SuperAdmin only)
        Route::get('/{user}/reset-password', [AdminUserController::class, 'resetPasswordForm'])->name('reset-password-form');
        Route::post('/{user}/reset-password', [AdminUserController::class, 'resetPassword'])->name('reset-password');
        Route::post('/{user}/force-reset-password', [AdminUserController::class, 'forceResetPassword'])->name('force-reset-password');
    });

    Route::prefix('job-categories')->name('job-categories.')->group(function () {
        Route::get('/', [AdminJobCategoryController::class, 'index'])->name('index');
        Route::get('/create', [AdminJobCategoryController::class, 'create'])->name('create');
        Route::post('/', [AdminJobCategoryController::class, 'store'])->name('store');
        Route::get('/{jobCategory}/edit', [AdminJobCategoryController::class, 'edit'])->name('edit');
        Route::put('/{jobCategory}', [AdminJobCategoryController::class, 'update'])->name('update');
        Route::delete('/{jobCategory}', [AdminJobCategoryController::class, 'destroy'])->name('destroy');
        Route::post('/{jobCategory}/toggle', [AdminJobCategoryController::class, 'toggleStatus'])->name('toggle');
        Route::post('/reorder', [AdminJobCategoryController::class, 'reorder'])->name('reorder');
        Route::post('/bulk-delete', [AdminJobCategoryController::class, 'bulkDelete'])->name('bulk-delete');
        Route::get('/api/categories', [AdminJobCategoryController::class, 'getCategories'])->name('api.categories');
        Route::post('/{jobCategory}/remove-image', [AdminJobCategoryController::class, 'removeImage'])
            ->name('admin.job-categories.remove-image');
    });

    // General & Companies Jobs Posting Management
    Route::prefix('job-postings')->name('job-postings.')->group(function () {
        Route::get('/', [AdminJobPostingController::class, 'index'])->name('index');
        Route::get('/create', [AdminJobPostingController::class, 'create'])->name('create');
        Route::post('/', [AdminJobPostingController::class, 'store'])->name('store');
        Route::get('/{jobPosting}/edit', [AdminJobPostingController::class, 'edit'])->name('edit');
        Route::put('/{jobPosting}', [AdminJobPostingController::class, 'update'])->name('update');
        Route::delete('/{jobPosting}', [AdminJobPostingController::class, 'destroy'])->name('destroy');
        Route::post('/{jobPosting}/toggle-status', [AdminJobPostingController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/bulk-action', [AdminJobPostingController::class, 'bulkAction'])->name('bulk-action');


        // ✅ Scraping Routes
        Route::get('/scrape', [AdminJobPostingController::class, 'showScrapeForm'])->name('scrape.form');
        Route::post('/scrape', [AdminJobPostingController::class, 'scrape'])->name('scrape');
        Route::post('test-connection', [AdminJobPostingController::class, 'testConnection'])
            ->name('test-connection');
    });

    // ✅ Companies Management
    Route::prefix('companies')->name('companies.')->group(function () {
        Route::get('/', [AdminCompanyController::class, 'index'])->name('index');
        Route::get('/create', [AdminCompanyController::class, 'create'])->name('create');
        Route::post('/', [AdminCompanyController::class, 'store'])->name('store');
        Route::get('/{company}/edit', [AdminCompanyController::class, 'edit'])->name('edit');
        Route::put('/{company}', [AdminCompanyController::class, 'update'])->name('update');
        Route::delete('/{company}', [AdminCompanyController::class, 'destroy'])->name('destroy');
        // Routes of companies for status update
        Route::get('/{company}', [AdminCompanyController::class, 'show'])->name('show');
        Route::post('/{company}/approve', [AdminCompanyController::class, 'approve'])->name('approve');
        Route::post('/{company}/reject', [AdminCompanyController::class, 'reject'])->name('reject');
        Route::post('/{company}/warn', [AdminCompanyController::class, 'warn'])->name('warn');
        Route::post('/{company}/suspend', [AdminCompanyController::class, 'suspend'])->name('suspend');
        Route::post('/{company}/block', [AdminCompanyController::class, 'block'])->name('block');
        Route::post('/{company}/fraud', [AdminCompanyController::class, 'markFraud'])->name('fraud');

    });

    // ✅ Companies Approval Management
    Route::get('/company-verifications', [AdminCompanyVerificationController::class, 'index'])->name('company-verifications.index');
    Route::get('/company-verifications/{company}', [AdminCompanyVerificationController::class, 'show'])->name('company-verifications.show');
    Route::post('/company-verifications/{company}/approve', [AdminCompanyVerificationController::class, 'approve'])->name('company-verifications.approve');
    Route::post('/company-verifications/{company}/reject', [AdminCompanyVerificationController::class, 'reject'])->name('company-verifications.reject');

    // ✅ Education: Scholarships
    Route::prefix('scholarships')->name('scholarships.')->group(function () {
        // ✅ CRUD Routes
        Route::get('/', [AdminScholarshipController::class, 'index'])->name('index');
        Route::get('/create', [AdminScholarshipController::class, 'create'])->name('create');
        Route::post('/', [AdminScholarshipController::class, 'store'])->name('store');
        Route::get('/{scholarship}/edit', [AdminScholarshipController::class, 'edit'])->name('edit');
        Route::put('/{scholarship}', [AdminScholarshipController::class, 'update'])->name('update');
        Route::delete('/{scholarship}', [AdminScholarshipController::class, 'destroy'])->name('destroy');
        Route::post('/{scholarship}/toggle', [AdminScholarshipController::class, 'toggleStatus'])->name('toggle');

        // ✅ RSS Scraping Routes (FIXED)
        Route::get('/scrape', [AdminScholarshipController::class, 'showScrapeForm'])->name('scrape.form');
        Route::post('/scrape', [AdminScholarshipController::class, 'scrape'])->name('scrape');
        Route::get('/scrape-all', [AdminScholarshipController::class, 'scrapeAll'])->name('scrape.all');

        // ✅ Import/Export Routes
        Route::get('/export', [AdminScholarshipController::class, 'export'])->name('export');
        Route::post('/import', [AdminScholarshipController::class, 'import'])->name('import');
        Route::get('/download-template', [AdminScholarshipController::class, 'downloadTemplate'])->name('template');

    });

    // ✅ Education: Admissions
    Route::prefix('admissions')->name('admissions.')->group(function () {
        Route::get('/', [AdminAdmissionController::class, 'index'])->name('index');
        Route::get('/create', [AdminAdmissionController::class, 'create'])->name('create');
        Route::post('/', [AdminAdmissionController::class, 'store'])->name('store');
        Route::get('/{admission}/edit', [AdminAdmissionController::class, 'edit'])->name('edit');
        Route::put('/{admission}', [AdminAdmissionController::class, 'update'])->name('update');
        Route::delete('/{admission}', [AdminAdmissionController::class, 'destroy'])->name('destroy');
        Route::post('/{admission}/toggle', [AdminAdmissionController::class, 'toggleStatus'])->name('toggle');
    });

    // ✅ Education: Results
    Route::prefix('results')->name('results.')->group(function () {
        Route::get('/', [AdminResultController::class, 'index'])->name('index');
        Route::get('/create', [AdminResultController::class, 'create'])->name('create');
        Route::post('/', [AdminResultController::class, 'store'])->name('store');
        Route::get('/{result}/edit', [AdminResultController::class, 'edit'])->name('edit');
        Route::put('/{result}', [AdminResultController::class, 'update'])->name('update');
        Route::delete('/{result}', [AdminResultController::class, 'destroy'])->name('destroy');
        Route::post('/{result}/toggle', [AdminResultController::class, 'toggleStatus'])->name('toggle');
    });

    // ✅ News / Announcements
    Route::prefix('news')->name('news.')->group(function () {
        Route::get('/', [AdminNewsController::class, 'index'])->name('index');
        Route::get('/create', [AdminNewsController::class, 'create'])->name('create');
        Route::post('/', [AdminNewsController::class, 'store'])->name('store');
        Route::get('/{news}/edit', [AdminNewsController::class, 'edit'])->name('edit');
        Route::put('/{news}', [AdminNewsController::class, 'update'])->name('update');
        Route::delete('/{news}', [AdminNewsController::class, 'destroy'])->name('destroy');
        Route::post('/{news}/toggle', [AdminNewsController::class, 'toggleStatus'])->name('toggle');
    });

    // ✅ Content: SEO
    Route::prefix('seo')->name('seo.')->group(function () {
        Route::get('/', [AdminSeoController::class, 'index'])->name('index');
        Route::post('/update', [AdminSeoController::class, 'update'])->name('update');
    });

    // ✅ Content: FAQ
    Route::prefix('faqs')->name('faqs.')->group(function () {
        Route::get('/', [AdminFaqController::class, 'index'])->name('index');
        Route::get('/create', [AdminFaqController::class, 'create'])->name('create');
        Route::post('/', [AdminFaqController::class, 'store'])->name('store');
        Route::get('/{faq}/edit', [AdminFaqController::class, 'edit'])->name('edit');
        Route::put('/{faq}', [AdminFaqController::class, 'update'])->name('update');
        Route::delete('/{faq}', [AdminFaqController::class, 'destroy'])->name('destroy');
        Route::post('/{faq}/toggle-status', [AdminFaqController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/{faq}/toggle-featured', [AdminFaqController::class, 'toggleFeatured'])->name('toggle-featured');
        Route::post('/reorder', [AdminFaqController::class, 'reorder'])->name('reorder');
        Route::post('/bulk-action', [AdminFaqController::class, 'bulkAction'])->name('bulk-action');

    });
    // ✅ FAQ Categories Routes
    Route::prefix('faq-categories')->name('faq-categories.')->group(function () {
        Route::get('/', [AdminFaqCategoryController::class, 'index'])->name('index');
        Route::get('/create', [AdminFaqCategoryController::class, 'create'])->name('create');
        Route::post('/', [AdminFaqCategoryController::class, 'store'])->name('store');
        Route::get('/{faqCategory}/edit', [AdminFaqCategoryController::class, 'edit'])->name('edit');
        Route::put('/{faqCategory}', [AdminFaqCategoryController::class, 'update'])->name('update');
        Route::delete('/{faqCategory}', [AdminFaqCategoryController::class, 'destroy'])->name('destroy');
        Route::post('/{faqCategory}/toggle-status', [AdminFaqCategoryController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/bulk-action', [AdminFaqCategoryController::class, 'bulkAction'])->name('bulk-action');
    });

    // ✅ Translation: Languages
    Route::prefix('languages')->name('languages.')->group(function () {
        Route::get('/', [AdminLanguageController::class, 'index'])->name('index');
        Route::post('/', [AdminLanguageController::class, 'store'])->name('store');
        Route::put('/{language}', [AdminLanguageController::class, 'update'])->name('update');
        Route::delete('/{language}', [AdminLanguageController::class, 'destroy'])->name('destroy');
    });

    // ✅ Location (SuperAdmin only)
    Route::prefix('location')->name('location.')->middleware(['superadmin'])->group(function () {

        // Countries
        Route::prefix('countries')->name('countries.')->group(function () {
            Route::get('/', [AdminCountryController::class, 'index'])->name('index');
            Route::post('/', [AdminCountryController::class, 'store'])->name('store');
            Route::put('/{country}', [AdminCountryController::class, 'update'])->name('update');
            Route::delete('/{country}', [AdminCountryController::class, 'destroy'])->name('destroy');
            Route::post('/{country}/toggle', [AdminCountryController::class, 'toggleStatus'])->name('toggle');
        });

        // States
        Route::prefix('states')->name('states.')->group(function () {
            Route::get('/', [AdminStateController::class, 'index'])->name('index');
            Route::post('/', [AdminStateController::class, 'store'])->name('store');
            Route::put('/{state}', [AdminStateController::class, 'update'])->name('update');
            Route::delete('/{state}', [AdminStateController::class, 'destroy'])->name('destroy');
            Route::post('/{state}/toggle', [AdminStateController::class, 'toggleStatus'])->name('toggle');
        });

        // Cities
        Route::prefix('cities')->name('cities.')->group(function () {
            Route::get('/', [AdminCityController::class, 'index'])->name('index');
            Route::post('/', [AdminCityController::class, 'store'])->name('store');
            Route::put('/{city}', [AdminCityController::class, 'update'])->name('update');
            Route::delete('/{city}', [AdminCityController::class, 'destroy'])->name('destroy');
            Route::post('/{city}/toggle', [AdminCityController::class, 'toggleStatus'])->name('toggle');
        });

        // ✅ For dropdowns
        Route::get('/states-by-country/{countryId}', [AdminCityController::class, 'getStatesByCountry'])->name('states.by-country');
        Route::get('/state-info/{stateId}', [AdminCityController::class, 'getStateInfo'])->name('state.info');
        Route::get('/cities-by-state/{stateId}', [AdminCityController::class, 'getByState'])->name('cities.by-state');
    });

    // ✅ Packages
    Route::prefix('packages')->name('packages.')->group(function () {
        Route::get('/', [AdminPackageController::class, 'index'])->name('index');
        Route::post('/', [AdminPackageController::class, 'store'])->name('store');
        // ✅ IMPORTANT: GET route for fetching single package (View + Edit)
        Route::get('/{package}', [AdminPackageController::class, 'show'])->name('show');
        Route::put('/{package}', [AdminPackageController::class, 'update'])->name('update');
        Route::delete('/{package}', [AdminPackageController::class, 'destroy'])->name('destroy');
        Route::post('/{package}/toggle', [AdminPackageController::class, 'toggleStatus'])->name('toggle');
    });

    // ✅ Payments Routes
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/company', [AdminPaymentController::class, 'company'])->name('company');
        Route::get('/seeker', [AdminPaymentController::class, 'seeker'])->name('seeker');
        Route::get('/{payment}', [AdminPaymentController::class, 'show'])->name('show');
    });

    // ✅ Job Attributes (16 Types)
    Route::prefix('attributes')->name('attributes.')->group(function () {
        $attributes = [
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

        foreach ($attributes as $route => $method) {
            // ✅ Index
            Route::get('/' . $route, [AdminAttributeController::class, $method])->name($route);

            // ✅ Store
            Route::post('/' . $route, function (Request $request) use ($route) {
                $controller = app()->make(AdminAttributeController::class);
                return $controller->store($request, $route);
            })->name($route . '.store');

            // ✅ Import
            Route::post('/' . $route . '/import', function (Request $request) use ($route) {
                $controller = app()->make(AdminAttributeController::class);
                return $controller->import($request, $route);
            })->name($route . '.import');

            // ✅ Update
            Route::put('/' . $route . '/{id}', function (Request $request, $id) use ($route) {
                $controller = app()->make(AdminAttributeController::class);
                return $controller->update($request, $route, $id);
            })->name($route . '.update');

            // ✅ Delete
            Route::delete('/' . $route . '/{id}', function ($id) use ($route) {
                $controller = app()->make(AdminAttributeController::class);
                return $controller->destroy($route, $id);
            })->name($route . '.destroy');

            // ✅ Toggle Status
            Route::post('/' . $route . '/{id}/toggle', function ($id) use ($route) {
                $controller = app()->make(AdminAttributeController::class);
                return $controller->toggleStatus($route, $id);
            })->name($route . '.toggle');
        }
    });

    // ✅ System: Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [AdminSiteSettingController::class, 'index'])->name('index');
        Route::put('/', [AdminSiteSettingController::class, 'update'])->name('update');
        Route::post('/reset', [AdminSiteSettingController::class, 'reset'])->name('reset');
        Route::post('/upload-logo', [AdminSiteSettingController::class, 'uploadLogo'])->name('upload-logo');
        Route::post('/remove-logo', [AdminSiteSettingController::class, 'removeLogo'])->name('remove-logo');
        Route::get('/get-setting', [AdminSiteSettingController::class, 'getSetting'])->name('get-setting');
    });

    // ✅ System: Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::post('/update', [ProfileController::class, 'update'])->name('update');
    });

    // ✅ System: Change Password (Logged-in Admin)
    Route::prefix('change-password')->name('change-password.')->group(function () {
        Route::get('/', [PasswordController::class, 'index'])->name('index');
        Route::post('/update', [PasswordController::class, 'update'])->name('update');
    });

    // ✅ System: Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [AdminNotificationController::class, 'index'])->name('index');
        Route::post('/mark-read', [AdminNotificationController::class, 'markRead'])->name('mark-read');
        Route::post('/{id}/mark-read', [AdminNotificationController::class, 'markSingleRead'])->name('mark-single-read');
    });
});


// ============================================================
// ✍️ AUTHOR ROUTES
// ============================================================
Route::prefix('author')->middleware(['auth', 'verified', 'author'])->name('author.')->group(function () {
    Route::get('/dashboard', [AuthorDashboardController::class, 'index'])->name('dashboard');

    // Jobs
    Route::prefix('jobs')->name('jobs.')->group(function () {
        Route::get('/', [AuthorJobController::class, 'index'])->name('index');
        Route::get('/create', [AuthorJobController::class, 'create'])->name('create');
        Route::post('/', [AuthorJobController::class, 'store'])->name('store');
        Route::get('/{job}/edit', [AuthorJobController::class, 'edit'])->name('edit');
        Route::put('/{job}', [AuthorJobController::class, 'update'])->name('update');
        Route::delete('/{job}', [AuthorJobController::class, 'destroy'])->name('destroy');
    });

    // News
    Route::prefix('news')->name('news.')->group(function () {
        Route::get('/', [AuthorNewsController::class, 'index'])->name('index');
        Route::get('/create', [AuthorNewsController::class, 'create'])->name('create');
        Route::post('/', [AuthorNewsController::class, 'store'])->name('store');
        Route::get('/{news}/edit', [AuthorNewsController::class, 'edit'])->name('edit');
        Route::put('/{news}', [AuthorNewsController::class, 'update'])->name('update');
        Route::delete('/{news}', [AuthorNewsController::class, 'destroy'])->name('destroy');
    });

    // Scholarships
    Route::prefix('scholarships')->name('scholarships.')->group(function () {
        Route::get('/', [AuthorScholarshipController::class, 'index'])->name('index');
        Route::get('/create', [AuthorScholarshipController::class, 'create'])->name('create');
        Route::post('/', [AuthorScholarshipController::class, 'store'])->name('store');
        Route::get('/{scholarship}/edit', [AuthorScholarshipController::class, 'edit'])->name('edit');
        Route::put('/{scholarship}', [AuthorScholarshipController::class, 'update'])->name('update');
        Route::delete('/{scholarship}', [AuthorScholarshipController::class, 'destroy'])->name('destroy');
    });

    // Admissions
    Route::prefix('admissions')->name('admissions.')->group(function () {
        Route::get('/', [AuthorAdmissionController::class, 'index'])->name('index');
        Route::get('/create', [AuthorAdmissionController::class, 'create'])->name('create');
        Route::post('/', [AuthorAdmissionController::class, 'store'])->name('store');
        Route::get('/{admission}/edit', [AuthorAdmissionController::class, 'edit'])->name('edit');
        Route::put('/{admission}', [AuthorAdmissionController::class, 'update'])->name('update');
        Route::delete('/{admission}', [AuthorAdmissionController::class, 'destroy'])->name('destroy');
    });
});


// ============================================================
// 🏢 EMPLOYER ROUTES
// ============================================================
Route::prefix('employer')->middleware(['auth', 'verified', 'employer'])->name('employer.')->group(function () {

    Route::get('/dashboard', [EmployerDashboardController::class, 'index'])->name('dashboard');

    // Personal Profile Routes
    // ✅ User Profile (Personal)
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [EmployerPersonalProfileController::class, 'edit'])->name('edit');
        Route::put('/update-info', [EmployerPersonalProfileController::class, 'updateInfo'])->name('update-info');
        Route::put('/update-password', [EmployerPersonalProfileController::class, 'updatePassword'])->name('update-password');
        Route::post('/avatar', [EmployerPersonalProfileController::class, 'uploadAvatar'])->name('avatar');
        Route::post('/remove-avatar', [EmployerPersonalProfileController::class, 'removeAvatar'])->name('remove-avatar');

    });

    // Company Profile Routes
    Route::prefix('company-profile')->name('company-profile.')->group(function () {
        Route::get('/', [EmployerProfileController::class, 'index'])->name('index');
        Route::get('/edit', [EmployerProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [EmployerProfileController::class, 'update'])->name('update');

        // AJAX media routes
        Route::post('/upload-image', [EmployerProfileController::class, 'uploadImage'])->name('upload');
        Route::post('/remove-image', [EmployerProfileController::class, 'removeImage'])->name('remove-image');

        Route::post('/verify', [EmployerProfileController::class, 'verify'])->name('verify');
        Route::get('/check-complete', [EmployerProfileController::class, 'checkProfileComplete'])->name('check-complete');
    });


    // ✅ Job Routes (with profile check)
    Route::prefix('jobs')->name('jobs.')->group(function () {
        Route::get('/', [EmployerJobPostingController::class, 'index'])->name('index');
        Route::get('/create', [EmployerJobPostingController::class, 'create'])->name('create');
        Route::post('/', [EmployerJobPostingController::class, 'store'])->name('store');
        Route::get('/{job}/edit', [EmployerJobPostingController::class, 'edit'])->name('edit');
        Route::put('/{job}', [EmployerJobPostingController::class, 'update'])->name('update');
        Route::delete('/{job}', [EmployerJobPostingController::class, 'destroy'])->name('destroy');
    });

    // Applications
    Route::prefix('applications')->name('applications.')->group(function () {
        Route::get('/', [EmployerApplicationController::class, 'index'])->name('index');
        Route::get('/{application}', [EmployerApplicationController::class, 'show'])->name('show');
        Route::patch('/{application}/status', [EmployerApplicationController::class, 'updateStatus'])->name('status');
    });

    // Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/edit', [EmployerProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [EmployerProfileController::class, 'update'])->name('update');
    });

    // ============================================
    // ✅ PACKAGES ROUTES (Employer)
    // ============================================
    Route::prefix('packages')->name('packages.')->group(function () {
        Route::get('/', [EmployerPackageController::class, 'index'])->name('index');
        Route::get('/{package}/buy', [EmployerPackageController::class, 'buy'])->name('buy');  // ✅ ADD THIS
        Route::post('/subscribe', [EmployerPackageController::class, 'subscribe'])->name('subscribe');
    });

    // ============================================
    // ✅ SUBSCRIPTIONS ROUTES (Employer)
    // ============================================
    Route::prefix('subscriptions')->name('subscriptions.')->group(function () {
        Route::get('/', [EmployerPackageController::class, 'subscriptions'])->name('index');
        Route::get('/active', [EmployerPackageController::class, 'activeSubscription'])->name('active');
    });

    // ✅ Talent Search
    Route::prefix('talent')->name('talent.')->group(function () {
        Route::get('/search', [TalentController::class, 'search'])->name('search');
        Route::get('/candidate/{id}', [TalentController::class, 'view'])->name('view');
    });

    // ✅ Analytics
    Route::prefix('analytics')->name('analytics.')->group(function () {
        Route::get('/', [AnalyticsController::class, 'index'])->name('index');
        Route::get('/jobs', [AnalyticsController::class, 'jobs'])->name('jobs');
        Route::get('/applications', [AnalyticsController::class, 'applications'])->name('applications');
    });

    // ✅ Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        Route::post('/update', [SettingsController::class, 'update'])->name('update');
    });

    // Notifications
    Route::get('/notifications', [EmployerNotificationController::class, 'index'])->name('notifications.index');

});


// ============================================================
// 👤 SEEKER ROUTES
// ============================================================
Route::prefix('seeker')->middleware(['auth', 'verified', 'seeker'])->name('seeker.')->group(function () {
    Route::get('/dashboard', [SeekerDashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/edit', [SeekerProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [SeekerProfileController::class, 'update'])->name('update');
    });

    // Resumes
    Route::prefix('resumes')->name('resumes.')->group(function () {
        Route::get('/', [SeekerResumeController::class, 'index'])->name('index');
        Route::get('/create', [SeekerResumeController::class, 'create'])->name('create');
        Route::post('/', [SeekerResumeController::class, 'store'])->name('store');
        Route::get('/{resume}/edit', [SeekerResumeController::class, 'edit'])->name('edit');
        Route::put('/{resume}', [SeekerResumeController::class, 'update'])->name('update');
        Route::delete('/{resume}', [SeekerResumeController::class, 'destroy'])->name('destroy');
    });

    // Applications
    Route::prefix('applications')->name('applications.')->group(function () {
        Route::get('/', [SeekerApplicationController::class, 'index'])->name('index');
        Route::post('/jobs/{job}/apply', [SeekerApplicationController::class, 'store'])->name('store');
        Route::delete('/{application}', [SeekerApplicationController::class, 'destroy'])->name('destroy');
    });

    // Favourites
    Route::prefix('favourites')->name('favourites.')->group(function () {
        Route::get('/', [SeekerFavouriteController::class, 'index'])->name('index');
        Route::post('/{job}', [SeekerFavouriteController::class, 'store'])->name('store');
        Route::delete('/{job}', [SeekerFavouriteController::class, 'destroy'])->name('destroy');
    });
});


// ============================================================
// 💳 PAYMENT ROUTES (Common)
// ============================================================
Route::middleware(['auth'])->prefix('payment')->name('payment.')->group(function () {
    Route::post('/initiate', [FrontPaymentController::class, 'initiate'])->name('initiate');
    Route::get('/callback', [FrontPaymentController::class, 'callback'])->name('callback');
    Route::get('/success', [FrontPaymentController::class, 'success'])->name('success');
    Route::get('/failed', [FrontPaymentController::class, 'failed'])->name('failed');
});


// ============================================================
// 🚪 LOGOUT
// ============================================================
Route::get('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout.get');


// ============================================================
// 🌐 LANGUAGE SWITCH ROUTE
// ============================================================
Route::get('/language/switch/{locale}', function ($locale) {
    $availableLocales = ['en', 'ur', 'ar', 'fr', 'es', 'de', 'zh', 'hi'];

    if (in_array($locale, $availableLocales)) {
        session()->put('locale', $locale);
        app()->setLocale($locale);
    }

    return redirect()->back();
})->name('language.switch');
