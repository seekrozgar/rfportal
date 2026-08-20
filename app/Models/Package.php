<?php
// app/Models/Package.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Package extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'type',
        'price',
        'duration_days',
        'features',
        'is_featured',
        'is_active',
        'job_posts_limit',
        'resume_views_limit',
        'application_boost',
        'badge_color',
        'display_order'
    ];

    protected $casts = [
        'features' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function getFormattedFeaturesAttribute()
    {
        if (empty($this->features)) {
            return [];
        }
        return $this->features;
    }

    public function getIsPopularAttribute()
    {
        return $this->is_featured;
    }

    // Scopes
    public function scopeEmployer($query)
    {
        return $query->where('type', 'employer');
    }

    public function scopeSeeker($query)
    {
        return $query->where('type', 'seeker');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Boot method for slug
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($package) {
            $package->slug = Str::slug($package->name);
        });
    }
}
