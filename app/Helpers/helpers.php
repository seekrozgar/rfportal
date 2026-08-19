<?php

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
