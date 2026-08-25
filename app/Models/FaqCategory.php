<?php
// app/Models/FaqCategory.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class FaqCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    public function faqs()
    {
        return $this->hasMany(Faq::class, 'category_id');
    }

    public function getActiveFaqsCountAttribute()
    {
        return $this->faqs()->where('is_active', true)->count();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('name');
    }

    public function getIconHtmlAttribute()
    {
        if ($this->icon) {
            return '<i class="' . $this->icon . '"></i>';
        }
        return '<i class="fas fa-folder"></i>';
    }
}
