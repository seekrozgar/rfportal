<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles;

    protected $guard_name = 'web';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'company_id',
        'provider',
        'provider_id',
        'is_active',      // ✅ Add this
        'is_fraud',       // ✅ Add this
        'permissions',    // ✅ Add this (JSON for menu permissions)
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
    ];

    public function company()
    {
        return $this->hasOne(Company::class);
    }

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
}
