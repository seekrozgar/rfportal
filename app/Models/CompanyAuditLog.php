<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyAuditLog extends Model
{
    protected $fillable = [
        'company_id',
        'user_id',
        'action',
        'status_before',
        'status_after',
        'reason',
        'admin_note',
        'ticket_number',
        'metadata',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * Get the company that was audited
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the admin who performed the action
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get action badge
     */
    public function getActionBadgeAttribute(): string
    {
        return match ($this->action) {
            'approve' => '<span class="badge bg-success">Approved</span>',
            'reject' => '<span class="badge bg-danger">Rejected</span>',
            'suspend' => '<span class="badge bg-warning text-dark">Suspended</span>',
            'block' => '<span class="badge bg-dark">Blocked</span>',
            'restore' => '<span class="badge bg-info">Restored</span>',
            'unverify' => '<span class="badge bg-secondary">Unverified</span>',
            'mark_fraud' => '<span class="badge bg-danger">Marked Fraud</span>',
            'remove_fraud' => '<span class="badge bg-success">Removed Fraud</span>',
            default => '<span class="badge bg-secondary">' . ucfirst($this->action) . '</span>',
        };
    }

    /**
     * Scope for specific company
     */
    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    /**
     * Scope for specific action
     */
    public function scopeAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope for date range
     */
    public function scopeDateRange($query, $from, $to)
    {
        return $query->whereBetween('created_at', [$from, $to]);
    }

    /**
     * Generate ticket number
     */
    public static function generateTicketNumber(): string
    {
        $prefix = 'TKT';
        $date = now()->format('Ymd');
        $random = strtoupper(substr(uniqid(), -6));
        return $prefix . '-' . $date . '-' . $random;
    }
}
