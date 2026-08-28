<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Notifications\PasswordChangedNotification;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles;

    protected $guard_name = 'web';

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'designation',
        'avatar',
        'role',
        'company_id',
        'provider',
        'provider_id',
        'is_active',
        'is_fraud',
        'permissions',
        'notify_scholarships',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'is_fraud' => 'boolean',
        'permissions' => 'array',
        'notify_scholarships' => 'boolean'
    ];

    // ============================================================
    // ✅ RELATIONSHIPS
    // ============================================================

    public function company()
    {
        return $this->hasOne(Company::class);
    }

    // ============================================================
    // ✅ ROLE CHECK METHODS
    // ============================================================

    public function isSuperAdmin()
    {
        return $this->role === 'superadmin';
    }

    public function isAdmin()
    {
        return $this->role === 'admin' || $this->role === 'superadmin';
    }

    public function isEmployer()
    {
        return $this->role === 'employer';
    }

    public function isSeeker()
    {
        return $this->role === 'seeker';
    }

    public function isAuthor()
    {
        return $this->role === 'author';
    }

    public function isActive()
    {
        return $this->is_active && !$this->is_fraud;
    }

    // ============================================================
    // ✅ MENU PERMISSION CHECK
    // ============================================================

    public function canAccessMenu($menuItem)
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        if (!$this->permissions) {
            return false;
        }

        return in_array($menuItem, $this->permissions);
    }

    // ============================================================
    // ✅ PASSWORD CHANGE NOTIFICATION SYSTEM
    // ============================================================

    /**
     * Send password changed notification to user
     *
     * @param string $type 'reset' | 'changed' | 'admin_changed'
     */
    public function sendPasswordChangedNotification($type = 'changed')
    {
        $this->notify(new PasswordChangedNotification($type));
    }

    /**
     * Check if password was changed and send notification
     */
    public function handlePasswordChange($type = 'changed')
    {
        if ($this->wasChanged('password')) {
            $this->sendPasswordChangedNotification($type);
        }
    }

    // ✅ Method to check if user should receive scholarship notifications
    public function wantsScholarshipNotifications()
    {
        return $this->notify_scholarships || in_array($this->role, ['admin', 'seeker']);
    }
}
