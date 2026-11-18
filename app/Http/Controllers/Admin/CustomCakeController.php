<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomCake;
use App\Models\CustomCakeNotification;
use App\Services\CustomCakePushService;
use Illuminate\Http\Request;

class CustomCakeController extends Controller
{
    public function index()
    {
        $customCakes = CustomCake::with('order.customer')
            ->latest()
            ->paginate(10);

        return view('admin.custom-cakes.index', compact('customCakes'));
    }

    public function show(CustomCake $customCake)
    {
        $customCake->load('order.customer');

        return view('admin.custom-cakes.show', compact('customCake'));
    }

    public function updatePrice(Request $request, CustomCake $customCake)
    {
        if (in_array($customCake->status, ['rejected', 'completed'])) {
            return back()->with(
                'error',
                'Pengajuan ini tidak dapat diubah harganya.'
            );
        }

        $validated = $request->validate([
            'estimated_price' => ['nullable', 'numeric', 'min:0'],
            'final_price' => ['required', 'numeric', 'min:0'],
        ]);

        $oldPrice = $customCake->final_price;
        $newPrice = (float) $validated['final_price'];

        $priceChanged = $oldPrice === null
            || (float) $oldPrice !== $newPrice;

        $customCake->update([
            'estimated_price' => $validated['estimated_price'] ?? null,
            'final_price' => $newPrice,
        ]);

        if ($priceChanged) {
            CustomCakeNotification::create([
                'custom_cake_id' => $customCake->id,
                'title' => 'Harga Custom Cake Diperbarui',
                'message' => 'Admin telah memperbarui harga custom cake kamu menjadi Rp'
                    . number_format($newPrice, 0, ',', '.')
                    . '. Silakan periksa kembali detail pengajuanmu.',
                'type' => 'price',
            ]);
        }

        return back()->with(
            'success',
            'Harga custom cake berhasil diperbarui.'
        );
    }

    public function updateStatus(Request $request, CustomCake $customCake)
    {
        $validated = $request->validate([
            'status' => [
                'required',
                'in:pending,approved,rejected,completed',
            ],
            'rejection_reason' => [
                'required_if:status,rejected',
                'nullable',
                'string',
                'max:1000',
            ],
        ], [
            'rejection_reason.required_if' =>
                'Alasan penolakan wajib diisi.',
            'rejection_reason.max' =>
                'Alasan penolakan maksimal 1000 karakter.',
        ]);

        $oldStatus = $customCake->status;
        $newStatus = $validated['status'];

        if ($oldStatus === $newStatus) {
            return back()->with(
                'success',
                'Status pengajuan tidak mengalami perubahan.'
            );
        }

        $rejectionReason = $newStatus === 'rejected'
            ? trim($validated['rejection_reason'] ?? '')
            : null;

        if ($newStatus === 'rejected' && $rejectionReason === '') {
            return back()->withErrors([
                'rejection_reason' => 'Alasan penolakan wajib diisi.',
            ]);
        }

        $customCake->update([
            'status' => $newStatus,
            'rejection_reason' => $rejectionReason,
        ]);

        $statusLabels = [
            'pending' => 'Menunggu',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'completed' => 'Selesai',
        ];

        $statusMessages = [
            'pending' => 'Pengajuan custom cake kamu sedang menunggu pemeriksaan admin.',
            'approved' => 'Kabar baik! Pengajuan custom cake kamu telah disetujui.',
            'rejected' => 'Mohon maaf, pengajuan custom cake kamu ditolak. Alasan: '
                . $rejectionReason,
            'completed' => 'Custom cake kamu telah selesai diproses.',
        ];

        $notification = CustomCakeNotification::create([
            'custom_cake_id' => $customCake->id,
            'title' => 'Status Custom Cake: ' . $statusLabels[$newStatus],
            'message' => $statusMessages[$newStatus],
            'type' => 'status',
        ]);

        app(CustomCakePushService::class)->send($notification);

        return back()->with(
            'success',
            'Status custom cake berhasil diperbarui.'
        );
    }

    public function destroy(CustomCake $customCake)
    {
        $customCake->delete();

        return redirect()
            ->route('admin.custom-cakes.index')
            ->with('success', 'Pengajuan custom cake berhasil dihapus.');
    }
}
