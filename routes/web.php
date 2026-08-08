<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\SocialiteController;



// Email Verification Routes
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Dynamic Social Login Routes
Route::get('/auth/{provider}/redirect', [SocialiteController::class, 'redirect'])->name('social.redirect');
Route::get('/auth/{provider}/callback', [SocialiteController::class, 'callback'])->name('social.callback');

// Frontend Routes
Route::get('/', [App\Http\Controllers\Frontend\HomeController::class, 'index'])->name('home');
Route::get('/jobs', [App\Http\Controllers\Frontend\JobController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{slug}', [App\Http\Controllers\Frontend\JobController::class, 'show'])->name('jobs.show');
Route::get('/companies', [App\Http\Controllers\Frontend\CompanyController::class, 'index'])->name('companies.index');
Route::get('/companies/{slug}', [App\Http\Controllers\Frontend\CompanyController::class, 'show'])->name('companies.show');
Route::get('/about-us', [App\Http\Controllers\Frontend\PageController::class, 'about'])->name('about');
Route::get('/contact', [App\Http\Controllers\Frontend\PageController::class, 'contact'])->name('contact');

// Auth Routes (Breeze)
require __DIR__ . '/auth.php';

// Admin Routes Group
Route::prefix('admin')->middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');

    // Jobs
    Route::resource('jobs', App\Http\Controllers\Admin\JobController::class, ['as' => 'admin']);
    Route::post('jobs/import', [App\Http\Controllers\Admin\JobController::class, 'import'])->name('admin.jobs.import');

    // Companies
    Route::resource('companies', App\Http\Controllers\Admin\CompanyController::class, ['as' => 'admin']);

    // Users
    Route::resource('users', App\Http\Controllers\Admin\UserController::class, ['as' => 'admin']);

    // Scholarships
    Route::resource('scholarships', App\Http\Controllers\Admin\ScholarshipController::class, ['as' => 'admin']);

    // Admissions
    Route::resource('admissions', App\Http\Controllers\Admin\AdmissionController::class, ['as' => 'admin']);

    // News
    Route::resource('news', App\Http\Controllers\Admin\NewsController::class, ['as' => 'admin']);

    // FAQ
    Route::resource('faq', App\Http\Controllers\Admin\FaqController::class, ['as' => 'admin']);

    // Settings (Only Superadmin & Admin)
    Route::get('/settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('admin.settings.index');
    Route::put('/settings/{key}', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('admin.settings.update');
});

// Super Admin Only Routes (User deletion, etc.)
Route::prefix('admin')->middleware(['auth', 'verified', 'superadmin'])->group(function () {
    Route::delete('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('admin.users.destroy');
});

// Author Routes (Post jobs, news, scholarships, admissions)
Route::prefix('author')->middleware(['auth', 'verified', 'author'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Author\DashboardController::class, 'index'])->name('author.dashboard');
    Route::resource('jobs', App\Http\Controllers\Author\JobController::class, ['as' => 'author']);
    Route::resource('news', App\Http\Controllers\Author\NewsController::class, ['as' => 'author']);
    Route::resource('scholarships', App\Http\Controllers\Author\ScholarshipController::class, ['as' => 'author']);
    Route::resource('admissions', App\Http\Controllers\Author\AdmissionController::class, ['as' => 'author']);
});

// Employer Routes
Route::prefix('employer')->middleware(['auth', 'verified', 'employer'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Employer\DashboardController::class, 'index'])->name('employer.dashboard');
    Route::resource('jobs', App\Http\Controllers\Employer\JobController::class, ['as' => 'employer']);
    Route::get('/applications', [App\Http\Controllers\Employer\ApplicationController::class, 'index'])->name('employer.applications.index');
    Route::get('/profile', [App\Http\Controllers\Employer\ProfileController::class, 'edit'])->name('employer.profile.edit');
    Route::put('/profile', [App\Http\Controllers\Employer\ProfileController::class, 'update'])->name('employer.profile.update');
    Route::get('/packages', [App\Http\Controllers\Employer\PackageController::class, 'index'])->name('employer.packages.index');
    Route::post('/packages/subscribe', [App\Http\Controllers\Employer\PackageController::class, 'subscribe'])->name('employer.packages.subscribe');
});

// Seeker Routes
Route::prefix('seeker')->middleware(['auth', 'verified', 'seeker'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Seeker\DashboardController::class, 'index'])->name('seeker.dashboard');
    Route::get('/profile', [App\Http\Controllers\Seeker\ProfileController::class, 'edit'])->name('seeker.profile.edit');
    Route::put('/profile', [App\Http\Controllers\Seeker\ProfileController::class, 'update'])->name('seeker.profile.update');
    Route::resource('resumes', App\Http\Controllers\Seeker\ResumeController::class, ['as' => 'seeker']);
    Route::resource('applications', App\Http\Controllers\Seeker\ApplicationController::class, ['as' => 'seeker']);
    Route::get('/favourites', [App\Http\Controllers\Seeker\FavouriteController::class, 'index'])->name('seeker.favourites.index');
    Route::post('/favourites/{job}', [App\Http\Controllers\Seeker\FavouriteController::class, 'store'])->name('seeker.favourites.store');
    Route::delete('/favourites/{job}', [App\Http\Controllers\Seeker\FavouriteController::class, 'destroy'])->name('seeker.favourites.destroy');
});





// Temporary test route for direct logout via URL
Route::get('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
});
