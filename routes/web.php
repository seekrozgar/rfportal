<?php

use Illuminate\Support\Facades\Route;

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
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
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
Route::prefix('admin')->middleware(['auth', 'superadmin'])->group(function () {
    Route::delete('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('admin.users.destroy');
});

// Author Routes (Post jobs, news, scholarships, admissions)
Route::prefix('author')->middleware(['auth', 'author'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Author\DashboardController::class, 'index'])->name('author.dashboard');
    Route::resource('jobs', App\Http\Controllers\Author\JobController::class, ['as' => 'author']);
    Route::resource('news', App\Http\Controllers\Author\NewsController::class, ['as' => 'author']);
    Route::resource('scholarships', App\Http\Controllers\Author\ScholarshipController::class, ['as' => 'author']);
    Route::resource('admissions', App\Http\Controllers\Author\AdmissionController::class, ['as' => 'author']);
});

// Employer Routes
Route::prefix('employer')->middleware(['auth', 'employer'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Employer\DashboardController::class, 'index'])->name('employer.dashboard');
    Route::resource('jobs', App\Http\Controllers\Employer\JobController::class, ['as' => 'employer']);
    Route::get('/applications', [App\Http\Controllers\Employer\ApplicationController::class, 'index'])->name('employer.applications.index');
    Route::get('/profile', [App\Http\Controllers\Employer\ProfileController::class, 'edit'])->name('employer.profile.edit');
    Route::put('/profile', [App\Http\Controllers\Employer\ProfileController::class, 'update'])->name('employer.profile.update');
    Route::get('/packages', [App\Http\Controllers\Employer\PackageController::class, 'index'])->name('employer.packages.index');
    Route::post('/packages/subscribe', [App\Http\Controllers\Employer\PackageController::class, 'subscribe'])->name('employer.packages.subscribe');
});

// Seeker Routes
Route::prefix('seeker')->middleware(['auth', 'seeker'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Seeker\DashboardController::class, 'index'])->name('seeker.dashboard');
    Route::get('/profile', [App\Http\Controllers\Seeker\ProfileController::class, 'edit'])->name('seeker.profile.edit');
    Route::put('/profile', [App\Http\Controllers\Seeker\ProfileController::class, 'update'])->name('seeker.profile.update');
    Route::resource('resumes', App\Http\Controllers\Seeker\ResumeController::class, ['as' => 'seeker']);
    Route::resource('applications', App\Http\Controllers\Seeker\ApplicationController::class, ['as' => 'seeker']);
    Route::get('/favourites', [App\Http\Controllers\Seeker\FavouriteController::class, 'index'])->name('seeker.favourites.index');
    Route::post('/favourites/{job}', [App\Http\Controllers\Seeker\FavouriteController::class, 'store'])->name('seeker.favourites.store');
    Route::delete('/favourites/{job}', [App\Http\Controllers\Seeker\FavouriteController::class, 'destroy'])->name('seeker.favourites.destroy');
});
