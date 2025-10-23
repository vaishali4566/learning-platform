<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeMail extends Notification
{
    use Queueable;
    protected $name;

    /**
     * Create a new notification instance.
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('🎉 Welcome to ' . config('app.name'))
            ->greeting('Hello ' . ucfirst($this->name) . '!')
            ->line('Welcome to ' . config('app.name') . '! We’re excited to have you on board.')
            ->line('You can now log in to your account and start exploring our courses and features.')
            ->action('Go to Dashboard', url('/user/login'))
            ->line('Thank you for joining us!');
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}
