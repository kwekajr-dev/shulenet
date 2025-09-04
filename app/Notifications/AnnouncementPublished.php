<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AnnouncementPublished extends Notification implements ShouldQueue
{
    use Queueable;

    public $announcement;

    public function __construct($announcement)
    {
        $this->announcement = $announcement;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('New Announcement: ' . $this->announcement->title)
                    ->line($this->announcement->title)
                    ->line($this->announcement->content)
                    ->line('Posted by: ' . $this->announcement->teacher->name)
                    ->action('View All Announcements', url('/announcements'))
                    ->line('Thank you for using our school management system!');
    }

    public function toArray($notifiable)
    {
        return [
            'announcement_id' => $this->announcement->id,
            'title' => $this->announcement->title,
            'teacher_name' => $this->announcement->teacher->name,
            'message' => 'A new announcement has been published: ' . $this->announcement->title,
        ];
    }
}