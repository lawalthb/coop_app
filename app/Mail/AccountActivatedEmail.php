<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccountActivatedEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $member;

    public function __construct(User $member)
    {
        $this->member = $member;
    }

    public function build()
    {
        return $this->subject('Your OGITECH COOP Account has been Activated')
                    ->markdown('emails.account-activated');
    }
}
