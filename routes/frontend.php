<?php

use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\JobController;
use App\Http\Controllers\Frontend\CompanyController;
use App\Http\Controllers\Frontend\PackagesController;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Frontend\ScholarshipController;
use App\Http\Controllers\Frontend\AdmissionController;
use App\Http\Controllers\Frontend\ResultController;
use App\Http\Controllers\Frontend\NewsController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Frontend / Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

// Jobs
Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{slug}', [JobController::class, 'show'])->name('jobs.show');

// Companies
Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
Route::get('/companies/{slug}', [CompanyController::class, 'show'])->name('companies.show');

// Packages
Route::get('/packages', [PackagesController::class, 'index'])->named('packages.index');

// Pages
Route::get('/about-us', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/privacy-policy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/terms-of-service', [PageController::class, 'terms'])->name('terms');


// ============================================================
// 🎓 EDUCATION ROUTES
// ============================================================

// Scholarships
Route::get('/scholarships', [ScholarshipController::class, 'index'])
    ->name('scholarships.index');
Route::get('/scholarships/{slug}', [ScholarshipController::class, 'show'])
    ->name('scholarships.show');

// Admissions
Route::get('/admissions', [AdmissionController::class, 'index'])
    ->name('admissions.index');
Route::get('/admissions/{slug}', [AdmissionController::class, 'show'])
    ->name('admissions.show');

// Results
Route::get('/results', [ResultController::class, 'index'])
    ->name('results.index');
Route::get('/results/{slug}', [ResultController::class, 'show'])
    ->name('results.show');

// News
Route::get('/news', [NewsController::class, 'index'])
    ->name('news.index');
Route::get('/news/{slug}', [NewsController::class, 'show'])
    ->name('news.show');
