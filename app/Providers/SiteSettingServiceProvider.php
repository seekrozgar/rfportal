<?php

namespace App\Providers;

use App\Helpers\SiteHelper;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class SiteSettingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('site.helper', function () {
            return new SiteHelper();
        });

        $this->app->singleton('site.settings', function () {
            return SiteSetting::getSettings();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->shareSettingsWithViews();
        $this->registerBladeDirectives();
    }

    /**
     * Share settings with all views.
     */
    protected function shareSettingsWithViews(): void
    {
        try {
            if (Schema::hasTable('site_settings')) {
                View::share('siteSettings', SiteSetting::getSettings());
            } else {
                View::share('siteSettings', null);
            }
        } catch (\Throwable $e) {
            View::share('siteSettings', null);
        }
    }

    /**
     * Register Blade directives.
     */
    protected function registerBladeDirectives(): void
    {
        Blade::directive('siteName', function () {
            return "<?php echo e(siteName()); ?>";
        });

        Blade::directive('siteLogo', function () {
            return "<?php echo e(siteLogo()); ?>";
        });

        Blade::directive('siteFavicon', function () {
            return "<?php echo e(siteFavicon()); ?>";
        });

        Blade::directive('siteEmail', function () {
            return "<?php echo e(siteEmail()); ?>";
        });

        Blade::directive('sitePhone', function () {
            return "<?php echo e(sitePhone()); ?>";
        });

        Blade::directive('siteAddress', function () {
            return "<?php echo e(siteAddress()); ?>";
        });

        Blade::directive('siteWhatsapp', function () {
            return "<?php echo e(siteWhatsapp()); ?>";
        });

        Blade::directive('currencySymbol', function () {
            return "<?php echo e(currencySymbol()); ?>";
        });

        Blade::directive('metaTitle', function () {
            return "<?php echo e(metaTitle()); ?>";
        });

        Blade::directive('metaDescription', function () {
            return "<?php echo e(metaDescription()); ?>";
        });

        Blade::directive('metaKeywords', function () {
            return "<?php echo e(metaKeywords()); ?>";
        });

        Blade::directive('metaAuthor', function () {
            return "<?php echo e(metaAuthor()); ?>";
        });

        Blade::directive('metaRobots', function () {
            return "<?php echo e(metaRobots()); ?>";
        });

        Blade::directive('copyright', function () {
            return "<?php echo e(copyrightText()); ?>";
        });

        Blade::directive('socialLinks', function () {
            return "<?php echo socialLinks(); ?>";
        });

        Blade::directive('isMaintenanceMode', function () {
            return "<?php echo (int) isMaintenanceMode(); ?>";
        });

        Blade::directive('isRegistrationEnabled', function () {
            return "<?php echo (int) isRegistrationEnabled(); ?>";
        });

        Blade::directive('isCaptchaEnabled', function () {
            return "<?php echo (int) isCaptchaEnabled(); ?>";
        });

        Blade::directive('captchaSiteKey', function () {
            return "<?php echo e(captchaSiteKey()); ?>";
        });

        Blade::directive('isAnalyticsEnabled', function () {
            return "<?php echo (int) isAnalyticsEnabled(); ?>";
        });

        Blade::directive('analyticsMeasurementId', function () {
            return "<?php echo e(analyticsMeasurementId()); ?>";
        });

        Blade::directive('isCookieConsentEnabled', function () {
            return "<?php echo (int) isCookieConsentEnabled(); ?>";
        });

        Blade::directive('cookieConsentMessage', function () {
            return "<?php echo e(cookieConsentMessage()); ?>";
        });

        Blade::directive('isAdsEnabled', function () {
            return "<?php echo (int) isAdsEnabled(); ?>";
        });

        Blade::directive('isSocialLoginEnabled', function () {
            return "<?php echo (int) isSocialLoginEnabled(); ?>";
        });

        Blade::directive('isPaymentEnabled', function () {
            return "<?php echo (int) isPaymentEnabled(); ?>";
        });
    }
}
