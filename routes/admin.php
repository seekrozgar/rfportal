<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\JobPostingController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\JobCategoryController;
use App\Http\Controllers\Admin\ScholarshipController;
use App\Http\Controllers\Admin\AdmissionController;
use App\Http\Controllers\Admin\ResultController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\FaqCategoryController;
use App\Http\Controllers\Admin\SeoController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\Location\CountryController;
use App\Http\Controllers\Admin\Location\StateController;
use App\Http\Controllers\Admin\Location\CityController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\CompanyVerificationController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\PasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.')
    ->group(function () {

        // ============================================================
        // 📊 DASHBOARD
        // ============================================================
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // ============================================================
        // 👤 USERS (SuperAdmin only)
        // ============================================================
        Route::prefix('users')
            ->name('users.')
            ->middleware(['superadmin'])
            ->group(function () {
                Route::get('/', [UserController::class, 'index'])->name('index');
                Route::get('/create', [UserController::class, 'create'])->name('create');
                Route::post('/', [UserController::class, 'store'])->name('store');
                Route::get('/profiles', [UserController::class, 'profiles'])->name('profiles');
                Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
                Route::put('/{user}', [UserController::class, 'update'])->name('update');
                Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');

                // AJAX Routes
                Route::post('/{user}/toggle-status-ajax', [UserController::class, 'toggleStatusAjax'])
                    ->name('toggle-status-ajax');
                Route::post('/{user}/mark-fraud-ajax', [UserController::class, 'markFraudAjax'])
                    ->name('mark-fraud-ajax');
                Route::delete('/{user}/delete-ajax', [UserController::class, 'destroyAjax'])
                    ->name('destroy-ajax');
                Route::post('/{user}/resend-verification-ajax', [UserController::class, 'resendVerificationAjax'])
                    ->name('resend-verification-ajax');

                // Admin Users
                Route::get('/admin-users', [UserController::class, 'adminUsers'])->name('admin-users');
                Route::delete('/admin-users/{admin}', [UserController::class, 'destroyAdmin'])
                    ->name('admin-users.destroy');

                // Reset Password
                Route::get('/{user}/reset-password', [UserController::class, 'resetPasswordForm'])
                    ->name('reset-password-form');
                Route::post('/{user}/reset-password', [UserController::class, 'resetPassword'])
                    ->name('reset-password');
                Route::post('/{user}/force-reset-password', [UserController::class, 'forceResetPassword'])
                    ->name('force-reset-password');
            });

        // ============================================================
        // 📁 JOB CATEGORIES
        // ============================================================
        Route::prefix('job-categories')
            ->name('job-categories.')
            ->group(function () {
                Route::get('/', [JobCategoryController::class, 'index'])->name('index');
                Route::get('/create', [JobCategoryController::class, 'create'])->name('create');
                Route::post('/', [JobCategoryController::class, 'store'])->name('store');
                Route::get('/{jobCategory}/edit', [JobCategoryController::class, 'edit'])->name('edit');
                Route::put('/{jobCategory}', [JobCategoryController::class, 'update'])->name('update');
                Route::delete('/{jobCategory}', [JobCategoryController::class, 'destroy'])->name('destroy');
                Route::post('/{jobCategory}/toggle', [JobCategoryController::class, 'toggleStatus'])->name('toggle');
                Route::post('/reorder', [JobCategoryController::class, 'reorder'])->name('reorder');
                Route::post('/bulk-delete', [JobCategoryController::class, 'bulkDelete'])->name('bulk-delete');
                Route::get('/api/categories', [JobCategoryController::class, 'getCategories'])->name('api.categories');
                Route::post('/{jobCategory}/remove-image', [JobCategoryController::class, 'removeImage'])
                    ->name('admin.job-categories.remove-image');
            });

        // ============================================================
        // 💼 JOB POSTINGS
        // ============================================================
        Route::prefix('job-postings')
            ->name('job-postings.')
            ->group(function () {
                Route::get('/', [JobPostingController::class, 'index'])->name('index');
                Route::get('/create', [JobPostingController::class, 'create'])->name('create');
                Route::post('/', [JobPostingController::class, 'store'])->name('store');
                Route::get('/{jobPosting}/edit', [JobPostingController::class, 'edit'])->name('edit');
                Route::put('/{jobPosting}', [JobPostingController::class, 'update'])->name('update');
                Route::delete('/{jobPosting}', [JobPostingController::class, 'destroy'])->name('destroy');
                Route::post('/{jobPosting}/toggle-status', [JobPostingController::class, 'toggleStatus'])
                    ->name('toggle-status');
                Route::post('/bulk-action', [JobPostingController::class, 'bulkAction'])->name('bulk-action');

                // Scraping Routes
                Route::get('/scrape', [JobPostingController::class, 'showScrapeForm'])->name('scrape.form');
                Route::post('/scrape', [JobPostingController::class, 'scrape'])->name('scrape');
                Route::post('test-connection', [JobPostingController::class, 'testConnection'])
                    ->name('test-connection');
            });

        // ============================================================
        // 🏢 COMPANIES
        // ============================================================
        Route::prefix('companies')
            ->name('companies.')
            ->group(function () {
                Route::get('/', [CompanyController::class, 'index'])->name('index');
                Route::get('/create', [CompanyController::class, 'create'])->name('create');
                Route::post('/', [CompanyController::class, 'store'])->name('store');
                Route::get('/{company}/edit', [CompanyController::class, 'edit'])->name('edit');
                Route::put('/{company}', [CompanyController::class, 'update'])->name('update');
                Route::delete('/{company}', [CompanyController::class, 'destroy'])->name('destroy');
                Route::get('/{company}', [CompanyController::class, 'show'])->name('show');

                // Verification Actions
                Route::post('/{company}/approve', [CompanyController::class, 'approve'])->name('approve');
                Route::post('/{company}/reject', [CompanyController::class, 'reject'])->name('reject');
                Route::post('/{company}/suspend', [CompanyController::class, 'suspend'])->name('suspend');
                Route::post('/{company}/restore', [CompanyController::class, 'restore'])->name('restore');
                Route::post('/{company}/block', [CompanyController::class, 'block'])->name('block');
                Route::post('/{company}/fraud', [CompanyController::class, 'markFraud'])->name('fraud');
                Route::post('/{company}/remove-fraud', [CompanyController::class, 'removeFraud'])->name('remove-fraud');
                Route::post('/{company}/unverify', [CompanyController::class, 'unverify'])->name('unverify');
            });

        // ============================================================
        // ✅ COMPANY VERIFICATION
        // ============================================================
        Route::get('/company-verifications', [CompanyVerificationController::class, 'index'])
            ->name('company-verifications.index');

        Route::get('/company-verifications/{company}', [CompanyVerificationController::class, 'show'])
            ->name('company-verifications.show');

        Route::post('/company-verifications/{company}/approve', [CompanyVerificationController::class, 'approve'])
            ->name('company-verifications.approve');

        Route::post('/company-verifications/{company}/reject', [CompanyVerificationController::class, 'reject'])
            ->name('company-verifications.reject');

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
                Route::post('/{scholarship}/toggle', [ScholarshipController::class, 'toggleStatus'])->name('toggle');

                // Scraping
                Route::get('/scrape', [ScholarshipController::class, 'showScrapeForm'])->name('scrape.form');
                Route::post('/scrape', [ScholarshipController::class, 'scrape'])->name('scrape');
                Route::get('/scrape-all', [ScholarshipController::class, 'scrapeAll'])->name('scrape.all');

                // Import/Export
                Route::get('/export', [ScholarshipController::class, 'export'])->name('export');
                Route::post('/import', [ScholarshipController::class, 'import'])->name('import');
                Route::get('/download-template', [ScholarshipController::class, 'downloadTemplate'])->name('template');
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
                Route::post('/{admission}/toggle', [AdmissionController::class, 'toggleStatus'])->name('toggle');
            });

        // ============================================================
        // 📊 RESULTS
        // ============================================================
        Route::prefix('results')
            ->name('results.')
            ->group(function () {
                Route::get('/', [ResultController::class, 'index'])->name('index');
                Route::get('/create', [ResultController::class, 'create'])->name('create');
                Route::post('/', [ResultController::class, 'store'])->name('store');
                Route::get('/{result}/edit', [ResultController::class, 'edit'])->name('edit');
                Route::put('/{result}', [ResultController::class, 'update'])->name('update');
                Route::delete('/{result}', [ResultController::class, 'destroy'])->name('destroy');
                Route::post('/{result}/toggle', [ResultController::class, 'toggleStatus'])->name('toggle');
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
                Route::post('/{news}/toggle', [NewsController::class, 'toggleStatus'])->name('toggle');
            });

        // ============================================================
        // 🔍 SEO
        // ============================================================
        Route::prefix('seo')
            ->name('seo.')
            ->group(function () {
                Route::get('/', [SeoController::class, 'index'])->name('index');
                Route::post('/update', [SeoController::class, 'update'])->name('update');
            });

        // ============================================================
        // ❓ FAQS
        // ============================================================
        Route::prefix('faqs')
            ->name('faqs.')
            ->group(function () {
                Route::get('/', [FaqController::class, 'index'])->name('index');
                Route::get('/create', [FaqController::class, 'create'])->name('create');
                Route::post('/', [FaqController::class, 'store'])->name('store');
                Route::get('/{faq}/edit', [FaqController::class, 'edit'])->name('edit');
                Route::put('/{faq}', [FaqController::class, 'update'])->name('update');
                Route::delete('/{faq}', [FaqController::class, 'destroy'])->name('destroy');
                Route::post('/{faq}/toggle-status', [FaqController::class, 'toggleStatus'])->name('toggle-status');
                Route::post('/{faq}/toggle-featured', [FaqController::class, 'toggleFeatured'])->name('toggle-featured');
                Route::post('/reorder', [FaqController::class, 'reorder'])->name('reorder');
                Route::post('/bulk-action', [FaqController::class, 'bulkAction'])->name('bulk-action');
            });

        // ============================================================
        // 📂 FAQ CATEGORIES
        // ============================================================
        Route::prefix('faq-categories')
            ->name('faq-categories.')
            ->group(function () {
                Route::get('/', [FaqCategoryController::class, 'index'])->name('index');
                Route::get('/create', [FaqCategoryController::class, 'create'])->name('create');
                Route::post('/', [FaqCategoryController::class, 'store'])->name('store');
                Route::get('/{faqCategory}/edit', [FaqCategoryController::class, 'edit'])->name('edit');
                Route::put('/{faqCategory}', [FaqCategoryController::class, 'update'])->name('update');
                Route::delete('/{faqCategory}', [FaqCategoryController::class, 'destroy'])->name('destroy');
                Route::post('/{faqCategory}/toggle-status', [FaqCategoryController::class, 'toggleStatus'])
                    ->name('toggle-status');
                Route::post('/bulk-action', [FaqCategoryController::class, 'bulkAction'])->name('bulk-action');
            });

        // ============================================================
        // 🌐 LANGUAGES
        // ============================================================
        Route::prefix('languages')
            ->name('languages.')
            ->group(function () {
                Route::get('/', [LanguageController::class, 'index'])->name('index');
                Route::post('/', [LanguageController::class, 'store'])->name('store');
                Route::put('/{language}', [LanguageController::class, 'update'])->name('update');
                Route::delete('/{language}', [LanguageController::class, 'destroy'])->name('destroy');
            });

        // ============================================================
        // 📍 LOCATION (SuperAdmin only)
        // ============================================================
        Route::prefix('location')
            ->name('location.')
            ->middleware(['superadmin'])
            ->group(function () {

                // Countries
                Route::prefix('countries')->name('countries.')->group(function () {
                    Route::get('/', [CountryController::class, 'index'])->name('index');
                    Route::post('/', [CountryController::class, 'store'])->name('store');
                    Route::put('/{country}', [CountryController::class, 'update'])->name('update');
                    Route::delete('/{country}', [CountryController::class, 'destroy'])->name('destroy');
                    Route::post('/{country}/toggle', [CountryController::class, 'toggleStatus'])->name('toggle');
                });

                // States
                Route::prefix('states')->name('states.')->group(function () {
                    Route::get('/', [StateController::class, 'index'])->name('index');
                    Route::post('/', [StateController::class, 'store'])->name('store');
                    Route::put('/{state}', [StateController::class, 'update'])->name('update');
                    Route::delete('/{state}', [StateController::class, 'destroy'])->name('destroy');
                    Route::post('/{state}/toggle', [StateController::class, 'toggleStatus'])->name('toggle');
                });

                // Cities
                Route::prefix('cities')->name('cities.')->group(function () {
                    Route::get('/', [CityController::class, 'index'])->name('index');
                    Route::post('/', [CityController::class, 'store'])->name('store');
                    Route::put('/{city}', [CityController::class, 'update'])->name('update');
                    Route::delete('/{city}', [CityController::class, 'destroy'])->name('destroy');
                    Route::post('/{city}/toggle', [CityController::class, 'toggleStatus'])->name('toggle');
                });

                // Dropdowns
                Route::get('/states-by-country/{countryId}', [CityController::class, 'getStatesByCountry'])
                    ->name('states.by-country');
                Route::get('/state-info/{stateId}', [CityController::class, 'getStateInfo'])
                    ->name('state.info');
                Route::get('/cities-by-state/{stateId}', [CityController::class, 'getByState'])
                    ->name('cities.by-state');
            });

        // ============================================================
        // 📦 PACKAGES
        // ============================================================
        Route::prefix('packages')
            ->name('packages.')
            ->group(function () {
                Route::get('/', [PackageController::class, 'index'])->name('index');
                Route::post('/', [PackageController::class, 'store'])->name('store');
                Route::get('/{package}', [PackageController::class, 'show'])->name('show');
                Route::put('/{package}', [PackageController::class, 'update'])->name('update');
                Route::delete('/{package}', [PackageController::class, 'destroy'])->name('destroy');
                Route::post('/{package}/toggle', [PackageController::class, 'toggleStatus'])->name('toggle');
            });

        // ============================================================
        // 💳 PAYMENTS
        // ============================================================
        Route::prefix('payments')
            ->name('payments.')
            ->group(function () {
                Route::get('/company', [PaymentController::class, 'company'])->name('company');
                Route::get('/seeker', [PaymentController::class, 'seeker'])->name('seeker');
                Route::get('/{payment}', [PaymentController::class, 'show'])->name('show');
            });

        // ============================================================
        // 🏷️ JOB ATTRIBUTES (16 Types)
        // ============================================================
        Route::prefix('attributes')
            ->name('attributes.')
            ->group(function () {
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
                    Route::get('/' . $route, [AttributeController::class, $method])->name($route);

                    Route::post('/' . $route, function (Request $request) use ($route) {
                        $controller = app()->make(AttributeController::class);
                        return $controller->store($request, $route);
                    })->name($route . '.store');

                    Route::post('/' . $route . '/import', function (Request $request) use ($route) {
                        $controller = app()->make(AttributeController::class);
                        return $controller->import($request, $route);
                    })->name($route . '.import');

                    Route::put('/' . $route . '/{id}', function (Request $request, $id) use ($route) {
                        $controller = app()->make(AttributeController::class);
                        return $controller->update($request, $route, $id);
                    })->name($route . '.update');

                    Route::delete('/' . $route . '/{id}', function ($id) use ($route) {
                        $controller = app()->make(AttributeController::class);
                        return $controller->destroy($route, $id);
                    })->name($route . '.destroy');

                    Route::post('/' . $route . '/{id}/toggle', function ($id) use ($route) {
                        $controller = app()->make(AttributeController::class);
                        return $controller->toggleStatus($route, $id);
                    })->name($route . '.toggle');
                }
            });

        // ============================================================
        // ⚙️ SETTINGS
        // ============================================================
        Route::prefix('settings')
            ->name('settings.')
            ->group(function () {
                Route::get('/', [SiteSettingController::class, 'index'])->name('index');
                Route::put('/', [SiteSettingController::class, 'update'])->name('update');
                Route::post('/reset', [SiteSettingController::class, 'reset'])->name('reset');
                Route::post('/upload-logo', [SiteSettingController::class, 'uploadLogo'])->name('upload-logo');
                Route::post('/remove-logo', [SiteSettingController::class, 'removeLogo'])->name('remove-logo');
                Route::get('/get-setting', [SiteSettingController::class, 'getSetting'])->name('get-setting');
            });

        // ============================================================
        // 👤 PROFILE
        // ============================================================
        Route::prefix('profile')
            ->name('profile.')
            ->group(function () {
                Route::get('/', [ProfileController::class, 'index'])->name('index');
                Route::post('/update', [ProfileController::class, 'update'])->name('update');
            });

        // ============================================================
        // 🔑 CHANGE PASSWORD
        // ============================================================
        Route::prefix('change-password')
            ->name('change-password.')
            ->group(function () {
                Route::get('/', [PasswordController::class, 'index'])->name('index');
                Route::post('/update', [PasswordController::class, 'update'])->name('update');
            });

        // ============================================================
        // 🔔 NOTIFICATIONS
        // ============================================================
        Route::prefix('notifications')
            ->name('notifications.')
            ->group(function () {
                Route::get('/', [AdminNotificationController::class, 'index'])
                    ->name('index');
                Route::get('/latest', [AdminNotificationController::class, 'latest'])
                    ->name('latest');
                Route::post('/mark-read', [AdminNotificationController::class, 'markRead'])
                    ->name('mark-read');
                Route::post('/{id}/mark-read', [AdminNotificationController::class, 'markSingleRead'])
                    ->name('mark-single-read');
                Route::delete('/', [AdminNotificationController::class, 'destroyAll'])
                    ->name('destroy-all');
                Route::delete('/{id}', [AdminNotificationController::class, 'destroy'])
                    ->name('destroy');
            });
    });
