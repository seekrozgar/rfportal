<?php
// database/migrations/xxxx_xx_xx_create_site_settings_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();

            // ============================================================
            // ✅ 1. SITE INFORMATION
            // ============================================================
            $table->string('site_name')->default('Rozgar Finder');
            $table->string('site_tagline')->nullable();
            $table->string('site_logo')->nullable();
            $table->string('site_favicon')->nullable();
            $table->string('site_email')->nullable();
            $table->string('site_phone')->nullable();
            $table->text('site_address')->nullable();
            $table->string('site_whatsapp')->nullable();

            // ============================================================
            // ✅ 2. SOCIAL NETWORKS
            // ============================================================
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('instagram')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('youtube')->nullable();
            $table->string('tiktok')->nullable();
            $table->string('pinterest')->nullable();
            $table->string('snapchat')->nullable();
            $table->string('telegram')->nullable();
            $table->string('whatsapp_group')->nullable();

            // ============================================================
            // ✅ 3. SOCIAL MEDIA LOGIN
            // ============================================================
            $table->boolean('social_login_enabled')->default(false);
            $table->boolean('facebook_login')->default(false);
            $table->string('facebook_client_id')->nullable();
            $table->string('facebook_client_secret')->nullable();
            $table->boolean('google_login')->default(false);
            $table->string('google_client_id')->nullable();
            $table->string('google_client_secret')->nullable();
            $table->boolean('linkedin_login')->default(false);
            $table->string('linkedin_client_id')->nullable();
            $table->string('linkedin_client_secret')->nullable();
            $table->boolean('github_login')->default(false);
            $table->string('github_client_id')->nullable();
            $table->string('github_client_secret')->nullable();

            // ============================================================
            // ✅ 4. PAYMENT GATEWAYS
            // ============================================================
            $table->boolean('payment_enabled')->default(false);
            $table->boolean('easypaisa_enabled')->default(false);
            $table->string('easypaisa_merchant_id')->nullable();
            $table->string('easypaisa_api_key')->nullable();
            $table->string('easypaisa_api_secret')->nullable();
            $table->boolean('jazzcash_enabled')->default(false);
            $table->string('jazzcash_merchant_id')->nullable();
            $table->string('jazzcash_api_key')->nullable();
            $table->string('jazzcash_api_secret')->nullable();
            $table->boolean('paypal_enabled')->default(false);
            $table->string('paypal_client_id')->nullable();
            $table->string('paypal_client_secret')->nullable();
            $table->boolean('paypal_sandbox')->default(true);
            $table->boolean('stripe_enabled')->default(false);
            $table->string('stripe_publishable_key')->nullable();
            $table->string('stripe_secret_key')->nullable();

            // ============================================================
            // ✅ 5. CAPTCHA
            // ============================================================
            $table->boolean('captcha_enabled')->default(false);
            $table->string('captcha_site_key')->nullable();
            $table->string('captcha_secret_key')->nullable();
            $table->boolean('captcha_on_login')->default(false);
            $table->boolean('captcha_on_register')->default(false);
            $table->boolean('captcha_on_contact')->default(false);

            // ============================================================
            // ✅ 6. GOOGLE ANALYTICS
            // ============================================================
            $table->boolean('analytics_enabled')->default(false);
            $table->string('analytics_measurement_id')->nullable();
            $table->string('analytics_api_key')->nullable();
            $table->string('analytics_property_id')->nullable();
            $table->boolean('analytics_anonymize_ip')->default(true);

            // ============================================================
            // ✅ 7. GDPR COOKIE POLICY
            // ============================================================
            $table->boolean('cookie_consent_enabled')->default(true);
            $table->text('cookie_consent_message')->nullable();
            $table->text('cookie_policy_url')->nullable();
            $table->string('cookie_expiry_days')->default(365);

            // ============================================================
            // ✅ 8. MANAGE ADS
            // ============================================================
            $table->boolean('ads_enabled')->default(false);
            $table->text('header_ad_code')->nullable();
            $table->text('sidebar_ad_code')->nullable();
            $table->text('footer_ad_code')->nullable();
            $table->text('popup_ad_code')->nullable();
            $table->text('in_content_ad_code')->nullable();

            // ============================================================
            // ✅ 9. MAINTENANCE & FEATURES
            // ============================================================
            $table->boolean('maintenance_mode')->default(false);
            $table->text('maintenance_message')->nullable();
            $table->boolean('registration_enabled')->default(true);
            $table->boolean('comments_enabled')->default(true);
            $table->string('timezone')->default('Asia/Karachi');
            $table->string('date_format')->default('d-m-Y');
            $table->string('currency')->default('PKR');
            $table->string('currency_symbol')->default('Rs.');

            // ============================================================
            // ✅ 10. SEO & META
            // ============================================================
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('meta_author')->nullable();
            $table->string('meta_robots')->default('index, follow');

            // ============================================================
            // ✅ 11. EMAIL SETTINGS
            // ============================================================
            $table->string('mail_driver')->default('smtp');
            $table->string('mail_host')->nullable();
            $table->string('mail_port')->nullable();
            $table->string('mail_username')->nullable();
            $table->string('mail_password')->nullable();
            $table->string('mail_encryption')->nullable();
            $table->string('mail_from_address')->nullable();
            $table->string('mail_from_name')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('site_settings');
    }
};
