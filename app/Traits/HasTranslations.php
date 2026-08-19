<?php

namespace App\Traits;

use App\Models\Translation;

trait HasTranslations
{
    /**
     * Get all translations for this model
     */
    public function translations()
    {
        return $this->morphMany(Translation::class, 'model');
    }

    /**
     * Get translated value for a field
     */
    public function getTranslation($field, $locale = null)
    {
        $locale = $locale ?? app()->getLocale();

        $translation = $this->translations()
            ->where('key', $field)
            ->where('language_code', $locale)
            ->first();

        if ($translation) {
            return $translation->value;
        }

        return $this->getOriginal($field);
    }

    /**
     * Set translation for a field
     */
    public function setTranslation($field, $value, $locale = null)
    {
        $locale = $locale ?? app()->getLocale();

        $this->translations()->updateOrCreate(
            [
                'key' => $field,
                'language_code' => $locale,
            ],
            [
                'value' => $value,
                'group' => $this->getTable(),
            ]
        );

        return $this;
    }

    /**
     * Get all translations for this model grouped by language
     */
    public function getAllTranslations()
    {
        $translations = [];

        foreach ($this->translations as $translation) {
            if (!isset($translations[$translation->language_code])) {
                $translations[$translation->language_code] = [];
            }
            $translations[$translation->language_code][$translation->key] = $translation->value;
        }

        return $translations;
    }

    /**
     * Check if translation exists for a field
     */
    public function hasTranslation($field, $locale = null)
    {
        $locale = $locale ?? app()->getLocale();

        return $this->translations()
            ->where('key', $field)
            ->where('language_code', $locale)
            ->exists();
    }
}
