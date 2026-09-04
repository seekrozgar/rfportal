<?php
// app/Models/Company.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'website',
        'address',
        'description',
        'industry',
        'company_size',
        'founded_year',
        'headquarters',
        'ntn_number',
        'secp_number',
        'facebook',
        'linkedin',
        'twitter',
        'instagram',
        'youtube',

        'logo',
        'cover_image',
        'business_license',

        'is_active',

        // Verification
        'verification_status',
        'verification_requested_at',
        'verified_at',
        'verified_by',
        'verification_rejection_reason',
        'verification_admin_note',
        'is_suspended',
        'is_fraud',
        'fraud_reason',
        'fraud_marked_at',
        'fraud_marked_by',
        // Trust / fraud
        'is_verified',
        'is_blocked',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_suspended' => 'boolean',
        'is_fraud' => 'boolean',
        'verification_requested_at' => 'datetime',
        'verified_at' => 'datetime',
        'fraud_marked_at' => 'datetime',

        'is_verified' => 'boolean',
        'is_featured' => 'boolean',

        'views_count' => 'integer',
        'job_posts_count' => 'integer',



    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($company) {
            if (empty($company->slug)) {
                $company->slug = Str::slug($company->name) . '-' . uniqid();
            }
        });

        static::updating(function ($company) {
            if ($company->isDirty('name') && !$company->isDirty('slug')) {
                $company->slug = Str::slug($company->name) . '-' . uniqid();
            }
        });
    }

    // ✅ Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function actions()
    {
        return $this->hasMany(CompanyAction::class);
    }

    public function reports()
    {
        return $this->hasMany(CompanyReport::class);
    }
    public function verificationReviewer()
    {
        return $this->belongsTo(
            User::class,
            'verification_reviewed_by'
        );
    }

    public function jobs()
    {
        return $this->hasMany(JobPosting::class, 'company_id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // ✅ Accessors
    public function getLogoUrlAttribute()
    {
        if ($this->logo) {
            return asset('storage/' . $this->logo);
        }
        return null;
    }

    public function getCoverImageUrlAttribute()
    {
        if ($this->cover_image) {
            return asset('storage/' . $this->cover_image);
        }
        return null;
    }

    public function getBusinessLicenseUrlAttribute()
    {
        if ($this->business_license) {
            return asset('storage/' . $this->business_license);
        }
        return null;
    }

    public function getCompanySizeLabelAttribute()
    {
        $sizes = [
            '1-10' => '1-10 employees',
            '11-50' => '11-50 employees',
            '51-200' => '51-200 employees',
            '201-500' => '201-500 employees',
            '501-1000' => '501-1000 employees',
            '500+' => '500+ employees',
        ];
        return $sizes[$this->company_size] ?? $this->company_size ?? 'Not specified';
    }

    public function getIsCompleteAttribute()
    {
        $required = ['name', 'email', 'phone', 'address', 'description', 'industry'];
        foreach ($required as $field) {
            if (empty($this->$field)) {
                return false;
            }
        }
        return true;
    }

    public function getCompletionPercentageAttribute()
    {
        $fields = [
            'name',
            'email',
            'phone',
            'website',
            'address',
            'description',
            'logo',
            'cover_image',
            'industry',
            'company_size',
            'founded_year',
            'headquarters',
            'ntn_number',
            'secp_number',
            'business_license'
        ];
        $filled = 0;
        foreach ($fields as $field) {
            if (!empty($this->$field)) {
                $filled++;
            }
        }
        return round(($filled / count($fields)) * 100);
    }

    // ✅ Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'LIKE', "%{$term}%")
                ->orWhere('description', 'LIKE', "%{$term}%")
                ->orWhere('industry', 'LIKE', "%{$term}%");
        });
    }

    /**
     * Get verification status badge
     */
    public function getVerificationBadgeAttribute(): string
    {
        return match ($this->verification_status) {
            'verified' => '<span class="badge bg-success">Verified</span>',
            'pending' => '<span class="badge bg-warning text-dark">Pending</span>',
            'rejected' => '<span class="badge bg-danger">Rejected</span>',
            default => '<span class="badge bg-secondary">Unverified</span>',
        };
    }

    /**
     * Get all audit logs for this company
     */
    public function auditLogs(): HasMany
    {
        return $this->hasMany(CompanyAuditLog::class)->latest();
    }

    /**
     * Get latest audit log
     */
    public function latestAuditLog(): ?CompanyAuditLog
    {
        return $this->auditLogs()->first();
    }

    /**
     * Log an admin action with full audit trail
     */
    public function logAdminAction(
        string $action,
        ?string $reason = null,
        ?string $adminNote = null,
        ?string $ticketNumber = null,
        array $metadata = []
    ): CompanyAuditLog {
        // Get current admin
        $admin = auth()->user();

        // Generate ticket number if not provided
        $ticketNumber = $ticketNumber ?? CompanyAuditLog::generateTicketNumber();

        return CompanyAuditLog::create([
            'company_id' => $this->id,
            'user_id' => $admin?->id,
            'action' => $action,
            'status_before' => $this->verification_status,
            'status_after' => $this->verification_status, // Will be updated after
            'reason' => $reason,
            'admin_note' => $adminNote,
            'ticket_number' => $ticketNumber,
            'metadata' => array_merge($metadata, [
                'company_name' => $this->name,
                'admin_name' => $admin?->name,
                'admin_email' => $admin?->email,
            ]),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }


}
