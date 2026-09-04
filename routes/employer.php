<?php

use App\Http\Controllers\Employer\DashboardController;
use App\Http\Controllers\Employer\JobPostingController;
use App\Http\Controllers\Employer\ApplicationController;
use App\Http\Controllers\Employer\CompanyProfileController;
use App\Http\Controllers\Employer\PersonalProfileController;
use App\Http\Controllers\Employer\PackageController;
use App\Http\Controllers\Employer\NotificationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Employer Routes
|--------------------------------------------------------------------------
*/

Route::prefix('employer')
    ->middleware(['auth', 'verified', 'employer'])
    ->name('employer.')
    ->group(function () {

        // ============================================================
        // 📊 DASHBOARD
        // ============================================================
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // ============================================================
        // 👤 PERSONAL PROFILE
        // ============================================================
        Route::prefix('profile')
            ->name('profile.')
            ->group(function () {
                Route::get('/', [PersonalProfileController::class, 'edit'])->name('edit');
                Route::put('/update-info', [PersonalProfileController::class, 'updateInfo'])
                    ->name('update-info');
                Route::put('/update-password', [PersonalProfileController::class, 'updatePassword'])
                    ->name('update-password');
                Route::post('/avatar', [PersonalProfileController::class, 'uploadAvatar'])
                    ->name('avatar');
                Route::post('/remove-avatar', [PersonalProfileController::class, 'removeAvatar'])
                    ->name('remove-avatar');
            });

        // ============================================================
        // 🏢 COMPANY PROFILE
        // ============================================================
        Route::prefix('company-profile')
            ->name('company-profile.')
            ->group(function () {
                Route::get('/', [CompanyProfileController::class, 'index'])->name('index');
                Route::get('/edit', [CompanyProfileController::class, 'edit'])->name('edit');
                Route::put('/update', [CompanyProfileController::class, 'update'])->name('update');
                Route::post('/upload-image', [CompanyProfileController::class, 'uploadImage'])
                    ->name('upload');
                Route::post('/remove-image', [CompanyProfileController::class, 'removeImage'])
                    ->name('remove-image');
                Route::post('/verify', [CompanyProfileController::class, 'verify'])->name('verify');
                Route::get('/check-complete', [CompanyProfileController::class, 'checkProfileComplete'])
                    ->name('check-complete');
            });

        // ============================================================
        // 💼 JOBS
        // ============================================================
        Route::prefix('jobs')
            ->name('jobs.')
            ->group(function () {
                Route::get('/', [JobPostingController::class, 'index'])->name('index');
                Route::get('/create', [JobPostingController::class, 'create'])->name('create');
                Route::post('/', [JobPostingController::class, 'store'])->name('store');
                Route::get('/{job}/edit', [JobPostingController::class, 'edit'])->name('edit');
                Route::put('/{job}', [JobPostingController::class, 'update'])->name('update');
                Route::delete('/{job}', [JobPostingController::class, 'destroy'])->name('destroy');
            });

        // ============================================================
        // 📋 APPLICATIONS
        // ============================================================
        Route::prefix('applications')
            ->name('applications.')
            ->group(function () {
                Route::get('/', [ApplicationController::class, 'index'])->name('index');
                Route::get('/{application}', [ApplicationController::class, 'show'])->name('show');
                Route::patch('/{application}/status', [ApplicationController::class, 'updateStatus'])
                    ->name('status');
            });

        // ============================================================
        // 📦 PACKAGES
        // ============================================================
        Route::prefix('packages')
            ->name('packages.')
            ->group(function () {
                Route::get('/', [PackageController::class, 'index'])->name('index');
                Route::get('/{package}/buy', [PackageController::class, 'buy'])->name('buy');
                Route::post('/subscribe', [PackageController::class, 'subscribe'])->name('subscribe');
            });

        // ============================================================
        // 📋 SUBSCRIPTIONS
        // ============================================================
        Route::prefix('subscriptions')
            ->name('subscriptions.')
            ->group(function () {
                Route::get('/', [PackageController::class, 'subscriptions'])->name('index');
                Route::get('/active', [PackageController::class, 'activeSubscription'])->name('active');
            });

        // ============================================================
        // 🔔 NOTIFICATIONS
        // ============================================================
        Route::prefix('notifications')
            ->name('notifications.')
            ->group(function () {
                Route::get('/', [NotificationController::class, 'index'])
                    ->name('index');
                Route::get('/latest', [NotificationController::class, 'latest'])
                    ->name('latest');
                Route::post('/mark-read', [NotificationController::class, 'markRead'])
                    ->name('mark-read');
                Route::post('/{id}/mark-read', [NotificationController::class, 'markSingleRead'])
                    ->name('mark-single-read');
                Route::delete('/', [NotificationController::class, 'destroyAll'])
                    ->name('destroy-all');
                Route::delete('/{id}', [NotificationController::class, 'destroy'])
                    ->name('destroy');
            });
    });
