<?php

namespace App\Notifications;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentReceived extends Notification implements ShouldQueue
{
    use Queueable;

    public $invoice;
    public $payment;

    public function __construct(Invoice $invoice, Payment $payment)
    {
        $this->invoice = $invoice;
        $this->payment = $payment;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Payment Received - Invoice #' . $this->invoice->id)
            ->line('Thank you for your payment of TZS ' . number_format($this->payment->amount, 2))
            ->line('Invoice: ' . $this->invoice->title)
            ->line('Student: ' . $this->invoice->student->name)
            ->line('Payment Method: ' . ucfirst(str_replace('_', ' ', $this->payment->method)))
            ->line('Transaction ID: ' . $this->payment->transaction_id)
            ->action('View Invoice', url('/invoices/' . $this->invoice->id))
            ->line('Thank you for using our application!');
    }

    public function toArray($notifiable)
    {
        return [
            'invoice_id' => $this->invoice->id,
            'payment_id' => $this->payment->id,
            'amount' => $this->payment->amount,
            'method' => $this->payment->method,
            'student_name' => $this->invoice->student->name,
        ];
    }
}