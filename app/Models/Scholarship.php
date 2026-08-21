<?php
// app/Models/Scholarship.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Scholarship extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'provider',
        'country',
        'university',
        'degree_level',
        'scholarship_type',
        'amount',
        'deadline',
        'apply_link',
        'featured_image',
        'featured_image_original',
        'eligibility',
        'benefits',
        'required_documents',
        'contact_email',
        'contact_phone',
        'source_url',
        'source',
        'posted_by',
        'is_published',
        'is_draft',
        'views_count'
    ];

    protected $casts = [
        'deadline' => 'datetime',
        'is_published' => 'boolean',
        'is_draft' => 'boolean',
        'views_count' => 'integer',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    public function getExcerptAttribute()
    {
        return Str::limit(strip_tags($this->description), 150);
    }

    public function getFormattedDeadlineAttribute()
    {
        return $this->deadline?->format('d M, Y') ?? 'Not specified';
    }

    public function getIsDeadlinePassedAttribute()
    {
        if (!$this->deadline) {
            return false;
        }
        return $this->deadline->isPast();
    }

    public function getDaysRemainingAttribute()
    {
        if (!$this->deadline || $this->deadline->isPast()) {
            return 0;
        }
        return $this->deadline->diffInDays(now());
    }

    public function getFeaturedImageUrlAttribute()
    {
        if ($this->featured_image) {
            return asset('storage/' . $this->featured_image);
        }
        return null;
    }

    public function getSourceNameAttribute()
    {
        $sources = [
            'propakistani' => 'Pro Pakistani',
            'scholars4dev' => 'Scholars4Dev',
            'opportunitydesk' => 'Opportunity Desk',
            'scholarshipscorner' => 'Scholarships Corner',
            'studyportals' => 'Study Portals',
        ];
        return $sources[$this->source] ?? $this->source ?? 'Manual';
    }

    public function getStatusBadgeAttribute()
    {
        if ($this->is_draft) {
            return '<span class="status-badge status-draft"><i class="fas fa-file-alt"></i> Draft</span>';
        }
        if ($this->is_published && !$this->is_deadline_passed) {
            return '<span class="status-badge status-completed"><i class="fas fa-check-circle"></i> Active</span>';
        }
        if ($this->is_published && $this->is_deadline_passed) {
            return '<span class="status-badge status-expired"><i class="fas fa-clock"></i> Expired</span>';
        }
        return '<span class="status-badge status-pending"><i class="fas fa-clock"></i> Pending</span>';
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($scholarship) {
            $scholarship->slug = Str::slug($scholarship->title);
            if (!$scholarship->views_count) {
                $scholarship->views_count = 0;
            }
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
        return $query->where('deadline', '>=', now());
    }

    public function scopeExpired($query)
    {
        return $query->where('deadline', '<', now());
    }

    public function scopeDraft($query)
    {
        return $query->where('is_draft', true);
    }

    public function scopeBySource($query, $source)
    {
        return $query->where('source', $source);
    }
}
