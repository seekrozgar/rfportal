<?php
// app/Models/Admission.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Admission extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'institution',
        'programs_offered',
        'category',
        'last_date',
        'announcement_date',
        'fee',
        'apply_through',
        'apply_link',
        'eligibility',
        'required_documents',
        'contact_email',
        'contact_phone',
        'featured_image',
        'featured_image_original',
        'posted_by',
        'is_published',
        'views_count'
    ];

    protected $casts = [
        'last_date' => 'datetime',
        'announcement_date' => 'datetime',
        'is_published' => 'boolean',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    public function getExcerptAttribute()
    {
        return Str::limit(strip_tags($this->description), 150);
    }

    public function getFormattedLastDateAttribute()
    {
        if ($this->last_date) {
            return $this->last_date->format('d M, Y');
        }
        return 'Not specified';
    }

    public function getFormattedAnnouncementDateAttribute()
    {
        if ($this->announcement_date) {
            return $this->announcement_date->format('d M, Y');
        }
        return $this->created_at->format('d M, Y');
    }

    public function getIsDeadlinePassedAttribute()
    {
        if (!$this->last_date) {
            return false;
        }
        return $this->last_date->isPast();
    }

    public function getDaysRemainingAttribute()
    {
        if (!$this->last_date) {
            return 0;
        }
        if ($this->last_date->isPast()) {
            return 0;
        }
        return $this->last_date->diffInDays(now());
    }

    public function getFeaturedImageUrlAttribute()
    {
        if ($this->featured_image) {
            return asset('storage/' . $this->featured_image);
        }
        return null;
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($admission) {
            $admission->slug = Str::slug($admission->title);
        });
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('last_date', '>=', now());
    }

    public function scopeExpired($query)
    {
        return $query->where('last_date', '<', now());
    }
}
