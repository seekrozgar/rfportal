<?php

// app/Helpers/helpers.php

use App\Helpers\SiteHelper;

if (!function_exists('__t')) {
    function __t($key, $replace = [], $locale = null)
    {
        $locale = $locale ?? app()->getLocale();

        // If language is English, return the key directly (no translation needed)
        if ($locale === 'en') {
            return $key;
        }

        try {
            // Check database translation
            $translation = \App\Models\Translation::where('key', $key)
                ->where('language_code', $locale)
                ->whereNull('group')
                ->first();

            if ($translation) {
                $text = $translation->value;
            } else {
                // Fallback to English
                $defaultTranslation = \App\Models\Translation::where('key', $key)
                    ->where('language_code', 'en')
                    ->whereNull('group')
                    ->first();

                $text = $defaultTranslation ? $defaultTranslation->value : $key;
            }

            // Replace placeholders
            foreach ($replace as $key => $value) {
                $text = str_replace(':' . $key, $value, $text);
            }

            return $text;
        } catch (\Exception $e) {
            return $key;
        }
    }
}

if (!function_exists('__lang')) {
    function __lang()
    {
        return app()->getLocale();
    }
}

// ============================================================
// ✅ SITE SETTINGS HELPERS
// ============================================================


if (!function_exists('siteName')) {
    function siteName(): string
    {
        return SiteHelper::siteName();
    }
}

if (!function_exists('siteTagline')) {
    function siteTagline(): string
    {
        return SiteHelper::siteTagline();
    }
}

if (!function_exists('siteLogo')) {
    function siteLogo(): string
    {
        return SiteHelper::siteLogo();
    }
}

if (!function_exists('siteFavicon')) {
    function siteFavicon(): string
    {
        return SiteHelper::siteFavicon();
    }
}

if (!function_exists('siteEmail')) {
    function siteEmail(): string
    {
        return SiteHelper::siteEmail();
    }
}

if (!function_exists('sitePhone')) {
    function sitePhone(): string
    {
        return SiteHelper::sitePhone();
    }
}

if (!function_exists('siteAddress')) {
    function siteAddress(): string
    {
        return SiteHelper::siteAddress();
    }
}

if (!function_exists('siteWhatsapp')) {
    function siteWhatsapp(): string
    {
        return SiteHelper::siteWhatsapp();
    }
}

if (!function_exists('currencySymbol')) {
    function currencySymbol(): string
    {
        return SiteHelper::currencySymbol();
    }
}

if (!function_exists('metaTitle')) {
    function metaTitle(): string
    {
        return SiteHelper::metaTitle();
    }
}

if (!function_exists('metaDescription')) {
    function metaDescription(): string
    {
        return SiteHelper::metaDescription();
    }
}

if (!function_exists('metaKeywords')) {
    function metaKeywords(): string
    {
        return SiteHelper::metaKeywords();
    }
}

if (!function_exists('metaAuthor')) {
    function metaAuthor(): string
    {
        return SiteHelper::metaAuthor();
    }
}

if (!function_exists('metaRobots')) {
    function metaRobots(): string
    {
        return SiteHelper::metaRobots();
    }
}

if (!function_exists('copyrightText')) {
    function copyrightText(): string
    {
        return SiteHelper::copyrightText();
    }
}

if (!function_exists('socialLinks')) {
    function socialLinks(): string
    {
        return SiteHelper::socialLinks();
    }
}

if (!function_exists('isMaintenanceMode')) {
    function isMaintenanceMode(): bool
    {
        return SiteHelper::isMaintenanceMode();
    }
}

if (!function_exists('isRegistrationEnabled')) {
    function isRegistrationEnabled(): bool
    {
        return SiteHelper::isRegistrationEnabled();
    }
}

if (!function_exists('isCaptchaEnabled')) {
    function isCaptchaEnabled(): bool
    {
        return SiteHelper::isCaptchaEnabled();
    }
}

if (!function_exists('captchaSiteKey')) {
    function captchaSiteKey(): string
    {
        return SiteHelper::captchaSiteKey();
    }
}

if (!function_exists('isAnalyticsEnabled')) {
    function isAnalyticsEnabled(): bool
    {
        return SiteHelper::isAnalyticsEnabled();
    }
}

if (!function_exists('analyticsMeasurementId')) {
    function analyticsMeasurementId(): string
    {
        return SiteHelper::analyticsMeasurementId();
    }
}

if (!function_exists('isCookieConsentEnabled')) {
    function isCookieConsentEnabled(): bool
    {
        return SiteHelper::isCookieConsentEnabled();
    }
}

if (!function_exists('cookieConsentMessage')) {
    function cookieConsentMessage(): string
    {
        return SiteHelper::cookieConsentMessage();
    }
}

if (!function_exists('isAdsEnabled')) {
    function isAdsEnabled(): bool
    {
        return SiteHelper::isAdsEnabled();
    }
}

if (!function_exists('isSocialLoginEnabled')) {
    function isSocialLoginEnabled(): bool
    {
        return SiteHelper::isSocialLoginEnabled();
    }
}

if (!function_exists('isPaymentEnabled')) {
    function isPaymentEnabled(): bool
    {
        return SiteHelper::isPaymentEnabled();
    }
}
