<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
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

        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {


        return (new MailMessage)
            ->subject('Loan Guarantor Request')




            ->line('You have been requested to be a guarantor for a loan.')
            ->action('View Request', route('member.loans.show', $this->loan))
            ->line('Please review and respond to this request.');
    }

    public function toArray($notifiable)
    {
        return [
            'loan_id' => $this->loan->id,



            'message' => 'You have been requested to be a guarantor for a loan',
            'action_url' => route('member.loans.show', $this->loan)
        ];
    }
}
