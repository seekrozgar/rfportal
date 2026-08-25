<?php
// app/Models/JobPosting.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class JobPosting extends Model
{
    use HasFactory;

    protected $table = 'job_postings';

    protected $fillable = [
        'title',
        'slug',
        'job_source',
        'company_id',
        'posted_by',

        // Job Details
        'category_id',
        'job_type_id',
        'job_shift_id',
        'location',
        'experience_level_id',
        'career_level_id',
        'industry_id',
        'functional_area_id',
        'degree_level_id',
        'degree_type_id',
        'major_subject_id',
        'gender_id',
        'marital_status_id',
        'language_level_id',

        // Salary
        'salary_min',
        'salary_max',
        'salary_period_id',

        // Advertisement
        'advertisement_image',
        'apply_link',
        'description',

        // Requirements
        'requirements',
        'benefits',
        'skills_required',
        'responsibilities',

        // Application
        'apply_email',
        'apply_phone',
        'application_instructions',

        // Status
        'deadline',
        'vacancies',
        'publish_date',
        'is_active',
        'is_featured',
        'is_urgent',
        'is_remote',
        'is_fresh',
        'is_verified',

        // Stats
        'views_count',
        'applications_count',
        'shares_count',

        // Meta
        'source',
        'source_url',
        'source_id',
        'meta_title',
        'meta_description',
        'meta_keywords',

        // Publishing
        'published_at',
        'expired_at',
        'verified_at',
        'verified_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_urgent' => 'boolean',
        'is_remote' => 'boolean',
        'is_fresh' => 'boolean',
        'is_verified' => 'boolean',
        'deadline' => 'date',
        'publish_date' => 'date',
        'published_at' => 'datetime',
        'expired_at' => 'datetime',
        'verified_at' => 'datetime',
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
        'views_count' => 'integer',
        'applications_count' => 'integer',
        'shares_count' => 'integer',
        'vacancies' => 'integer',
    ];

    protected $appends = [
        'status_badge',
        'source_badge',
        'formatted_salary',
        'days_remaining',
        'is_expired',
        'excerpt',
        'company_logo_url',
        'advertisement_image_url',
        'company_details',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($job) {
            if (empty($job->slug)) {
                $job->slug = Str::slug($job->title) . '-' . uniqid();
            }
            if (empty($job->published_at) && $job->is_active) {
                $job->published_at = now();
            }
            if (empty($job->publish_date)) {
                $job->publish_date = now();
            }

            // ✅ Admin jobs are auto-verified
            if ($job->job_source === 'admin') {
                $job->is_verified = true;
                $job->verified_at = now();
                $job->verified_by = auth()->id();
            }
        });

        static::updating(function ($job) {
            if ($job->isDirty('title') && !$job->isDirty('slug')) {
                $job->slug = Str::slug($job->title) . '-' . uniqid();
            }
            if ($job->isDirty('is_active') && $job->is_active && empty($job->published_at)) {
                $job->published_at = now();
            }
        });
    }

    // ============================================================
    // ✅ RELATIONSHIPS
    // ============================================================

    public function category()
    {
        return $this->belongsTo(JobCategory::class, 'category_id');
    }

    public function jobType()
    {
        return $this->belongsTo(JobType::class, 'job_type_id');
    }

    public function jobShift()
    {
        return $this->belongsTo(JobShift::class, 'job_shift_id');
    }

    public function experienceLevel()
    {
        return $this->belongsTo(ExperienceLevel::class, 'experience_level_id');
    }

    public function careerLevel()
    {
        return $this->belongsTo(CareerLevel::class, 'career_level_id');
    }

    public function industry()
    {
        return $this->belongsTo(Industry::class, 'industry_id');
    }

    public function functionalArea()
    {
        return $this->belongsTo(FunctionalArea::class, 'functional_area_id');
    }

    public function degreeLevel()
    {
        return $this->belongsTo(DegreeLevel::class, 'degree_level_id');
    }

    public function degreeType()
    {
        return $this->belongsTo(DegreeType::class, 'degree_type_id');
    }

    public function majorSubject()
    {
        return $this->belongsTo(MajorSubject::class, 'major_subject_id');
    }

    public function gender()
    {
        return $this->belongsTo(Gender::class, 'gender_id');
    }

    public function maritalStatus()
    {
        return $this->belongsTo(MaritalStatus::class, 'marital_status_id');
    }

    public function languageLevel()
    {
        return $this->belongsTo(LanguageLevel::class, 'language_level_id');
    }

    public function salaryPeriod()
    {
        return $this->belongsTo(SalaryPeriod::class, 'salary_period_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // ============================================================
    // ✅ ACCESSORS
    // ============================================================

    public function getCompanyDetailsAttribute()
    {
        if ($this->company_id) {
            return $this->company;
        }
        return null;
    }

    public function getCompanyLogoUrlAttribute()
    {
        if ($this->company_id && $this->company) {
            return $this->company->logo_url;
        }
        return null;
    }

    public function getAdvertisementImageUrlAttribute()
    {
        if ($this->advertisement_image) {
            return asset('storage/' . $this->advertisement_image);
        }
        return null;
    }

    public function getSourceBadgeAttribute()
    {
        if ($this->job_source === 'company') {
            return '<span class="badge bg-primary"><i class="fas fa-building"></i> Company</span>';
        }
        return '<span class="badge bg-success"><i class="fas fa-user-shield"></i> Admin</span>';
    }

    public function getStatusBadgeAttribute()
    {
        if ($this->job_source === 'company' && !$this->is_verified) {
            return '<span class="status-badge status-pending"><i class="fas fa-clock"></i> Pending</span>';
        }
        if ($this->is_expired) {
            return '<span class="status-badge status-expired"><i class="fas fa-clock"></i> Expired</span>';
        }
        if (!$this->is_active) {
            return '<span class="status-badge status-inactive"><i class="fas fa-ban"></i> Inactive</span>';
        }
        if ($this->is_urgent) {
            return '<span class="status-badge status-urgent"><i class="fas fa-fire"></i> Urgent</span>';
        }
        if ($this->is_featured) {
            return '<span class="status-badge status-featured"><i class="fas fa-star"></i> Featured</span>';
        }
        if ($this->is_fresh) {
            return '<span class="status-badge status-fresh"><i class="fas fa-leaf"></i> Fresh</span>';
        }
        return '<span class="status-badge status-active"><i class="fas fa-check-circle"></i> Active</span>';
    }

    public function getFormattedSalaryAttribute()
    {
        if ($this->salary_min && $this->salary_max) {
            $min = number_format($this->salary_min);
            $max = number_format($this->salary_max);
            $period = $this->salaryPeriod?->name ?? 'Month';
            return 'PKR ' . $min . ' - ' . $max . ' / ' . $period;
        }
        if ($this->salary_min) {
            $period = $this->salaryPeriod?->name ?? 'Month';
            return 'PKR ' . number_format($this->salary_min) . ' / ' . $period;
        }
        return 'Negotiable';
    }

    public function getDaysRemainingAttribute()
    {
        if (!$this->deadline)
            return null;
        $days = now()->diffInDays($this->deadline, false);
        return $days < 0 ? 0 : $days;
    }

    public function getIsExpiredAttribute()
    {
        if (!$this->deadline)
            return false;
        return $this->deadline->isPast();
    }

    public function getExcerptAttribute()
    {
        return Str::limit(strip_tags($this->description), 200);
    }

    // ============================================================
    // ✅ SCOPES
    // ============================================================

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAdminJobs($query)
    {
        return $query->where('job_source', 'admin');
    }

    public function scopeCompanyJobs($query)
    {
        return $query->where('job_source', 'company');
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopePendingVerification($query)
    {
        return $query->where('job_source', 'company')->where('is_verified', false);
    }

    public function scopePublished($query)
    {
        return $query->where('is_active', true)->where(function ($q) {
            $q->where('deadline', '>=', now())->orWhereNull('deadline');
        });
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeUrgent($query)
    {
        return $query->where('is_urgent', true);
    }

    public function scopeRemote($query)
    {
        return $query->where('is_remote', true);
    }

    public function scopeByCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('title', 'LIKE', "%{$term}%")
                ->orWhere('description', 'LIKE', "%{$term}%")
                ->orWhere('location', 'LIKE', "%{$term}%")
                ->orWhere('skills_required', 'LIKE', "%{$term}%");
        });
    }
}
