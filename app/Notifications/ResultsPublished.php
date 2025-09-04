<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResultNotification extends Notification
{
    use Queueable;

    public $result;

    public function __construct($result)
    {
        $this->result = $result;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('New Academic Result: ' . $this->result->term . ' ' . $this->result->subject)
                    ->line('A new academic result has been published for ' . $this->result->student->name)
                    ->line('Subject: ' . $this->result->subject)
                    ->line('Score: ' . $this->result->score)
                    ->line('Grade: ' . $this->result->grade)
                    ->action('View Results', url('/parent/results'))
                    ->line('Thank you for using our school management system!');
    }
}