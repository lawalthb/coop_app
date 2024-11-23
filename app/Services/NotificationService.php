<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    public static function notify($userId, $title, $message, $type, $data = [])
    {
        Notification::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'data' => $data
        ]);

        // Notify admins
        $admins = User::where('is_admin', true)->get();
        foreach ($admins as $admin) {
            if ($admin->id !== $userId) {
                Notification::create([
                    'user_id' => $admin->id,
                    'title' => $title,
                    'message' => $message,
                    'type' => $type,
                    'data' => $data
                ]);
            }
        }
    }
}
