<?php
// database/seeders/SiteSettingSeeder.php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run()
    {
        SiteSetting::updateOrCreate(
            ['id' => 1],
            [
                // Site Information
                'site_name' => 'Rozgar Finder',
                'site_tagline' => 'Find Your Dream Job',
                'site_email' => 'info@rozgarfinder.com',
                'site_phone' => '+92-300-1234567',
                'site_address' => '123, Main Street, Lahore, Pakistan',
                'site_whatsapp' => '+92-300-1234567',

                // Social Networks
                'facebook' => 'https://facebook.com/rozgarfinder',
                'twitter' => 'https://twitter.com/rozgarfinder',
                'instagram' => 'https://instagram.com/rozgarfinder',
                'linkedin' => 'https://linkedin.com/company/rozgarfinder',
                'youtube' => 'https://youtube.com/@rozgarfinder',
                'tiktok' => 'https://tiktok.com/@rozgarfinder',
                'pinterest' => 'https://pinterest.com/rozgarfinder',
                'telegram' => 'https://t.me/rozgarfinder',

                // Social Login
                'social_login_enabled' => false,
                'facebook_login' => false,
                'google_login' => false,
                'linkedin_login' => false,

                // Payments
                'payment_enabled' => false,
                'easypaisa_enabled' => false,
                'jazzcash_enabled' => false,
                'paypal_enabled' => false,
                'paypal_sandbox' => true,
                'stripe_enabled' => false,

                // Captcha
                'captcha_enabled' => false,
                'captcha_on_login' => false,
                'captcha_on_register' => false,
                'captcha_on_contact' => false,

                // Analytics
                'analytics_enabled' => false,
                'analytics_anonymize_ip' => true,

                // Cookie
                'cookie_consent_enabled' => true,
                'cookie_consent_message' => 'We use cookies to enhance your experience. By continuing to visit this site you agree to our use of cookies.',
                'cookie_expiry_days' => 365,

                // Ads
                'ads_enabled' => false,

                // Features
                'maintenance_mode' => false,
                'maintenance_message' => 'We are currently under maintenance. Please check back later.',
                'registration_enabled' => true,
                'comments_enabled' => true,
                'timezone' => 'Asia/Karachi',
                'date_format' => 'd-m-Y',
                'currency' => 'PKR',
                'currency_symbol' => 'Rs.',

                // SEO
                'meta_title' => 'Rozgar Finder - Find Your Dream Job',
                'meta_description' => 'Rozgar Finder is Pakistan\'s leading job portal. Find jobs, post jobs, and build your career.',
                'meta_keywords' => 'jobs, careers, employment, Pakistan jobs, Rozgar Finder',
                'meta_author' => 'Rozgar Finder',
                'meta_robots' => 'index, follow',

                // Email
                'mail_driver' => 'smtp',
                'mail_host' => 'smtp.gmail.com',
                'mail_port' => '587',
                'mail_encryption' => 'tls',
            ]
        );
    }
}
