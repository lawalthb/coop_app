<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProfileUpdateRequestNotification extends Notification
{
    use Queueable;

    protected $updateRequest;

    public function __construct($updateRequest)
    {
        $this->updateRequest = $updateRequest;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => 'New profile update request from ' . $this->updateRequest->user->firstname,
            'request_id' => $this->updateRequest->id
        ];
    }
}
