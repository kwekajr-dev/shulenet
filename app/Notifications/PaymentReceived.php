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
      $schoolName = config('app.name');

return (new MailMessage)
    ->subject('MALIPO YAMEPOKELEWA - Namba ya Invoice #' . $this->invoice->id . ' - ' . $schoolName)
    ->greeting('HONGERA ' . strtoupper($notifiable->name) . '!')
    ->line('MALIPO YAKO YAMEPOKEWA KIKAMILIFU!')
    ->line('Tumepokea malipo yako ya ankra: ' . $this->invoice->title)
    ->line('')
    ->line('Asante sana kwa ushirikiano wako na kufanya  malipo kwa wakati. Usaidizi wako unawezesha huduma bora za elimu kwa mwanafunzi wetu.')
    ->line('')
    ->line('Unaweza kupakua nakala ya invoice yako kwa kubofya kitufe hapa chini.')
    ->action('ANGALIA INVOICE YAKO', url('/invoices/' . $this->invoice->id))
    ->line('')
    ->line('Kama una maswali yoyote, tafadhali wasiliana nasi:')
    ->line('Simu: 0657219617')
    ->line('Barua Pepe: infos@shulenet.co.tz')
    ->line('Dodoma, Tanzania')
    ->salutation('Kwa heshima, ' . $schoolName);
        
    }

    /**
     * Convert payment method to Swahili with icons
     */
    private function getPaymentMethodInSwahili($method)
    {
        $methods = [
            'cash' => '💵 Fedha Taslimu',
            'card' => '💳 Kadi ya Benki',
            'bank_transfer' => '🏦 Uhamisho wa Benki',
            'cheque' => '📋 Cheki',
            'paypal' => '🌐 PayPal',
            'mobile_money' => '📱 Malipo ya Mkononi',
            'credit_card' => '💳 Kadi ya Mkopo'
        ];

        return $methods[$method] ?? '🔹 ' . ucfirst(str_replace('_', ' ', $method));
    }

    public function toArray($notifiable)
    {
        return [
            'invoice_id' => $this->invoice->id,
            'payment_id' => $this->payment->id,
            'amount' => $this->payment->amount,
            'method' => $this->payment->method,
            'student_name' => $this->invoice->student->name,
            'message' => 'Malipo ya TZS ' . number_format($this->payment->amount, 2) . ' yamepokelewa kikamilifu kwa ajili ya ' . $this->invoice->student->name,
            'icon' => '💰',
            'type' => 'payment_received',
            'action_url' => '/invoices/' . $this->invoice->id
        ];
    }
}