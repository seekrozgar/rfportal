<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Cviebrock\EloquentSluggable\Sluggable; // ✅ Now this will work

class JobPosting extends Model
{
    use HasFactory, LogsActivity, Sluggable;

    protected $table = 'job_postings';

    protected $fillable = [
        'company_id',
        'category_id',
        'posted_by',
        'title',
        'slug',
        'description',
        'requirements',
        'benefits',
        'location',
        'salary_min',
        'salary_max',
        'salary_period',
        'job_type',
        'experience_level',
        'application_deadline',
        'source',
        'is_active',
        'is_featured',
        'views_count',
    ];

    protected $casts = [
        'application_deadline' => 'date',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'views_count' => 'integer',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'title',
                'location',
                'is_active',
                'is_featured',
                'source',
                'company_id',
                'category_id',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * ✅ Sluggable configuration
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    // ============================================================
    // ✅ RELATIONSHIPS
    // ============================================================

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function postedBy()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'job_id');
    }

    // ============================================================
    // ✅ SCOPES
    // ============================================================

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('application_deadline', '>=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeSource($query, $source)
    {
        return $query->where('source', $source);
    }

    public function scopeByCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    // ============================================================
    // ✅ HELPER METHODS
    // ============================================================

    public function isExpired()
    {
        return $this->application_deadline < now();
    }

    public function isAvailable()
    {
        return $this->is_active && !$this->isExpired();
    }

    public function incrementViews()
    {
        $this->increment('views_count');
    }

    public function getFormattedSalaryAttribute()
    {
        if ($this->salary_min && $this->salary_max) {
            return $this->salary_min . ' - ' . $this->salary_max . ' ' . ($this->salary_period ?? '');
        }
        if ($this->salary_min) {
            return $this->salary_min . ' ' . ($this->salary_period ?? '');
        }
        return 'Not specified';
    }

    public function getStatusBadgeClassAttribute()
    {
        if (!$this->is_active) {
            return 'badge-danger';
        }
        if ($this->isExpired()) {
            return 'badge-warning';
        }
        return 'badge-success';
    }

    public function getStatusTextAttribute()
    {
        if (!$this->is_active) {
            return 'Inactive';
        }
        if ($this->isExpired()) {
            return 'Expired';
        }
        return 'Active';
    }
}
