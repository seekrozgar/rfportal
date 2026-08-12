<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions; // 👈 1. Yeh link top par lazmi hona chahiye

class Job extends Model
{
    use HasFactory, LogsActivity; // 👈 Traits standard setup

    protected $fillable = ['title', 'company_id', 'location', 'is_active'];

    /**
     * ✅ Spatie Trait Requirements Complete Resolution Function
     * Is function ka hona lazmi hai warna abstract method crash dega
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'location', 'is_active']) // Track specified updates
            ->logOnlyDirty()                              // Only track modified fields
            ->dontSubmitEmptyLogs();                      // Prevent recording redundant entries
    }

    /**
     * Relationship with Company Model
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
