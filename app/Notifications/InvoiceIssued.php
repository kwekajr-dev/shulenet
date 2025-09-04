<?php

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoiceIssued extends Notification implements ShouldQueue
{
    use Queueable;

    public $invoice;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Invoice Issued - ' . $this->invoice->title)
            ->line('A new invoice has been issued for ' . $this->invoice->student->name)
            ->line('Amount: TZS ' . number_format($this->invoice->amount, 2))
            ->line('Due Date: ' . $this->invoice->due_date->format('M d, Y'))
            ->action('View Invoice', url('/invoices/' . $this->invoice->id))
            ->line('Thank you for using our application!');
    }

    public function toArray($notifiable)
    {
        return [
            'invoice_id' => $this->invoice->id,
            'title' => $this->invoice->title, 
            'amount' => $this->invoice->amount,
            'student_name' => $this->invoice->student->name,
            'message' => 'New invoice issued: ' . $this->invoice->title,
        ];
    }
}