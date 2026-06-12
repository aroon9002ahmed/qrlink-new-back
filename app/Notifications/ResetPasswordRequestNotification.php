<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordRequestNotification extends Notification
{
    use Queueable;

    /**
     * The password reset URL.
     */
    public string $resetUrl;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $resetUrl)
    {
        $this->resetUrl = $resetUrl;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $appName = config('app.name', 'QR Tree');
        $userName = $notifiable->name ?? 'there';
        $resetUrl = $this->resetUrl;
        $year = date('Y');

        return (new MailMessage())
            ->subject('Reset Your ' . $appName . ' Password')
            ->view('emails.reset-password-request', compact('appName', 'userName', 'resetUrl', 'year'));
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [];
    }
}
