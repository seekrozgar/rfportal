<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\Auth\VerifyEmailController; // ✅ Import this
use App\Http\Controllers\Auth\EmailVerificationNotificationController; // ✅ Import this
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

// ====================
// EMAIL VERIFICATION ROUTES
// ====================

// ====================
// PUBLIC VERIFICATION NOTICE (No auth required)
// ====================
Route::get('/verify-notice', [EmailVerificationPromptController::class, '__invoke'])
    ->name('verification.notice.public');

// ====================
// EMAIL VERIFICATION ROUTES (Auth required)
// ====================
Route::middleware('auth')->group(function () {
    Route::get('email/verify', [EmailVerificationPromptController::class, '__invoke'])
        ->name('verification.notice'); // ✅ This will work after login

    Route::get('email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
});

// ✅ Password Confirmation Route
Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])
    ->middleware('auth')
    ->name('password.confirm');

Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store'])
    ->middleware('auth');

// ====================
// DYNAMIC SOCIAL LOGIN ROUTES
// ====================
Route::get('/auth/{provider}/redirect', [SocialiteController::class, 'redirect'])->name('social.redirect');
Route::get('/auth/{provider}/callback', [SocialiteController::class, 'callback'])->name('social.callback');

// ====================
// FRONTEND ROUTES (Public)
// ====================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{slug}', [JobController::class, 'show'])->name('jobs.show');
Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
Route::get('/companies/{slug}', [CompanyController::class, 'show'])->name('companies.show');
Route::get('/about-us', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');

// ====================
// AUTH ROUTES (Breeze)
// ====================
require __DIR__ . '/auth.php';

// ====================
// ADMIN ROUTES GROUP (SuperAdmin + Admin + Author)
// ====================
Route::prefix('admin')->middleware(['auth', 'verified', 'admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Jobs Management
    Route::resource('jobs', AdminJobController::class)->except(['show']);
    Route::post('jobs/import', [AdminJobController::class, 'import'])->name('jobs.import');

    // Companies Management
    Route::resource('companies', AdminCompanyController::class)->except(['show']);

    // Users Management
    Route::resource('users', AdminUserController::class);

    // Scholarships
    Route::resource('scholarships', AdminScholarshipController::class)->except(['show']);

    // Admissions
    Route::resource('admissions', AdminAdmissionController::class)->except(['show']);

    // News
    Route::resource('news', AdminNewsController::class)->except(['show']);

    // FAQ
    Route::resource('faq', AdminFaqController::class)->except(['show']);

    // Settings (Only Superadmin & Admin)
    Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::put('/settings/{key}', [AdminSettingController::class, 'update'])->name('settings.update');
});

// ====================
// SUPER ADMIN ONLY ROUTES (User deletion, etc.)
// ====================
Route::prefix('admin')->middleware(['auth', 'verified', 'superadmin'])->name('admin.')->group(function () {
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    Route::delete('/admin-users/{admin}', [AdminUserController::class, 'destroyAdmin'])->name('admin-users.destroy');
});

// ====================
// AUTHOR ROUTES (Post jobs, news, scholarships, admissions)
// ====================
Route::prefix('author')->middleware(['auth', 'verified', 'author'])->name('author.')->group(function () {
    Route::get('/dashboard', [AuthorDashboardController::class, 'index'])->name('dashboard');
    Route::resource('jobs', AuthorJobController::class)->except(['show']);
    Route::resource('news', AuthorNewsController::class)->except(['show']);
    Route::resource('scholarships', AuthorScholarshipController::class)->except(['show']);
    Route::resource('admissions', AuthorAdmissionController::class)->except(['show']);
});

// ====================
// EMPLOYER ROUTES
// ====================
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

// ====================
// SEEKER ROUTES
// ====================
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

// ====================
// TEMPORARY LOGOUT ROUTE (For testing - remove in production)
// ====================
Route::get('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout.get');

