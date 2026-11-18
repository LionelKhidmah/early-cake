<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class CustomCakeStatusController extends Controller
{
    /**
     * Menampilkan halaman cek status.
     */
    public function index()
    {
        return view('customer.custom-cake.check-status');
    }

    /**
     * Memverifikasi kode pengajuan dan nomor WhatsApp.
     */
    public function lookup(Request $request)
    {
        $validated = $request->validate([
            'order_number' => ['required', 'string', 'max:50'],
            'phone' => ['required', 'string', 'max:20'],
        ]);

        $order = Order::with(['customer', 'customCake'])
            ->where('order_number', strtoupper(trim($validated['order_number'])))
            ->first();

        $inputPhone = preg_replace('/\D+/', '', $validated['phone']);

        $storedPhone = $order
            ? preg_replace('/\D+/', '', $order->customer->phone ?? '')
            : '';

        if (
            !$order ||
            !$order->customCake ||
            $inputPhone !== $storedPhone
        ) {
            return back()
                ->withInput($request->only('order_number'))
                ->withErrors([
                    'lookup' => 'Kode pengajuan atau nomor WhatsApp tidak cocok.',
                ]);
        }

        $request->session()->put(
            'custom_cake_status_access.' . $order->customCake->id,
            true
        );

        return redirect()->route('custom-cake.status.show', [
            'orderNumber' => $order->order_number,
        ]);
    }

    /**
     * Menampilkan detail status pengajuan.
     */
    public function show(Request $request, string $orderNumber)
    {
        $order = Order::with(['customer', 'customCake.notifications'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        abort_unless($order->customCake, 404);

        abort_unless(
            $request->session()->get(
                'custom_cake_status_access.' . $order->customCake->id
            ),
            403
        );

        $customCake = $order->customCake;

        $notifications = $customCake->notifications()
            ->latest()
            ->get();

        return view('customer.custom-cake.status', compact(
            'order',
            'customCake',
            'notifications'
        ));
    }

    /**
     * Menyiapkan identitas pelanggan untuk desain ulang.
     */
    public function redesign(Request $request, string $orderNumber)
    {
        $order = Order::with(['customer', 'customCake'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        abort_unless($order->customCake, 404);

        abort_unless(
            $request->session()->get(
                'custom_cake_status_access.' . $order->customCake->id
            ),
            403
        );

        abort_unless(
            $order->customCake->status === 'rejected',
            403
        );

        $request->session()->put('custom_cake_redesign_prefill', [
            'name' => $order->customer->name ?? '',
            'phone' => $order->customer->phone ?? '',
            'email' => $order->customer->email ?? '',
            'address' => $order->customer->address ?? '',
        ]);

        return redirect()->route('custom-cake.index');
    }
}
