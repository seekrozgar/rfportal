<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    /**
     * Create a notification for a specific user.
     */
    public function send(
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
            'icon' => $icon ?: $this->getIcon($type),
            'action_url' => $actionUrl,
        ]);
    }

    /**
     * Send notification to all admin users.
     */
    public function sendToAdmins(
        string $type,
        string $title,
        string $message,
        ?string $actionUrl = null,
        ?string $icon = null
    ): void {
        /*
         * IMPORTANT:
         * This assumes your users table has a "role" column
         * containing "admin".
         */
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            $this->send(
                $admin,
                $type,
                $title,
                $message,
                $actionUrl,
                $icon
            );
        }
    }

    /**
     * Get default FontAwesome icon.
     */
    private function getIcon(string $type): string
    {
        return match ($type) {
            'verification' => 'shield-alt',
            'verification_approved' => 'check-circle',
            'verification_rejected' => 'times-circle',
            'fraud' => 'exclamation-triangle',
            'suspended' => 'ban',
            'job_report' => 'flag',
            'job_application' => 'briefcase',
            'success' => 'check',
            'warning' => 'exclamation',
            'error' => 'times',
            default => 'bell',
        };
    }
}
