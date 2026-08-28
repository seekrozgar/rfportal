<?php
// app/Models/JobApplication.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'user_id',
        'application_reference',
        'status',
        'cover_letter',
        'resume',
        'expected_salary',
        'expected_salary_period',
        'available_from',
        'additional_info',
        'submitted_at',
        'reviewed_at',
        'shortlisted_at',
        'interview_at',
        'offered_at',
        'hired_at',
        'rejected_at',
        'rejection_reason',
        'employer_notes',
        'feedback',
        'rating',
        'source',
        'ip_address',
        'user_agent',
        'is_read',
        'read_at',
        'is_archived',
        'reviewed_by',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_archived' => 'boolean',
        'expected_salary' => 'decimal:2',
        'rating' => 'integer',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'shortlisted_at' => 'datetime',
        'interview_at' => 'datetime',
        'offered_at' => 'datetime',
        'hired_at' => 'datetime',
        'rejected_at' => 'datetime',
        'read_at' => 'datetime',
        'available_from' => 'date',
    ];

    protected $appends = [
        'status_badge',
        'formatted_expected_salary',
        'is_reviewed',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($application) {
            if (empty($application->application_reference)) {
                $application->application_reference = 'APP-' . date('Ymd') . '-' . Str::upper(Str::random(8));
            }
            if (empty($application->submitted_at)) {
                $application->submitted_at = now();
            }
        });
    }

    // ✅ Relationships
    public function job()
    {
        return $this->belongsTo(JobPosting::class, 'job_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // ✅ Accessors
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => '<span class="status-badge pending"><i class="fas fa-clock"></i> Pending</span>',
            'reviewing' => '<span class="status-badge reviewing"><i class="fas fa-search"></i> Reviewing</span>',
            'shortlisted' => '<span class="status-badge shortlisted"><i class="fas fa-star"></i> Shortlisted</span>',
            'interview' => '<span class="status-badge interview"><i class="fas fa-handshake"></i> Interview</span>',
            'offered' => '<span class="status-badge offered"><i class="fas fa-file-signature"></i> Offered</span>',
            'hired' => '<span class="status-badge hired"><i class="fas fa-check-circle"></i> Hired</span>',
            'rejected' => '<span class="status-badge rejected"><i class="fas fa-times-circle"></i> Rejected</span>',
        ];

        return $badges[$this->status] ?? $badges['pending'];
    }

    public function getFormattedExpectedSalaryAttribute()
    {
        if (!$this->expected_salary) {
            return 'Negotiable';
        }
        $period = $this->expected_salary_period ?? 'monthly';
        $periodLabel = $period === 'annual' ? '/year' : '/month';
        return 'PKR ' . number_format($this->expected_salary()) . ' ' . $periodLabel;
    }

    public function getIsReviewedAttribute()
    {
        return !is_null($this->reviewed_at);
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Pending Review',
            'reviewing' => 'Under Review',
            'shortlisted' => 'Shortlisted',
            'interview' => 'Interview Scheduled',
            'offered' => 'Job Offered',
            'hired' => 'Hired',
            'rejected' => 'Rejected',
        ];
        return $labels[$this->status] ?? $this->status;
    }

    // ✅ Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeReviewed($query)
    {
        return $query->whereNotNull('reviewed_at');
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeArchived($query)
    {
        return $query->where('is_archived', true);
    }

    public function scopeNotArchived($query)
    {
        return $query->where('is_archived', false);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByJob($query, $jobId)
    {
        return $query->where('job_id', $jobId);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->whereHas('user', function ($q2) use ($term) {
                $q2->where('name', 'LIKE', "%{$term}%")
                    ->orWhere('email', 'LIKE', "%{$term}%");
            })->orWhere('application_reference', 'LIKE', "%{$term}%");
        });
    }
}
