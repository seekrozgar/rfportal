<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    /**
     * Notification icons.
     */
    private static array $iconMap = [

        'verification' => 'shield-alt',
        'verification_pending' => 'clock',

        'verification_approved' => 'check-circle',
        'verification_rejected' => 'times-circle',
        'verification_revoked' => 'shield-alt',

        'company_verified' => 'check-circle',

        'job_posted' => 'briefcase',
        'job_approved' => 'check',
        'job_rejected' => 'times',

        'application' => 'file-alt',
        'application_shortlisted' => 'star',
        'application_interview' => 'calendar',
        'application_hired' => 'trophy',
        'application_rejected' => 'times',

        'fraud' => 'exclamation-triangle',
        'fraud_removed' => 'shield-alt',

        'suspended' => 'ban',
        'blocked' => 'ban',
        'restored' => 'check',

        'success' => 'check-circle',
        'warning' => 'exclamation-triangle',
        'info' => 'info-circle',
    ];

    /**
     * Send notification to one user.
     */
    public static function send(
        User $user,
        string $type,
        string $title,
        string $message,
        ?string $actionUrl = null,
        ?string $icon = null
    ): Notification {

        return Notification::create([
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'icon' => $icon ?: self::getIcon($type),
            'action_url' => $actionUrl,
            'read_at' => null,
        ]);
    }

    /**
     * Get icon.
     */
    private static function getIcon(string $type): string
    {
        return self::$iconMap[$type] ?? 'bell';
    }

    /**
     * Send notification to all admins.
     */
    public static function sendToAdmins(
        string $type,
        string $title,
        string $message,
        ?string $actionUrl = null,
        ?string $icon = null
    ): void {

        $admins = User::whereHas('roles', function ($query) {
            $query->whereIn('name', [
                'admin',
                'superadmin',
            ]);
        })->get();

        foreach ($admins as $admin) {

            self::send(
                $admin,
                $type,
                $title,
                $message,
                $actionUrl,
                $icon
            );
        }
    }
}
