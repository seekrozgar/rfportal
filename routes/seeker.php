<?php

use App\Http\Controllers\Seeker\DashboardController;
use App\Http\Controllers\Seeker\ProfileController;
use App\Http\Controllers\Seeker\ResumeController;
use App\Http\Controllers\Seeker\ApplicationController;
use App\Http\Controllers\Seeker\FavouriteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Seeker Routes
|--------------------------------------------------------------------------
*/

Route::prefix('seeker')
    ->middleware(['auth', 'verified', 'seeker'])
    ->name('seeker.')
    ->group(function () {

        // ============================================================
        // 📊 DASHBOARD
        // ============================================================
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // ============================================================
        // 👤 PROFILE
        // ============================================================
        Route::prefix('profile')
            ->name('profile.')
            ->group(function () {
                Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
                Route::put('/update', [ProfileController::class, 'update'])->name('update');
            });

        // ============================================================
        // 📄 RESUMES
        // ============================================================
        Route::prefix('resumes')
            ->name('resumes.')
            ->group(function () {
                Route::get('/', [ResumeController::class, 'index'])->name('index');
                Route::get('/create', [ResumeController::class, 'create'])->name('create');
                Route::post('/', [ResumeController::class, 'store'])->name('store');
                Route::get('/{resume}/edit', [ResumeController::class, 'edit'])->name('edit');
                Route::put('/{resume}', [ResumeController::class, 'update'])->name('update');
                Route::delete('/{resume}', [ResumeController::class, 'destroy'])->name('destroy');
            });

        // ============================================================
        // 📋 APPLICATIONS
        // ============================================================
        Route::prefix('applications')
            ->name('applications.')
            ->group(function () {
                Route::get('/', [ApplicationController::class, 'index'])->name('index');
                Route::post('/jobs/{job}/apply', [ApplicationController::class, 'store'])->name('store');
                Route::delete('/{application}', [ApplicationController::class, 'destroy'])->name('destroy');
            });

        // ============================================================
        // ⭐ FAVOURITES
        // ============================================================
        Route::prefix('favourites')
            ->name('favourites.')
            ->group(function () {
                Route::get('/', [FavouriteController::class, 'index'])->name('index');
                Route::post('/{job}', [FavouriteController::class, 'store'])->name('store');
                Route::delete('/{job}', [FavouriteController::class, 'destroy'])->name('destroy');
            });
    });
