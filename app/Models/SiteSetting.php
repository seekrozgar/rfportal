<?php
// app/Models/SiteSetting.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_name',
        'site_tagline',
        'site_logo',
        'site_favicon',
        'site_email',
        'site_phone',
        'site_address',
        'site_whatsapp',
        'facebook',
        'twitter',
        'instagram',
        'linkedin',
        'youtube',
        'tiktok',
        'pinterest',
        'snapchat',
        'telegram',
        'whatsapp_group',
        'social_login_enabled',
        'facebook_login',
        'facebook_client_id',
        'facebook_client_secret',
        'google_login',
        'google_client_id',
        'google_client_secret',
        'linkedin_login',
        'linkedin_client_id',
        'linkedin_client_secret',
        'github_login',
        'github_client_id',
        'github_client_secret',
        'payment_enabled',
        'easypaisa_enabled',
        'easypaisa_merchant_id',
        'easypaisa_api_key',
        'easypaisa_api_secret',
        'jazzcash_enabled',
        'jazzcash_merchant_id',
        'jazzcash_api_key',
        'jazzcash_api_secret',
        'paypal_enabled',
        'paypal_client_id',
        'paypal_client_secret',
        'paypal_sandbox',
        'stripe_enabled',
        'stripe_publishable_key',
        'stripe_secret_key',
        'captcha_enabled',
        'captcha_site_key',
        'captcha_secret_key',
        'captcha_on_login',
        'captcha_on_register',
        'captcha_on_contact',
        'analytics_enabled',
        'analytics_measurement_id',
        'analytics_api_key',
        'analytics_property_id',
        'analytics_anonymize_ip',
        'cookie_consent_enabled',
        'cookie_consent_message',
        'cookie_policy_url',
        'cookie_expiry_days',
        'ads_enabled',
        'header_ad_code',
        'sidebar_ad_code',
        'footer_ad_code',
        'popup_ad_code',
        'in_content_ad_code',
        'maintenance_mode',
        'maintenance_message',
        'registration_enabled',
        'comments_enabled',
        'timezone',
        'date_format',
        'currency',
        'currency_symbol',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'meta_author',
        'meta_robots',
        'mail_driver',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'mail_encryption',
        'mail_from_address',
        'mail_from_name',
    ];

    protected $casts = [
        'social_login_enabled' => 'boolean',
        'facebook_login' => 'boolean',
        'google_login' => 'boolean',
        'linkedin_login' => 'boolean',
        'github_login' => 'boolean',
        'payment_enabled' => 'boolean',
        'easypaisa_enabled' => 'boolean',
        'jazzcash_enabled' => 'boolean',
        'paypal_enabled' => 'boolean',
        'paypal_sandbox' => 'boolean',
        'stripe_enabled' => 'boolean',
        'captcha_enabled' => 'boolean',
        'captcha_on_login' => 'boolean',
        'captcha_on_register' => 'boolean',
        'captcha_on_contact' => 'boolean',
        'analytics_enabled' => 'boolean',
        'analytics_anonymize_ip' => 'boolean',
        'cookie_consent_enabled' => 'boolean',
        'ads_enabled' => 'boolean',
        'maintenance_mode' => 'boolean',
        'registration_enabled' => 'boolean',
        'comments_enabled' => 'boolean',
        'cookie_expiry_days' => 'integer',
    ];

    /**
     * Get site settings safely.
     *
     * Cache only plain attributes, never serialize the Eloquent model.
     */
    public static function getSettings(): self
    {
        $cached = Cache::get('site_settings_data');

        if (is_array($cached)) {
            $settings = new static();

            $settings->setRawAttributes($cached);
            $settings->exists = true;

            return $settings;
        }

        $settings = static::first();

        if (!$settings) {
            $settings = static::createDefault();
        }

        Cache::put(
            'site_settings_data',
            $settings->getAttributes(),
            now()->addHour()
        );

        return $settings;
    }

    /**
     * ✅ Create default settings
     */
    public static function createDefault(): self
    {
        $settings = static::create([
            'site_name' => 'Rozgar Finder',
            'site_tagline' => 'Find Your Dream Job',

            'registration_enabled' => true,
            'comments_enabled' => true,

            'cookie_consent_enabled' => true,
            'cookie_expiry_days' => 365,

            'timezone' => 'Asia/Karachi',
            'date_format' => 'd-m-Y',

            'currency' => 'PKR',
            'currency_symbol' => 'Rs.',

            'meta_robots' => 'index, follow',

            'mail_driver' => 'smtp',

            'analytics_anonymize_ip' => true,

            'paypal_sandbox' => true,
        ]);

        Cache::put(
            'site_settings_data',
            $settings->getAttributes(),
            now()->addHour()
        );

        return $settings;
    }

    /**
     * ✅ Laravel 13+ booted method
     */
    protected static function booted(): void
    {
        static::saved(function (): void {
            Cache::forget('site_settings_data');
        });

        static::deleted(function (): void {
            Cache::forget('site_settings_data');
        });
    }

    /**
     * ✅ Get a specific setting value
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $settings = static::getSettings();

        return $settings->getAttribute($key) ?? $default;
    }

    /**
     * ✅ Get logo URL
     */
    public function getLogoUrlAttribute(): ?string
    {
        return $this->site_logo ? asset('storage/' . $this->site_logo) : null;
    }

    /**
     * ✅ Get favicon URL
     */
    public function getFaviconUrlAttribute(): ?string
    {
        return $this->site_favicon ? asset('storage/' . $this->site_favicon) : null;
    }

    /**
     * ✅ Get all social links
     */
    public function getSocialLinksAttribute(): array
    {
        $socials = [];
        $fields = [
            'facebook',
            'twitter',
            'instagram',
            'linkedin',
            'youtube',
            'tiktok',
            'pinterest',
            'telegram',
            'whatsapp_group'
        ];

        foreach ($fields as $field) {
            if ($this->$field) {
                $socials[$field] = $this->$field;
            }
        }

        return $socials;
    }

    /**
     * ✅ Get enabled payment gateways
     */
    public function getEnabledPaymentsAttribute(): array
    {
        $payments = [];
        if ($this->easypaisa_enabled)
            $payments[] = 'easypaisa';
        if ($this->jazzcash_enabled)
            $payments[] = 'jazzcash';
        if ($this->paypal_enabled)
            $payments[] = 'paypal';
        if ($this->stripe_enabled)
            $payments[] = 'stripe';
        return $payments;
    }

    /**
     * ✅ Get enabled social logins
     */
    public function getEnabledSocialLoginsAttribute(): array
    {
        $logins = [];
        if ($this->facebook_login)
            $logins[] = 'facebook';
        if ($this->google_login)
            $logins[] = 'google';
        if ($this->linkedin_login)
            $logins[] = 'linkedin';
        if ($this->github_login)
            $logins[] = 'github';
        return $logins;
    }
}
