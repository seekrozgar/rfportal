<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;

class PasswordChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $changeType;
    protected $ipAddress;
    protected $userAgent;
    protected $changeTime;

    public function __construct($changeType = 'changed', $ipAddress = null, $userAgent = null)
    {
        $this->changeType = $changeType;
        $this->ipAddress = $ipAddress ?? request()->ip();
        $this->userAgent = $userAgent ?? request()->userAgent();
        $this->changeTime = Carbon::now();
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $messages = [
            'reset' => [
                'subject' => '🔐 Your Password Has Been Reset',
                'line1' => 'Your password has been successfully reset.',
                'line2' => 'If you did not request this password reset, please contact us immediately.',
            ],
            'changed' => [
                'subject' => '🔐 Your Password Has Been Changed',
                'line1' => 'Your password has been successfully changed.',
                'line2' => 'If you did not change your password, please contact us immediately.',
            ],
            'admin_changed' => [
                'subject' => '🔐 Your Password Was Updated by Admin',
                'line1' => 'An administrator has updated your password.',
                'line2' => 'If you did not request this change, please contact us immediately.',
            ],
        ];

        $message = $messages[$this->changeType] ?? $messages['changed'];

        return (new MailMessage)
            ->subject($message['subject'])
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line($message['line1'])
            ->line('📅 Date & Time: ' . $this->changeTime->format('F j, Y g:i A'))
            ->line('📍 IP Address: ' . $this->ipAddress)
            ->line('🖥️ Device: ' . $this->userAgent)
            ->line('')
            ->line('⚠️ ' . $message['line2'])
            ->action('🔑 Login to Your Account', route('login'))
            ->line('')
            ->line('📧 For any assistance, contact support@rozgarfinder.com')
            ->line('Thank you for using Rozgar Finder!');
    }
}
