<?php
// app/Notifications/NewScholarshipNotification.php

namespace App\Notifications;

use App\Models\Scholarship;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewScholarshipNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $scholarship;
    protected $newCount;

    public function __construct(Scholarship $scholarship, $newCount = 1)
    {
        $this->scholarship = $scholarship;
        $this->newCount = $newCount;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $scholarship = $this->scholarship;

        return (new MailMessage)
            ->subject("🎓 New Scholarship: {$scholarship->title}")
            ->greeting("Hello {$notifiable->name}!")
            ->line("A new scholarship has been added to Rozgar Finder.")
            ->line("")
            ->line("**📚 Scholarship Details:**")
            ->line("• **Title:** {$scholarship->title}")
            ->when($scholarship->provider, function ($msg, $provider) {
                return $msg->line("• **Provider:** {$provider}");
            })
            ->when($scholarship->university, function ($msg, $university) {
                return $msg->line("• **University:** {$university}");
            })
            ->when($scholarship->country, function ($msg, $country) {
                return $msg->line("• **Country:** {$country}");
            })
            ->when($scholarship->amount, function ($msg, $amount) {
                return $msg->line("• **Amount:** {$amount}");
            })
            ->when($scholarship->deadline, function ($msg, $deadline) {
                return $msg->line("• **Deadline:** " . $deadline->format('d M, Y'));
            })
            ->when($scholarship->degree_level, function ($msg, $level) {
                return $msg->line("• **Degree Level:** {$level}");
            })
            ->when($scholarship->scholarship_type, function ($msg, $type) {
                return $msg->line("• **Scholarship Type:** {$type}");
            })
            ->line("")
            ->when($scholarship->description, function ($msg) use ($scholarship) {
                return $msg->line("**Description:**")
                           ->line(\Illuminate\Support\Str::limit(strip_tags($scholarship->description), 300));
            })
            ->line("")
            ->when($scholarship->apply_link, function ($msg, $link) {
                return $msg->action('Apply Now', $link);
            })
            ->action('View Details', url("/admin/scholarships/{$scholarship->id}/edit"))
            ->line("📊 Total new scholarships: **{$this->newCount}**")
            ->line("")
            ->line("Thank you for using Rozgar Finder!");
    }

    public function toArray($notifiable)
    {
        return [
            'scholarship_id' => $this->scholarship->id,
            'title' => $this->scholarship->title,
            'message' => "New scholarship added: {$this->scholarship->title}",
            'total_new' => $this->newCount,
            'deadline' => $this->scholarship->deadline?->format('Y-m-d'),
            'provider' => $this->scholarship->provider,
        ];
    }
}
