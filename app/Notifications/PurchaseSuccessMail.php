<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\User;
use App\Models\Course;

class PurchaseSuccessMail extends Notification
{
    use Queueable;

    protected $user;
    protected $course;

    public function __construct(User $user, Course $course)
    {
        $this->user = $user;
        $this->course = $course;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Course Purchase Successful')
                    ->greeting('Hello ' . $this->user->name)
                    ->line('You have successfully purchased the course: ' . $this->course->title)
                    ->action('Go to Dashboard', url(route('user.dashboard')))
                    ->line('Thank you for learning with us!');
    }
}
