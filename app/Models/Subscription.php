<?php
// app/Models/Subscription.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'package_id',
        'type',
        'start_date',
        'end_date',
        'status',
        'job_posts_used',
        'job_posts_limit',
        'resume_views_used',
        'resume_views_limit',
        'features_used',
        'payment_id'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'features_used' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function getIsActiveAttribute()
    {
        return $this->status === 'active' && $this->end_date > now();
    }

    public function getRemainingJobPostsAttribute()
    {
        return $this->job_posts_limit - $this->job_posts_used;
    }

    public function getRemainingResumeViewsAttribute()
    {
        return $this->resume_views_limit - $this->resume_views_used;
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where('end_date', '>', now());
    }
}
