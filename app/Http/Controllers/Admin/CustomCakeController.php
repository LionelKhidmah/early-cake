<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomCake;
use Illuminate\Http\Request;

class CustomCakeController extends Controller
{
    /**
     * Menampilkan daftar pengajuan custom cake.
     */
    public function index()
    {
        $customCakes = CustomCake::with('order.customer')
            ->latest()
            ->paginate(10);

        return view('admin.custom-cakes.index', compact('customCakes'));
    }

    /**
     * Menampilkan detail pengajuan custom cake.
     */
    public function show(CustomCake $customCake)
    {
        $customCake->load('order.customer');

        return view('admin.custom-cakes.show', compact('customCake'));
    }

    /**
     * Menentukan harga custom cake.
     */
    public function updatePrice(Request $request, CustomCake $customCake)
    {
        if (in_array($customCake->status, ['rejected', 'completed'])) {
            return back()->with('error', 'Pengajuan ini tidak dapat diubah harganya.');
        }

        $validated = $request->validate([
            'estimated_price' => ['nullable', 'numeric', 'min:0'],
            'final_price' => ['required', 'numeric', 'min:0'],
        ]);

        $customCake->update([
            'estimated_price' => $validated['estimated_price'] ?? null,
            'final_price' => $validated['final_price'],
            'status' => 'reviewed',
        ]);

        return back()->with('success', 'Harga custom cake berhasil diperbarui.');
    }

    /**
     * Memperbarui status pengajuan custom cake.
     */
    public function updateStatus(Request $request, CustomCake $customCake)
    {
        $validated = $request->validate([
            'status' => [
                'required',
                'in:pending,reviewed,approved,rejected,completed',
            ],
        ]);

        $customCake->update([
            'status' => $validated['status'],
        ]);

        return back()->with('success', 'Status custom cake berhasil diperbarui.');
    }
}

