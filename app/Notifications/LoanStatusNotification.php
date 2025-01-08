<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class LoanStatusNotification extends Notification
{
    use Queueable;

    protected $loan;

    public function __construct($loan)
    {
        $this->loan = $loan;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Loan Application Status Update')
            ->line('Your loan application status has been updated to: ' . $this->loan->status)
            ->action('View Loan Details', route('member.loans.show', $this->loan->id))
            ->line('Thank you for using our cooperative services.');
    }

    public function toArray($notifiable)
    {
        return [
            'loan_id' => $this->loan->id,
            'status' => $this->loan->status,
            'amount' => $this->loan->amount,
            'message' => 'Your loan application status has been updated'
        ];
    }
}
