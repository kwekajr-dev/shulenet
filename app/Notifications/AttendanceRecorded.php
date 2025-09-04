<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AttendanceRecorded extends Notification implements ShouldQueue
{
    use Queueable;

    public $attendance;

    public function __construct($attendance)
    {
        $this->attendance = $attendance;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Attendance Recorded: ' . $this->attendance->student->name)
                    ->line('Attendance has been recorded for ' . $this->attendance->student->name)
                    ->line('Date: ' . $this->attendance->date->format('M d, Y'))
                    ->line('Status: ' . ucfirst($this->attendance->status))
                    ->action('View Attendance', url('/parent/attendance'))
                    ->line('Thank you for using our school management system!');
    }
}