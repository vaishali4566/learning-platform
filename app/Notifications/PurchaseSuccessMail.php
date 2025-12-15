<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PurchaseSuccessMail extends Notification
{
    public $buyer;
    public $course;

    public function __construct($buyer, $course)
    {
        $this->buyer = $buyer;
        $this->course = $course;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $name = $this->buyer->name ?? $this->buyer->trainer_name ?? 'Learner';

        return (new MailMessage)
            ->subject('Purchase Successful!')
            ->greeting("Hello {$name},")
            ->line("You have successfully purchased **{$this->course->title}**.")
            ->line("Price: ₹{$this->course->price}")
            ->action('View Course', $this->getRedirectUrl())
            ->line('Thank you for learning with us!');
    }

    private function getRedirectUrl()
    {
        if (get_class($this->buyer) === 'App\Models\Trainer') {
            return route('trainer.courses.my.purchases');
        }
        return route('user.courses.my');
    }
}
