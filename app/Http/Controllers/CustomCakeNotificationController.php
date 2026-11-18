<?php

namespace App\Http\Controllers;

use App\Models\CustomCake;
use App\Models\CustomCakeNotification;
use App\Models\CustomCakePushSubscription;
use Illuminate\Http\Request;
use App\Models\Order;

class CustomCakeNotificationController extends Controller
{
    /**
     * Menampilkan halaman notifikasi pelanggan.
     */
    public function index(string $token)
    {
        $customCake = CustomCake::with('order.customer')
            ->where('notification_token_hash', hash('sha256', $token))
            ->firstOrFail();

        $notifications = $customCake->notifications()
            ->latest()
            ->paginate(10);

        return view('customer.custom-cake.notifications', compact(
            'customCake',
            'notifications',
            'token'
        ));
    }

    /**
     * Menandai notifikasi sebagai sudah dibaca.
     */
    public function markAsRead(
        string $token,
        CustomCakeNotification $notification
    ) {
        $customCake = CustomCake::where(
            'notification_token_hash',
            hash('sha256', $token)
        )->firstOrFail();

        abort_unless(
            (int) $notification->custom_cake_id === (int) $customCake->id,
            404
        );

        $notification->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return back()->with(
            'success',
            'Notifikasi ditandai sudah dibaca.'
        );
    }

    /**
     * Mendaftarkan perangkat untuk push notification.
     */
    public function subscribe(Request $request, string $token)
    {
        $customCake = CustomCake::where(
            'notification_token_hash',
            hash('sha256', $token)
        )->firstOrFail();

        $validated = $request->validate([
            'endpoint' => ['required', 'url', 'max:2000'],
            'keys.p256dh' => ['required', 'string'],
            'keys.auth' => ['required', 'string'],
            'contentEncoding' => ['nullable', 'string'],
        ]);

        $endpointHash = hash(
            'sha256',
            $validated['endpoint']
        );

        $customCake->pushSubscriptions()->updateOrCreate(
            [
                'endpoint_hash' => $endpointHash,
            ],
            [
                'endpoint' => $validated['endpoint'],
                'endpoint_hash' => $endpointHash,
                'public_key' => $validated['keys']['p256dh'],
                'auth_token' => $validated['keys']['auth'],
                'content_encoding' => $validated['contentEncoding'] ?? 'aes128gcm',
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Perangkat berhasil didaftarkan.',
        ]);
    }
            /**
         * Membuka halaman status melalui signed URL.
         */
        public function openStatus(
            Request $request,
            string $orderNumber,
            int $customCakeId
        ) {
            $order = Order::with('customCake')
                ->where('order_number', $orderNumber)
                ->firstOrFail();

            abort_unless(
                $order->customCake &&
                (int) $order->customCake->id === $customCakeId,
                404
            );

            $request->session()->put(
                'custom_cake_status_access.' . $customCakeId,
                true
            );

            return redirect()->route('custom-cake.status.show', [
                'orderNumber' => $order->order_number,
            ]);
        }
}
