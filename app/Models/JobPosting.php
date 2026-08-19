<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Cviebrock\EloquentSluggable\Sluggable;
use App\Traits\HasTranslations;

class JobPosting extends Model
{
    use HasFactory, LogsActivity, Sluggable, HasTranslations;

    protected $table = 'job_postings';

    protected $fillable = [
        'company_id',
        'category_id',
        'job_type_id',
        'experience_level_id',
        'posted_by',
        'title',
        'slug',
        'ad_image',
        'apply_link',
        'description',
        'requirements',
        'benefits',
        'location',
        'salary_min',
        'salary_max',
        'salary_period_id',      // ✅ Changed to ID
        'job_shift_id',          // ✅ New
        'career_level_id',       // ✅ New
        'degree_level_id',       // ✅ New
        'gender_id',             // ✅ New
        'industry_id',           // ✅ New
        'functional_area_id',    // ✅ New
        'marital_status_id',     // ✅ New
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

    // ============================================================
    // ✅ MAGIC METHOD TO GET TRANSLATED ATTRIBUTES
    // ============================================================

    public function __get($key)
    {
        // If property exists in model, return it
        if (isset($this->attributes[$key])) {
            $value = $this->getOriginal($key);
            // Check if translation exists for this field
            if ($this->hasTranslation($key)) {
                return $this->getTranslation($key);
            }
            return $value;
        }

        return parent::__get($key);
    }

    // ============================================================
    // ✅ ALL RELATIONSHIPS
    // ============================================================

    public function category()
    {
        return $this->belongsTo(JobCategory::class, 'category_id');
    }

    public function jobType()
    {
        return $this->belongsTo(JobType::class, 'job_type_id');
    }

    public function experienceLevel()
    {
        return $this->belongsTo(ExperienceLevel::class, 'experience_level_id');
    }

    public function salaryPeriod()
    {
        return $this->belongsTo(SalaryPeriod::class, 'salary_period_id');
    }

    public function jobShift()
    {
        return $this->belongsTo(JobShift::class, 'job_shift_id');
    }

    public function careerLevel()
    {
        return $this->belongsTo(CareerLevel::class, 'career_level_id');
    }

    public function degreeLevel()
    {
        return $this->belongsTo(DegreeLevel::class, 'degree_level_id');
    }

    public function gender()
    {
        return $this->belongsTo(Gender::class, 'gender_id');
    }

    public function industry()
    {
        return $this->belongsTo(Industry::class, 'industry_id');
    }

    public function functionalArea()
    {
        return $this->belongsTo(FunctionalArea::class, 'functional_area_id');
    }

    public function maritalStatus()
    {
        return $this->belongsTo(MaritalStatus::class, 'marital_status_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
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
    // ✅ ACTIVITY LOG
    // ============================================================

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'location', 'is_active', 'is_featured', 'source'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // ============================================================
    // ✅ SLUGGABLE
    // ============================================================

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
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

    // ============================================================
    // ✅ HELPERS
    // ============================================================

    public function isExpired()
    {
        return $this->application_deadline < now();
    }

    public function isAvailable()
    {
        return $this->is_active && !$this->isExpired();
    }

    public function getFormattedSalaryAttribute()
    {
        if ($this->salary_min && $this->salary_max) {
            return $this->salary_min . ' - ' . $this->salary_max . ' ' . ($this->salaryPeriod->name ?? '');
        }
        if ($this->salary_min) {
            return $this->salary_min . ' ' . ($this->salaryPeriod->name ?? '');
        }
        return 'Not specified';
    }

    public function getAdImageUrlAttribute()
    {
        if ($this->ad_image) {
            return asset('storage/jobs/' . $this->ad_image);
        }
        return null;
    }
}
