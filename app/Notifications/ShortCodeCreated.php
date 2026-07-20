<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Link;
use App\Models\Qrcode;

class ShortCodeCreated extends Notification
{
    use Queueable;

    public $code;
    public $codeableType;
    public $codeableId;

    /**
     * Create a new notification instance.
     */
    public function __construct($code, $codeableType, $codeableId)
    {
        $this->code = $code;
        $this->codeableType = $codeableType;
        $this->codeableId = $codeableId;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $type = match ($this->codeableType) {
            Link::class => 'links',
            Qrcode::class => 'qrcodes',
            default => strtolower(class_basename($this->codeableType)) . 's',
        };

        $frontendUrl = rtrim(config('app.frontend_url', env('FRONTEND_URL', 'http://localhost:3000')), '/');
        $url = $frontendUrl . "/dashboard/" . $type . "/" . $this->codeableId . "/show";
        $typeName = $type === 'links' ? 'Link' : 'QR Code';

        return (new MailMessage)
            ->subject('ShortCode Created: ' . $this->code)
            ->view('mail.short-code-created', [
                'name' => $notifiable->name,
                'code' => $this->code,
                'type' => $typeName,
                'url' => $url,
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $type = match ($this->codeableType) {
            Link::class => 'links',
            Qrcode::class => 'qrcodes',
            default => strtolower(class_basename($this->codeableType)) . 's',
        };

        return [
            'title' => 'ShortCode Created: ' . $this->code,
            'message' => 'ShortCode "' . $this->code . '" has been created successfully.',
            'url' => "/dashboard/" . $type . "/" . $this->codeableId  . "/show",
            'code_name' => $this->code,
        ];
    }
}
