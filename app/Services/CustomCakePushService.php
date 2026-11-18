<?php

namespace App\Services;

use App\Models\CustomCakeNotification;
use Illuminate\Support\Facades\Log;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;
use Throwable;
use Illuminate\Support\Facades\URL;

class CustomCakePushService
{
    public function send(CustomCakeNotification $notification): void
    {
        $customCake = $notification->customCake()
            ->with(['order', 'pushSubscriptions'])
            ->first();

        if (!$customCake) {
            return;
        }

        $subscriptions = $customCake->pushSubscriptions;

        if ($subscriptions->isEmpty()) {
            return;
        }

        $publicKey = config('services.webpush.public_key');
        $privateKey = config('services.webpush.private_key');
        $subject = config('services.webpush.subject');

        if (!$publicKey || !$privateKey || !$subject) {
            Log::warning('Konfigurasi VAPID belum lengkap.');
            return;
        }

        $orderNumber = $customCake->order?->order_number;

        if (!$orderNumber) {
            Log::warning('Nomor pesanan custom cake tidak ditemukan.', [
                'custom_cake_id' => $customCake->id,
            ]);
            return;
        }

        $statusUrl = URL::temporarySignedRoute(
        'custom-cake.notifications.status',
        now()->addDays(30),
        [
            'orderNumber' => $orderNumber,
            'customCakeId' => $customCake->id,
        ]
    );

        try {
            $webPush = new WebPush([
                'VAPID' => [
                    'subject' => $subject,
                    'publicKey' => $publicKey,
                    'privateKey' => $privateKey,
                ],
            ]);

            $payload = json_encode([
                'title' => $notification->title,
                'body' => $notification->message,
                'url' => $statusUrl,
            ]);

            foreach ($subscriptions as $item) {
                $subscription = Subscription::create([
                    'endpoint' => $item->endpoint,
                    'publicKey' => $item->public_key,
                    'authToken' => $item->auth_token,
                    'contentEncoding' => $item->content_encoding ?: 'aes128gcm',
                ]);

                $report = $webPush->sendOneNotification(
                    $subscription,
                    $payload
                );

                if ($report->isSubscriptionExpired()) {
                    $item->delete();
                }
            }
        } catch (Throwable $e) {
            Log::error('Gagal mengirim push notification Early Cake.', [
                'custom_cake_id' => $customCake->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
