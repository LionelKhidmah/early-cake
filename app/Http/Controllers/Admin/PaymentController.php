<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Menampilkan daftar pembayaran.
     */
    public function index()
    {
        $payments = Payment::with('order.customer')
            ->latest()
            ->paginate(10);

        return view('admin.payments.index', compact('payments'));
    }

    /**
     * Memperbarui status pembayaran.
     */
    public function updateStatus(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'payment_status' => [
                'required',
                'in:pending,paid,failed',
            ],
        ]);

        // Pembayaran yang sudah lunas tidak boleh diubah
        // kembali tanpa proses koreksi tersendiri.
        if ($payment->payment_status === 'paid') {
            return back()->with(
                'error',
                'Pembayaran sudah lunas dan tidak dapat diubah melalui fitur ini.'
            );
        }

        $status = $validated['payment_status'];

        $payment->update([
            'payment_status' => $status,
            'paid_at' => $status === 'paid' ? now() : null,
        ]);

        return back()->with(
            'success',
            'Status pembayaran berhasil diperbarui.'
        );
    }
}

