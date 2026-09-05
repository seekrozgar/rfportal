<?php

namespace App\Helpers;

use App\Models\SiteSetting;

class SiteHelper
{
    /**
     * Get site settings.
     */
    public static function settings(): SiteSetting
    {
        return SiteSetting::getSettings();
    }

    /**
     * Get a setting value.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return self::settings()->getAttribute($key) ?? $default;
    }

    public static function siteName(): string
    {
        return (string) self::get('site_name', 'Rozgar Finder');
    }

    public static function siteTagline(): string
    {
        return (string) self::get('site_tagline', 'Find Your Dream Job');
    }

    public static function siteLogo(): string
    {
        $logo = self::settings()->site_logo;

        return $logo
            ? asset('storage/' . $logo)
            : '';
    }

    public static function siteFavicon(): string
    {
        $favicon = self::settings()->site_favicon;

        return $favicon
            ? asset('storage/' . $favicon)
            : '';
    }

    public static function siteEmail(): string
    {
        return (string) self::get('site_email', '');
    }

    public static function sitePhone(): string
    {
        return (string) self::get('site_phone', '');
    }

    public static function siteAddress(): string
    {
        return (string) self::get('site_address', '');
    }

    public static function siteWhatsapp(): string
    {
        return (string) self::get('site_whatsapp', '');
    }

    public static function currency(): string
    {
        return (string) self::get('currency', 'PKR');
    }

    public static function currencySymbol(): string
    {
        return (string) self::get('currency_symbol', 'Rs.');
    }

    public static function metaTitle(): string
    {
        return (string) self::get(
            'meta_title',
            self::siteName() . ' - ' . self::siteTagline()
        );
    }

    public static function metaDescription(): string
    {
        return (string) self::get('meta_description', '');
    }

    public static function metaKeywords(): string
    {
        return (string) self::get('meta_keywords', '');
    }

    public static function metaAuthor(): string
    {
        return (string) self::get('meta_author', self::siteName());
    }

    public static function metaRobots(): string
    {
        return (string) self::get('meta_robots', 'index, follow');
    }

    public static function copyrightText(): string
    {
        return '© ' . date('Y') . ' ' . self::siteName() . '. All rights reserved.';
    }

    public static function isMaintenanceMode(): bool
    {
        return (bool) self::get('maintenance_mode', false);
    }

    public static function isRegistrationEnabled(): bool
    {
        return (bool) self::get('registration_enabled', true);
    }

    public static function isCaptchaEnabled(): bool
    {
        return (bool) self::get('captcha_enabled', false);
    }

    public static function captchaSiteKey(): string
    {
        return (string) self::get('captcha_site_key', '');
    }

    public static function isAnalyticsEnabled(): bool
    {
        return (bool) self::get('analytics_enabled', false);
    }

    public static function analyticsMeasurementId(): string
    {
        return (string) self::get('analytics_measurement_id', '');
    }

    public static function isCookieConsentEnabled(): bool
    {
        return (bool) self::get('cookie_consent_enabled', true);
    }

    public static function cookieConsentMessage(): string
    {
        return (string) self::get(
            'cookie_consent_message',
            'We use cookies to enhance your experience.'
        );
    }

    public static function isAdsEnabled(): bool
    {
        return (bool) self::get('ads_enabled', false);
    }

    public static function isSocialLoginEnabled(): bool
    {
        return (bool) self::get('social_login_enabled', false);
    }

    public static function isPaymentEnabled(): bool
    {
        return (bool) self::get('payment_enabled', false);
    }

    public static function socialLinks(): string
    {
        $links = [];

        $socials = [
            'facebook',
            'twitter',
            'instagram',
            'linkedin',
            'youtube',
            'tiktok',
            'pinterest',
            'telegram',
            'whatsapp_group',
            'snapchat',
        ];

        foreach ($socials as $social) {
            $url = self::get($social);

            if (!empty($url)) {
                $links[$social] = $url;
            }
        }

        return json_encode($links);
    }

    }
