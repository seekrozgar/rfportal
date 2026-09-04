<?php

use App\Http\Controllers\Author\DashboardController;
use App\Http\Controllers\Author\JobController;
use App\Http\Controllers\Author\NewsController;
use App\Http\Controllers\Author\ScholarshipController;
use App\Http\Controllers\Author\AdmissionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Author Routes
|--------------------------------------------------------------------------
*/

Route::prefix('author')
    ->middleware(['auth', 'verified', 'author'])
    ->name('author.')
    ->group(function () {

        // ============================================================
        // 📊 DASHBOARD
        // ============================================================
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // ============================================================
        // 💼 JOBS
        // ============================================================
        Route::prefix('jobs')
            ->name('jobs.')
            ->group(function () {
                Route::get('/', [JobController::class, 'index'])->name('index');
                Route::get('/create', [JobController::class, 'create'])->name('create');
                Route::post('/', [JobController::class, 'store'])->name('store');
                Route::get('/{job}/edit', [JobController::class, 'edit'])->name('edit');
                Route::put('/{job}', [JobController::class, 'update'])->name('update');
                Route::delete('/{job}', [JobController::class, 'destroy'])->name('destroy');
            });

        // ============================================================
        // 📰 NEWS
        // ============================================================
        Route::prefix('news')
            ->name('news.')
            ->group(function () {
                Route::get('/', [NewsController::class, 'index'])->name('index');
                Route::get('/create', [NewsController::class, 'create'])->name('create');
                Route::post('/', [NewsController::class, 'store'])->name('store');
                Route::get('/{news}/edit', [NewsController::class, 'edit'])->name('edit');
                Route::put('/{news}', [NewsController::class, 'update'])->name('update');
                Route::delete('/{news}', [NewsController::class, 'destroy'])->name('destroy');
            });

        // ============================================================
        // 🎓 SCHOLARSHIPS
        // ============================================================
        Route::prefix('scholarships')
            ->name('scholarships.')
            ->group(function () {
                Route::get('/', [ScholarshipController::class, 'index'])->name('index');
                Route::get('/create', [ScholarshipController::class, 'create'])->name('create');
                Route::post('/', [ScholarshipController::class, 'store'])->name('store');
                Route::get('/{scholarship}/edit', [ScholarshipController::class, 'edit'])->name('edit');
                Route::put('/{scholarship}', [ScholarshipController::class, 'update'])->name('update');
                Route::delete('/{scholarship}', [ScholarshipController::class, 'destroy'])->name('destroy');
            });

        // ============================================================
        // 🎓 ADMISSIONS
        // ============================================================
        Route::prefix('admissions')
            ->name('admissions.')
            ->group(function () {
                Route::get('/', [AdmissionController::class, 'index'])->name('index');
                Route::get('/create', [AdmissionController::class, 'create'])->name('create');
                Route::post('/', [AdmissionController::class, 'store'])->name('store');
                Route::get('/{admission}/edit', [AdmissionController::class, 'edit'])->name('edit');
                Route::put('/{admission}', [AdmissionController::class, 'update'])->name('update');
                Route::delete('/{admission}', [AdmissionController::class, 'destroy'])->name('destroy');
            });
    });
