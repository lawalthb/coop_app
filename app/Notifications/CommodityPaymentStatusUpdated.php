<?php

namespace App\Notifications;

use App\Models\CommodityPayment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommodityPaymentStatusUpdated extends Notification
{
    use Queueable;

    protected $payment;

    public function __construct(CommodityPayment $payment)
    {
        $this->payment = $payment;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        $status = ucfirst($this->payment->status);
        $subscription = $this->payment->subscription;
        $commodity = $subscription->commodity->name;
        $amount = number_format($this->payment->amount, 2);

        $mailMessage = (new MailMessage)
            ->subject("Commodity Payment {$status}")
            ->greeting("Hello {$notifiable->firstname},")
            ->line("Your payment of ₦{$amount} for {$commodity} has been {$this->payment->status}.");

        if ($this->payment->status === 'approved') {
            if ($subscription->is_completed) {
                $mailMessage->line("Your payment is now complete. Thank you for your purchase!");
            } else {
                if ($subscription->payment_type === 'installment') {
                    $remaining = number_format($subscription->remaining_amount, 2);
                    $nextDate = $subscription->next_payment_date ? $subscription->next_payment_date->format('M d, Y') : 'Not scheduled';

                    $mailMessage->line("Remaining amount: ₦{$remaining}")
                               ->line("Next payment due: {$nextDate}");
                }
            }
        } elseif ($this->payment->status === 'rejected') {
            $mailMessage->line("Reason: {$this->payment->rejection_reason}")
                       ->line("Please submit a new payment with the correct information.");
        }

        return $mailMessage
            ->action('View Subscription Details', url(route('commodity-subscriptions.show', $subscription)))
            ->line('Thank you for using our cooperative services!');
    }

    public function toArray($notifiable)
    {
        $subscription = $this->payment->subscription;

        return [
            'payment_id' => $this->payment->id,
            'subscription_id' => $subscription->id,
            'commodity_id' => $subscription->commodity_id,
            'commodity_name' => $subscription->commodity->name,
            'amount' => $this->payment->amount,
            'status' => $this->payment->status,
            'message' => "Your payment of ₦" . number_format($this->payment->amount, 2) . " for {$subscription->commodity->name} has been {$this->payment->status}.",
            'rejection_reason' => $this->payment->rejection_reason,
        ];
    }
}
