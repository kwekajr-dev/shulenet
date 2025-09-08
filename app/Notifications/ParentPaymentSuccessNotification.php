<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Payment;
use App\Models\Invoice;

class ParentPaymentSuccessNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $payment;
    public $invoice;

    /**
     * Create a new notification instance.
     */
    public function __construct(Payment $payment, Invoice $invoice)
    {
        $this->payment = $payment;
        $this->invoice = $invoice;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Malipo yamepokelewa - ' . config('app.name'))
                    ->greeting('Habari ' . $notifiable->name . ',')
                    ->line('We have successfully received your payment for ' . $this->invoice->title . '.')
                    ->line('Payment Details:')
                    ->line('Invoice #: ' . $this->invoice->id)
                    ->line('Amount: TZS ' . number_format($this->payment->amount, 2))
                    ->line('Payment Method: ' . ucfirst(str_replace('_', ' ', $this->payment->method)))
                    ->line('Payment Date: ' . $this->payment->paid_at->format('F j, Y'))
                    ->line('Thank you for your timely payment!')
                    ->action('View Invoice', url('/invoices/' . $this->invoice->id))
                    ->salutation('Regards, ' . config('app.name'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'invoice_id' => $this->invoice->id,
            'payment_id' => $this->payment->id,
            'amount' => $this->payment->amount,
            'message' => 'Your payment of TZS ' . number_format($this->payment->amount, 2) . ' has been received successfully.',
        ];
    }
}