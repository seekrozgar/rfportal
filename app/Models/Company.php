<?php
// app/Models/Company.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'email',
        'phone',
        'website',
        'address',
        'logo',
        'description',
        'is_active',
        'user_id',
        'package_id',
        'subscription_expires_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'subscription_expires_at' => 'datetime',
    ];

    /**
     * ✅ Scope for active companies only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * ✅ Scope for ordering (without 'order' column)
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('name', 'asc');
    }

    /**
     * ✅ Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function jobs()
    {
        return $this->hasMany(JobPosting::class, 'company_id');
    }

    /**
     * ✅ Accessor for logo URL
     */
    public function getLogoUrlAttribute()
    {
        if ($this->logo) {
            return asset('storage/' . $this->logo);
        }
        return null;
    }

    /**
     * ✅ Check if company has active subscription
     */
    public function hasActiveSubscription()
    {
        if (!$this->subscription_expires_at) {
            return false;
        }
        return $this->subscription_expires_at->isFuture();
    }

    /**
     * ✅ Get remaining job postings count
     */
    public function getRemainingJobsAttribute()
    {
        if (!$this->package) {
            return 0;
        }
        $used = $this->jobs()->count();
        $limit = $this->package->job_limit ?? 0;
        return max(0, $limit - $used);
    }
}
