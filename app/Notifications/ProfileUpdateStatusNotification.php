<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ProfileUpdateStatusNotification extends Notification
{
    use Queueable;

    protected $profileRequest;

    public function __construct($profileRequest)
    {
        $this->profileRequest = $profileRequest;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        $status = ucfirst($this->profileRequest->status);
        $message = $this->profileRequest->status === 'approved'
            ? 'Your profile update request has been approved.'
            : 'Your profile update request has been rejected.';

        return (new MailMessage)
            ->subject("Profile Update Request {$status}")
            ->line($message)
            ->line($this->profileRequest->admin_remarks ? "Remarks: {$this->profileRequest->admin_remarks}" : '')
            ->action('View Profile', route('profile.show'));
    }

    public function toArray($notifiable)
    {
        return [
            'message' => "Your profile update request has been {$this->profileRequest->status}",
            'request_id' => $this->profileRequest->id,
            'status' => $this->profileRequest->status,
            'remarks' => $this->profileRequest->admin_remarks
        ];
    }
}
