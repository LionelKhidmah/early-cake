<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderTrackingController extends Controller
{
    public function index()
    {
        return view('customer.orders.track');
    }

    public function track(Request $request)
    {
        $validated = $request->validate([
            'order_code' => ['required', 'string', 'max:100'],
        ]);

        $order = Order::where(
            'order_code',
            $validated['order_code']
        )->first();

        if (!$order) {
            return back()
                ->withInput()
                ->with('error', 'Kode pesanan tidak ditemukan.');
        }

        return view('customer.orders.track', compact('order'));
    }
}
