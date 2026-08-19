<?php

namespace App\Helpers;

use App\Models\Language;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;

class LanguageHelper
{
    /**
     * Get current locale
     */
    public static function getCurrentLocale()
    {
        return Session::get('locale', config('app.locale', 'en'));
    }

    /**
     * Set locale
     */
    public static function setLocale($locale)
    {
        $availableLocales = ['en', 'ur', 'ar', 'fr', 'es', 'de', 'zh', 'hi'];

        if (!in_array($locale, $availableLocales)) {
            $locale = config('app.locale', 'en');
        }

        Session::put('locale', $locale);
        App::setLocale($locale);

        return $locale;
    }

    /**
     * Get all active languages as objects (WITHOUT CACHE TO AVOID CORRUPTION)
     */
    public static function getLanguages()
    {
        try {
            // ✅ Try to get from database directly (no cache)
            $languages = Language::where('is_active', true)
                ->orderBy('order')
                ->get();

            // ✅ If no languages in database, return default array
            if ($languages->isEmpty()) {
                return collect([
                    (object) ['code' => 'en', 'name' => 'English', 'native_name' => 'English', 'flag' => '🇬🇧', 'is_default' => true],
                    (object) ['code' => 'ur', 'name' => 'Urdu', 'native_name' => 'اردو', 'flag' => '🇵🇰', 'is_default' => false],
                    (object) ['code' => 'ar', 'name' => 'Arabic', 'native_name' => 'العربية', 'flag' => '🇸🇦', 'is_default' => false],
                ]);
            }

            return $languages;

        } catch (\Exception $e) {
            // ✅ Fallback if database fails
            return collect([
                (object) ['code' => 'en', 'name' => 'English', 'native_name' => 'English', 'flag' => '🇬🇧', 'is_default' => true],
                (object) ['code' => 'ur', 'name' => 'Urdu', 'native_name' => 'اردو', 'flag' => '🇵🇰', 'is_default' => false],
                (object) ['code' => 'ar', 'name' => 'Arabic', 'native_name' => 'العربية', 'flag' => '🇸🇦', 'is_default' => false],
            ]);
        }
    }

    /**
     * Get available locales (array of codes)
     */
    public static function getAvailableLocales()
    {
        try {
            return Language::where('is_active', true)
                ->orderBy('order')
                ->pluck('code')
                ->toArray();
        } catch (\Exception $e) {
            return ['en', 'ur', 'ar'];
        }
    }

    /**
     * Get flag emoji
     */
    public static function getFlag($code)
    {
        $flags = [
            'en' => '🇬🇧',
            'ur' => '🇵🇰',
            'ar' => '🇸🇦',
            'fr' => '🇫🇷',
            'es' => '🇪🇸',
            'de' => '🇩🇪',
            'zh' => '🇨🇳',
            'hi' => '🇮🇳',
        ];

        return $flags[$code] ?? '🌐';
    }

    /**
     * Get language name
     */
    public static function getLanguageName($code)
    {
        $names = [
            'en' => 'English',
            'ur' => 'Urdu',
            'ar' => 'Arabic',
            'fr' => 'French',
            'es' => 'Spanish',
            'de' => 'German',
            'zh' => 'Chinese',
            'hi' => 'Hindi',
        ];

        return $names[$code] ?? $code;
    }

    /**
     * Get native language name
     */
    public static function getNativeName($code)
    {
        $names = [
            'en' => 'English',
            'ur' => 'اردو',
            'ar' => 'العربية',
            'fr' => 'Français',
            'es' => 'Español',
            'de' => 'Deutsch',
            'zh' => '中文',
            'hi' => 'हिन्दी',
        ];

        return $names[$code] ?? $code;
    }
}
