<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;

class FcmChannel
{
    public function send(object $notifiable, Notification $notification): void
    {
        $token = $notifiable->fcm_token;
        if (!$token) {
            return;
        }

        $payload = $notification->toFcm($notifiable);

        $messaging = $this->messaging();

        $message = CloudMessage::withTarget('token', $token)
            ->withNotification(FirebaseNotification::create(
                $payload['title'] ?? '',
                $payload['body'] ?? '',
            ))
            ->withData($payload['data'] ?? []);

        try {
            $messaging->send($message);
        } catch (\Throwable $e) {
            logger()->error('FCM send failed: ' . $e->getMessage(), [
                'user_id' => $notifiable->id,
                'token' => substr($token, 0, 20) . '...',
            ]);
        }
    }

    private function messaging(): \Kreait\Firebase\Messaging
    {
        $factory = (new Factory())->withServiceAccount(config('firebase.credentials'));

        return $factory->createMessaging();
    }
}
