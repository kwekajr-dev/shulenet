<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EventNotification extends Notification
{
    use Queueable;

    public $event;

    public function __construct($event)
    {
        $this->event = $event;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('New School Event: ' . $this->event->title)
                    ->line('A new school event has been scheduled')
                    ->line('Event: ' . $this->event->title)
                    ->line('Date: ' . $this->event->date->format('M d, Y'))
                    ->line('Time: ' . ($this->event->time ? $this->event->time->format('h:i A') : 'TBA'))
                    ->line('Location: ' . ($this->event->location ?? 'TBA'))
                    ->line('Description: ' . $this->event->description)
                    ->action('View Events', url('/parent/events'))
                    ->line('Thank you for using our school management system!');
    }
}