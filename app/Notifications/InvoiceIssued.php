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

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('ANKRA MPYA YA MALIPO IMETOLEWA - ' . $this->invoice->title)
            ->greeting('Hujambo ' . $notifiable->name . ',')
            ->line('Ankra mpya ya malipo imetolewa kwa mwanao **' . $this->invoice->student->name . '**')
            ->line('**Maelezo ya Malipo:**')
            ->line('• **Aina ya Malipo:** ' . $this->invoice->title)
            ->line('• **Kiasi:** TZS ' . number_format($this->invoice->amount, 2))
            ->line('• **Mwisho wa Malipo:** ' . $this->invoice->due_date->format('d M, Y'))
            ->line('• **Hali:** Haijalipiwa')
            ->line('')
            ->line('**⚠️ MUHIMU: LIPIA KWA WAKATI KUEPUKA USUMBUFU ⚠️**')
            ->line('')
            ->line('**Njia za Malipo:**')
            ->line('• Pesa Taslimu - Ofisini')
            ->line('• Uhamisho wa Benki')
            ->line('• Kadi ya Mkopo/Debit')
            ->line('')
            ->action('Angalia Maelezo ya Malipo', url('/invoices/' . $this->invoice->id))
            ->line('Kwa maswali yoyote, tupigie: **0657219617**')
            ->line('')
            ->line('Asante kwa kutumia mfumo wetu wa malipo!')
            ->salutation('Kwa heshima, Timu ya Uongozi');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable)
    {
        return [
            'type' => 'invoice_issued',
            'invoice_id' => $this->invoice->id,
            'title' => $this->invoice->title,
            'amount' => $this->invoice->amount,
            'due_date' => $this->invoice->due_date->format('Y-m-d'),
            'student_name' => $this->invoice->student->name,
            'student_id' => $this->invoice->student->id,
            'message' => 'Ankra mpya imetolewa: ' . $this->invoice->title,
            'swahili_message' => 'Ankara mpya imetolewa kwa ' . $this->invoice->student->name . '. Kiasi: TZS ' . number_format($this->invoice->amount, 2) . '. LIPIA KWA WAKATI KUEPUKA USUMBUFU',
            'status' => 'unread',
            'created_at' => now(),
        ];
    }

    /**
     * Get the notification's database type.
     */
    public function databaseType(object $notifiable): string
    {
        return 'invoice-issued';
    }
}