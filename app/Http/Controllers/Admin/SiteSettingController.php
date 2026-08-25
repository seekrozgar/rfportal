<?php
// app/Http/Controllers/Admin/SiteSettingController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;

class SiteSettingController extends Controller
{
    /**
     * ✅ Display settings page
     */
    public function index()
    {
        $settings = SiteSetting::first();

        if (!$settings) {
            $settings = SiteSetting::createDefault();
        }

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * ✅ Update settings with Toastr
     */
    public function update(Request $request)
    {
        try {
            $settings = SiteSetting::first() ?? new SiteSetting();

            $validated = $request->validate([
                // Site Information
                'site_name' => 'nullable|string|max:255',
                'site_tagline' => 'nullable|string|max:255',
                'site_email' => 'nullable|email|max:255',
                'site_phone' => 'nullable|string|max:255',
                'site_address' => 'nullable|string',
                'site_whatsapp' => 'nullable|string|max:255',

                // Social Networks
                'facebook' => 'nullable|url|max:255',
                'twitter' => 'nullable|url|max:255',
                'instagram' => 'nullable|url|max:255',
                'linkedin' => 'nullable|url|max:255',
                'youtube' => 'nullable|url|max:255',
                'tiktok' => 'nullable|url|max:255',
                'pinterest' => 'nullable|url|max:255',
                'snapchat' => 'nullable|url|max:255',
                'telegram' => 'nullable|url|max:255',
                'whatsapp_group' => 'nullable|url|max:255',

                // Social Login
                'social_login_enabled' => 'nullable|boolean',
                'facebook_login' => 'nullable|boolean',
                'facebook_client_id' => 'nullable|string|max:255',
                'facebook_client_secret' => 'nullable|string|max:255',
                'google_login' => 'nullable|boolean',
                'google_client_id' => 'nullable|string|max:255',
                'google_client_secret' => 'nullable|string|max:255',
                'linkedin_login' => 'nullable|boolean',
                'linkedin_client_id' => 'nullable|string|max:255',
                'linkedin_client_secret' => 'nullable|string|max:255',
                'github_login' => 'nullable|boolean',
                'github_client_id' => 'nullable|string|max:255',
                'github_client_secret' => 'nullable|string|max:255',

                // Payments
                'payment_enabled' => 'nullable|boolean',
                'easypaisa_enabled' => 'nullable|boolean',
                'easypaisa_merchant_id' => 'nullable|string|max:255',
                'easypaisa_api_key' => 'nullable|string|max:255',
                'easypaisa_api_secret' => 'nullable|string|max:255',
                'jazzcash_enabled' => 'nullable|boolean',
                'jazzcash_merchant_id' => 'nullable|string|max:255',
                'jazzcash_api_key' => 'nullable|string|max:255',
                'jazzcash_api_secret' => 'nullable|string|max:255',
                'paypal_enabled' => 'nullable|boolean',
                'paypal_client_id' => 'nullable|string|max:255',
                'paypal_client_secret' => 'nullable|string|max:255',
                'paypal_sandbox' => 'nullable|boolean',
                'stripe_enabled' => 'nullable|boolean',
                'stripe_publishable_key' => 'nullable|string|max:255',
                'stripe_secret_key' => 'nullable|string|max:255',

                // Captcha
                'captcha_enabled' => 'nullable|boolean',
                'captcha_site_key' => 'nullable|string|max:255',
                'captcha_secret_key' => 'nullable|string|max:255',
                'captcha_on_login' => 'nullable|boolean',
                'captcha_on_register' => 'nullable|boolean',
                'captcha_on_contact' => 'nullable|boolean',

                // Analytics
                'analytics_enabled' => 'nullable|boolean',
                'analytics_measurement_id' => 'nullable|string|max:255',
                'analytics_api_key' => 'nullable|string|max:255',
                'analytics_property_id' => 'nullable|string|max:255',
                'analytics_anonymize_ip' => 'nullable|boolean',

                // Cookie
                'cookie_consent_enabled' => 'nullable|boolean',
                'cookie_consent_message' => 'nullable|string',
                'cookie_policy_url' => 'nullable|url|max:255',
                'cookie_expiry_days' => 'nullable|integer|min:1|max:730',

                // Ads
                'ads_enabled' => 'nullable|boolean',
                'header_ad_code' => 'nullable|string',
                'sidebar_ad_code' => 'nullable|string',
                'footer_ad_code' => 'nullable|string',
                'popup_ad_code' => 'nullable|string',
                'in_content_ad_code' => 'nullable|string',

                // Features
                'maintenance_mode' => 'nullable|boolean',
                'maintenance_message' => 'nullable|string',
                'registration_enabled' => 'nullable|boolean',
                'comments_enabled' => 'nullable|boolean',
                'timezone' => 'nullable|string|max:255',
                'date_format' => 'nullable|string|max:255',
                'currency' => 'nullable|string|max:10',
                'currency_symbol' => 'nullable|string|max:10',

                // SEO
                'meta_title' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string|max:500',
                'meta_keywords' => 'nullable|string|max:255',
                'meta_author' => 'nullable|string|max:255',
                'meta_robots' => 'nullable|string|max:255',

                // Email
                'mail_driver' => 'nullable|string|max:50',
                'mail_host' => 'nullable|string|max:255',
                'mail_port' => 'nullable|string|max:10',
                'mail_username' => 'nullable|string|max:255',
                'mail_password' => 'nullable|string|max:255',
                'mail_encryption' => 'nullable|string|max:10',
                'mail_from_address' => 'nullable|email|max:255',
                'mail_from_name' => 'nullable|string|max:255',
            ]);

            // ✅ Handle boolean values
            $booleanFields = [
                'social_login_enabled', 'facebook_login', 'google_login', 'linkedin_login', 'github_login',
                'payment_enabled', 'easypaisa_enabled', 'jazzcash_enabled', 'paypal_enabled', 'paypal_sandbox', 'stripe_enabled',
                'captcha_enabled', 'captcha_on_login', 'captcha_on_register', 'captcha_on_contact',
                'analytics_enabled', 'analytics_anonymize_ip',
                'cookie_consent_enabled', 'ads_enabled',
                'maintenance_mode', 'registration_enabled', 'comments_enabled'
            ];

            foreach ($booleanFields as $field) {
                $validated[$field] = $request->has($field);
            }

            // ✅ Handle logo upload
            if ($request->hasFile('site_logo')) {
                if ($settings->site_logo) {
                    Storage::disk('public')->delete($settings->site_logo);
                }
                $path = $request->file('site_logo')->store('settings', 'public');
                $validated['site_logo'] = $path;
            }

            // ✅ Handle favicon upload
            if ($request->hasFile('site_favicon')) {
                if ($settings->site_favicon) {
                    Storage::disk('public')->delete($settings->site_favicon);
                }
                $path = $request->file('site_favicon')->store('settings', 'public');
                $validated['site_favicon'] = $path;
            }

            $settings->fill($validated);
            $settings->save();

            // ✅ Clear cache
            Cache::forget('site_settings_data');

            Log::info('✅ Site settings updated', ['by' => auth()->user()->name]);

            // ✅ Toastr success notification
            return redirect()->route('admin.settings.index')
                ->with('toast', [
                    'type' => 'success',
                    'message' => 'Site settings updated successfully!'
                ]);

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            Log::error('❌ Settings update failed', ['error' => $e->getMessage()]);

            // ✅ Toastr error notification
            return redirect()->back()
                ->withInput()
                ->with('toast', [
                    'type' => 'error',
                    'message' => 'Error: ' . $e->getMessage()
                ]);
        }
    }

    /**
     * ✅ Upload logo or favicon via AJAX
     */
    public function uploadLogo(Request $request)
    {
        try {
            $request->validate([
                'logo' => 'required|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048',
                'type' => 'required|in:logo,favicon'
            ]);

            $settings = SiteSetting::first() ?? new SiteSetting();

            $field = $request->type === 'logo' ? 'site_logo' : 'site_favicon';

            if ($settings->$field) {
                Storage::disk('public')->delete($settings->$field);
            }

            $path = $request->file('logo')->store('settings', 'public');
            $settings->$field = $path;
            $settings->save();

            Cache::forget('site_settings_data');

            return response()->json([
                'success' => true,
                'message' => 'Uploaded successfully!',
                'path' => asset('storage/' . $path)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * ✅ Remove logo or favicon via AJAX
     */
    public function removeLogo(Request $request)
    {
        try {
            $request->validate([
                'type' => 'required|in:logo,favicon'
            ]);

            $settings = SiteSetting::first();
            if (!$settings) {
                return response()->json(['success' => false, 'message' => 'Settings not found']);
            }

            $field = $request->type === 'logo' ? 'site_logo' : 'site_favicon';

            if ($settings->$field) {
                Storage::disk('public')->delete($settings->$field);
                $settings->$field = null;
                $settings->save();
                Cache::forget('site_settings_data');
            }

            return response()->json([
                'success' => true,
                'message' => 'Removed successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * ✅ Reset settings to default
     */
    public function reset(Request $request)
    {
        try {
            $settings = SiteSetting::first();
            if ($settings) {
                if ($settings->site_logo) {
                    Storage::disk('public')->delete($settings->site_logo);
                }
                if ($settings->site_favicon) {
                    Storage::disk('public')->delete($settings->site_favicon);
                }
                $settings->delete();
            }

            SiteSetting::createDefault();
            Cache::forget('site_settings_data');

            Log::info('✅ Site settings reset', ['by' => auth()->user()->name]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Settings reset to default successfully!'
                ]);
            }

            return redirect()->route('admin.settings.index')
                ->with('toast', [
                    'type' => 'success',
                    'message' => 'Settings reset to default successfully!'
                ]);

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 422);
            }
            return redirect()->back()
                ->with('toast', [
                    'type' => 'error',
                    'message' => 'Error: ' . $e->getMessage()
                ]);
        }
    }
}
