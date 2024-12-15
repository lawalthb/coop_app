<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class LoanGuarantorRequest extends Notification
{
    use Queueable;

    protected $loan;

    public function __construct($loan)
    {
        $this->loan = $loan;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $url = route('member.guarantor.respond', $this->loan->id);

        return (new MailMessage)
            ->subject('Loan Guarantor Request')
            ->line('You have been selected as a guarantor for a loan application.')
            ->line("Loan Amount: ₦" . number_format($this->loan->amount, 2))
            ->line("Duration: {$this->loan->duration} months")
            ->action('Respond to Request', $url)
            ->line('Please review and respond to this request.');
    }

    public function toArray($notifiable)
    {
        return [
            'loan_id' => $this->loan->id,
            'borrower_name' => $this->loan->user->name,
            'amount' => $this->loan->amount,
            'duration' => $this->loan->duration
        ];
    }
}
