<?php
// app/Helpers/LanguageHelper.php

namespace App\Helpers;

use App\Models\Language;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageHelper
{
    /**
     * ✅ Get current locale
     */
    public static function getCurrentLocale(): string
    {
        return Session::get('locale', config('app.locale', 'en'));
    }

    /**
     * ✅ Set locale
     */
    public static function setLocale(string $locale): string
    {
        $availableLocales = self::getAvailableLocales();

        if (!in_array($locale, $availableLocales)) {
            $locale = config('app.locale', 'en');
        }

        Session::put('locale', $locale);
        App::setLocale($locale);

        return $locale;
    }

    /**
     * ✅ Get all active languages as objects
     */
    public static function getLanguages(): \Illuminate\Support\Collection
    {
        try {
            $languages = Language::where('is_active', true)
                ->orderBy('order')
                ->get();

            if ($languages->isEmpty()) {
                return self::getDefaultLanguages();
            }

            // ✅ Ensure each language has flag_class
            $languages = $languages->map(function ($language) {
                $language->flag_class = $language->flag_class ?? self::getFlagClass($language->code);
                $language->flag_emoji = self::getFlag($language->code);
                return $language;
            });

            return $languages;

        } catch (\Exception $e) {
            return self::getDefaultLanguages();
        }
    }

    /**
     * ✅ Get default languages (fallback)
     */
    private static function getDefaultLanguages(): \Illuminate\Support\Collection
    {
        return collect([
            (object) [
                'code' => 'en',
                'name' => 'English',
                'native_name' => 'English',
                'flag' => '🇬🇧',
                'flag_class' => 'fi-gb',  // ✅ Correct class
                'is_default' => true,
                'is_active' => true,
                'order' => 1,
                'direction' => 'ltr',
            ],
            (object) [
                'code' => 'ur',
                'name' => 'Urdu',
                'native_name' => 'اردو',
                'flag' => '🇵🇰',
                'flag_class' => 'fi-pk',  // ✅ Correct class
                'is_default' => false,
                'is_active' => true,
                'order' => 2,
                'direction' => 'rtl',
            ],
            (object) [
                'code' => 'ar',
                'name' => 'Arabic',
                'native_name' => 'العربية',
                'flag' => '🇸🇦',
                'flag_class' => 'fi-sa',  // ✅ Correct class
                'is_default' => false,
                'is_active' => true,
                'order' => 3,
                'direction' => 'rtl',
            ],
        ]);
    }

    /**
     * ✅ Get available locales (array of codes)
     */
    public static function getAvailableLocales(): array
    {
        try {
            $locales = Language::where('is_active', true)
                ->orderBy('order')
                ->pluck('code')
                ->toArray();

            return !empty($locales) ? $locales : ['en', 'ur', 'ar'];
        } catch (\Exception $e) {
            return ['en', 'ur', 'ar'];
        }
    }

    /**
     * ✅ Get flag emoji (fallback for browsers that don't support emoji flags)
     */
    public static function getFlag(string $code): string
    {
        $flags = [
            'en' => '🇬🇧',
            'gb' => '🇬🇧',
            'uk' => '🇬🇧',
            'ur' => '🇵🇰',
            'pk' => '🇵🇰',
            'ar' => '🇸🇦',
            'sa' => '🇸🇦',
            'fr' => '🇫🇷',
            'es' => '🇪🇸',
            'de' => '🇩🇪',
            'zh' => '🇨🇳',
            'hi' => '🇮🇳',
            'it' => '🇮🇹',
            'pt' => '🇵🇹',
            'ru' => '🇷🇺',
            'ja' => '🇯🇵',
            'ko' => '🇰🇷',
        ];

        return $flags[$code] ?? '🌐';
    }

    /**
     * ✅ Get flag CSS class for flag-icons
     * NOTE: flag-icons uses 'flag-icon-' prefix
     */
    public static function getFlagClass(string $code): string
    {
        $flagClasses = [
            'en' => 'fi-gb',
            'gb' => 'fi-gb',
            'uk' => 'fi-gb',
            'ur' => 'fi-pk',
            'pk' => 'fi-pk',
            'ar' => 'fi-sa',
            'sa' => 'fi-sa',
            'fr' => 'fi-fr',
            'es' => 'fi-es',
            'de' => 'fi-de',
            'zh' => 'fi-cn',
            'hi' => 'fi-in',
            'it' => 'fi-it',
            'pt' => 'fi-pt',
            'ru' => 'fi-ru',
            'ja' => 'fi-jp',
            'ko' => 'fi-kr',
        ];

        return $flagClasses[$code] ?? 'fi-un';
    }

    /**
     * ✅ Get language name
     */
    public static function getLanguageName(string $code): string
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
            'it' => 'Italian',
            'pt' => 'Portuguese',
            'ru' => 'Russian',
            'ja' => 'Japanese',
            'ko' => 'Korean',
        ];

        return $names[$code] ?? $code;
    }

    /**
     * ✅ Get native language name
     */
    public static function getNativeName(string $code): string
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
            'it' => 'Italiano',
            'pt' => 'Português',
            'ru' => 'Русский',
            'ja' => '日本語',
            'ko' => '한국어',
        ];

        return $names[$code] ?? $code;
    }

    /**
     * ✅ Get language direction
     */
    public static function getDirection(string $code): string
    {
        $rtlLanguages = ['ur', 'ar', 'he', 'fa'];
        return in_array($code, $rtlLanguages) ? 'rtl' : 'ltr';
    }

    /**
     * ✅ Check if language is default
     */
    public static function isDefault(string $code): bool
    {
        return $code === config('app.locale', 'en');
    }

    /**
     * ✅ Get language by code
     */
    public static function getLanguage(string $code): ?object
    {
        $languages = self::getLanguages();
        return $languages->firstWhere('code', $code);
    }
}
