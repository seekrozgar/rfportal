<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_type',
        'module',
        'action',
        'description',
        'old_data',
        'new_data',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeRecent($query, $limit = 10)
    {
        return $query->latest()->limit($limit);
    }

    public function getIconAttribute()
    {
        $icons = [
            'create' => 'fa-plus-circle',
            'update' => 'fa-edit',
            'delete' => 'fa-trash-alt',
            'login' => 'fa-sign-in-alt',
            'logout' => 'fa-sign-out-alt',
            'status' => 'fa-toggle-on',
        ];
        return $icons[$this->action] ?? 'fa-bell';
    }

    public function getTypeAttribute()
    {
        $types = [
            'create' => 'success',
            'update' => 'info',
            'delete' => 'danger',
            'login' => 'info',
            'logout' => 'warning',
            'status' => 'warning',
        ];
        return $types[$this->action] ?? 'info';
    }
}
