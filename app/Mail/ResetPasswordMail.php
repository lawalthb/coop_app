<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $url;

    public function __construct($token)
    {
        $this->url = url(route('password.reset', [
            'token' => $token,
            'email' => request()->email,
        ], false));
    }

    public function build()
    {
        return $this->markdown('emails.reset-password')
            ->subject('Reset Password Notification')
            ->with([
                'url' => $this->url,
            ]);
    }
}
