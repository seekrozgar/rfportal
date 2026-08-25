<?php
// app/Helpers/SiteHelper.php

namespace App\Helpers;

use App\Models\SiteSetting;

class SiteHelper
{
    /**
     * ✅ Get a setting value
     */
    public static function get($key, $default = null)
    {
        return SiteSetting::get($key, $default);
    }

    /**
     * ✅ Get site name
     */
    public static function siteName()
    {
        return self::get('site_name', 'Rozgar Finder');
    }

    /**
     * ✅ Get site tagline
     */
    public static function siteTagline()
    {
        return self::get('site_tagline', '');
    }

    /**
     * ✅ Get site logo URL
     */
    public static function siteLogo()
    {
        $logo = self::get('site_logo');
        return $logo ? asset('storage/' . $logo) : null;
    }

    /**
     * ✅ Get site favicon URL
     */
    public static function siteFavicon()
    {
        $favicon = self::get('site_favicon');
        return $favicon ? asset('storage/' . $favicon) : null;
    }

    /**
     * ✅ Get site email
     */
    public static function siteEmail()
    {
        return self::get('site_email', '');
    }

    /**
     * ✅ Get site phone
     */
    public static function sitePhone()
    {
        return self::get('site_phone', '');
    }

    /**
     * ✅ Get site address
     */
    public static function siteAddress()
    {
        return self::get('site_address', '');
    }

    /**
     * ✅ Get all social links
     */
    public static function socialLinks()
    {
        $settings = SiteSetting::getSettings();
        return $settings->getSocialLinks();
    }

    /**
     * ✅ Check if registration is enabled
     */
    public static function registrationEnabled()
    {
        return self::get('registration_enabled', true);
    }

    /**
     * ✅ Check if maintenance mode is active
     */
    public static function inMaintenance()
    {
        return self::get('maintenance_mode', false);
    }

    /**
     * ✅ Get maintenance message
     */
    public static function maintenanceMessage()
    {
        return self::get('maintenance_message', 'We are currently under maintenance. Please check back later.');
    }

    /**
     * ✅ Check if comments are enabled
     */
    public static function commentsEnabled()
    {
        return self::get('comments_enabled', true);
    }

    /**
     * ✅ Get meta title
     */
    public static function metaTitle()
    {
        return self::get('meta_title', self::siteName());
    }

    /**
     * ✅ Get meta description
     */
    public static function metaDescription()
    {
        return self::get('meta_description', '');
    }

    /**
     * ✅ Get meta keywords
     */
    public static function metaKeywords()
    {
        return self::get('meta_keywords', '');
    }

    /**
     * ✅ Get timezone
     */
    public static function timezone()
    {
        return self::get('timezone', 'Asia/Karachi');
    }

    /**
     * ✅ Get currency
     */
    public static function currency()
    {
        return self::get('currency', 'PKR');
    }

    /**
     * ✅ Get currency symbol
     */
    public static function currencySymbol()
    {
        return self::get('currency_symbol', 'Rs.');
    }

    /**
     * ✅ Get date format
     */
    public static function dateFormat()
    {
        return self::get('date_format', 'd-m-Y');
    }

    /**
     * ✅ Check if captcha is enabled
     */
    public static function captchaEnabled()
    {
        return self::get('captcha_enabled', false);
    }

    /**
     * ✅ Get captcha site key
     */
    public static function captchaSiteKey()
    {
        return self::get('captcha_site_key', '');
    }

    /**
     * ✅ Check if analytics is enabled
     */
    public static function analyticsEnabled()
    {
        return self::get('analytics_enabled', false);
    }

    /**
     * ✅ Get analytics measurement ID
     */
    public static function analyticsMeasurementId()
    {
        return self::get('analytics_measurement_id', '');
    }

    /**
     * ✅ Check if cookie consent is enabled
     */
    public static function cookieConsentEnabled()
    {
        return self::get('cookie_consent_enabled', true);
    }

    /**
     * ✅ Get cookie consent message
     */
    public static function cookieConsentMessage()
    {
        return self::get('cookie_consent_message', 'We use cookies to enhance your experience. By continuing to visit this site you agree to our use of cookies.');
    }

    /**
     * ✅ Check if ads are enabled
     */
    public static function adsEnabled()
    {
        return self::get('ads_enabled', false);
    }

    /**
     * ✅ Get header ad code
     */
    public static function headerAdCode()
    {
        return self::get('header_ad_code', '');
    }

    /**
     * ✅ Get sidebar ad code
     */
    public static function sidebarAdCode()
    {
        return self::get('sidebar_ad_code', '');
    }

    /**
     * ✅ Get footer ad code
     */
    public static function footerAdCode()
    {
        return self::get('footer_ad_code', '');
    }

    /**
     * ✅ Get copyright text
     */
    public static function copyrightText()
    {
        return '© ' . date('Y') . ' ' . self::siteName() . '. All rights reserved.';
    }

    /**
     * ✅ Format date according to settings
     */
    public static function formatDate($date)
    {
        $format = self::dateFormat();
        return $date->format($format);
    }
}
