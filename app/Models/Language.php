<?php
// app/Models/Language.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\LanguageHelper;

class Language extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'native_name',
        'flag',
        'flag_class',
        'is_active',
        'is_default',
        'order',
        'direction',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * ✅ Get flag emoji
     */
    public function getFlagEmojiAttribute(): string
    {
        return LanguageHelper::getFlag($this->code);
    }

    /**
     * ✅ Get flag CSS class
     */
    public function getFlagCssClassAttribute(): string
    {
        return $this->flag_class ?? LanguageHelper::getFlagClass($this->code);
    }

    /**
     * ✅ Get language direction
     */
    public function getDirectionAttribute(): string
    {
        return $this->attributes['direction'] ?? LanguageHelper::getDirection($this->code);
    }
}
