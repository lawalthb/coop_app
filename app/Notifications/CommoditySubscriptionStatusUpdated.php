<?php

namespace App\Notifications;

use App\Models\CommoditySubscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommoditySubscriptionStatusUpdated extends Notification
{
    use Queueable;

    protected $subscription;

    public function __construct(CommoditySubscription $subscription)
    {
        $this->subscription = $subscription;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        $status = ucfirst($this->subscription->status);
        $commodity = $this->subscription->commodity->name;

        $mailMessage = (new MailMessage)
            ->subject("Commodity Subscription {$status}")
            ->greeting("Hello {$notifiable->firstname},")
            ->line("Your subscription for {$commodity} has been {$this->subscription->status}.");

        if ($this->subscription->status === 'approved') {
            $mailMessage->line("You can now proceed with payment for your subscription.");

            if ($this->subscription->payment_type === 'installment') {
                $mailMessage->line("Initial deposit required: ₦" . number_format($this->subscription->initial_deposit, 2));
                $mailMessage->line("Monthly installment: ₦" . number_format($this->subscription->monthly_amount, 2));
                $mailMessage->line("Installment period: {$this->subscription->installment_months} months");
            } else {
                $mailMessage->line("Total amount: ₦" . number_format($this->subscription->total_amount, 2));
            }
        } elseif ($this->subscription->status === 'rejected') {
            $mailMessage->line("Reason: {$this->subscription->admin_notes}");
        }

        return $mailMessage
            ->action('View Subscription Details', url(route('commodity-subscriptions.show', $this->subscription)))
            ->line('Thank you for using our cooperative services!');
    }

    public function toArray($notifiable)
    {
        return [
            'subscription_id' => $this->subscription->id,
            'commodity_id' => $this->subscription->commodity_id,
            'commodity_name' => $this->subscription->commodity->name,
            'status' => $this->subscription->status,
            'message' => "Your subscription for {$this->subscription->commodity->name} has been {$this->subscription->status}.",
            'admin_notes' => $this->subscription->admin_notes,
        ];
    }
}
