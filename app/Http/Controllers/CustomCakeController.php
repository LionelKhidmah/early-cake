<?php

namespace App\Http\Controllers;

use App\Models\CustomCake;
use App\Models\CustomCakeOption;
use App\Models\CustomCakeNotification;
use App\Models\Order;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CustomCakeController extends Controller
{
    /**
     * Menampilkan halaman pengajuan custom cake.
     */
    public function index()
    {
        $options = CustomCakeOption::active()
            ->get()
            ->groupBy('option_type');

        // Ambil identitas sementara untuk pengajuan ulang.
        $prefill = session()->pull('custom_cake_redesign_prefill', []);

        return view(
            'customer.custom-cake.index',
            compact('options', 'prefill')
        );
    }

    /**
     * Menyimpan pengajuan custom cake dari pelanggan.
     */
    public function store(Request $request)
    {
        // Ubah design_config dari JSON string menjadi array.
        $designConfig = $request->input('design_config');

        if (is_string($designConfig) && $designConfig !== '') {
            $decodedConfig = json_decode($designConfig, true);

            if (!is_array($decodedConfig)) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'design_config' => 'Konfigurasi desain kue tidak valid. Silakan periksa kembali desainmu.',
                    ]);
            }

            $request->merge([
                'design_config' => $decodedConfig,
            ]);
        }

        $validated = $request->validate([
            // Identitas pelanggan
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:100'],
            'address' => ['required', 'string', 'max:255'],

            // Spesifikasi kue
            'cake_type' => ['required', 'string', 'max:100'],
            'size' => ['required', 'string', 'max:50'],
            'flavor' => ['required', 'string', 'max:100'],
            'shape' => ['nullable', 'string', 'max:50'],
            'color' => ['nullable', 'string', 'max:50'],

            // Konfigurasi desain
            'decorations' => ['nullable', 'array'],
            'decorations.*' => ['string', 'max:100'],
            'design_config' => ['nullable', 'array'],

            // Informasi tambahan
            'theme' => ['nullable', 'string', 'max:150'],
            'message' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'reference_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        // Buat token akses khusus untuk halaman notifikasi.
        $notificationToken = Str::random(64);

        $order = DB::transaction(function () use (
            $request,
            $validated,
            $notificationToken
        ) {
            // Simpan data pelanggan.
            $customer = Customer::create([
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'email' => $validated['email'] ?? null,
                'address' => $validated['address'],
            ]);

            // Buat data pesanan baru.
            $order = Order::create([
                'customer_id' => $customer->id,
                'order_number' => 'EC-' . Str::upper(Str::random(10)),
                'order_date' => now(),
                'delivery_address' => $validated['address'],
                'notes' => 'Pengajuan custom cake. Menunggu peninjauan admin.',
                'total_amount' => 0,
                'status' => 'pending',
            ]);

            // Upload foto referensi jika tersedia.
            $imagePath = null;

            if ($request->hasFile('reference_image')) {
                $imagePath = $request->file('reference_image')
                    ->store('custom-cakes', 'public');
            }

            // Simpan pengajuan custom cake baru.
            $customCake = CustomCake::create([
                'order_id' => $order->id,
                'cake_type' => $validated['cake_type'],
                'size' => $validated['size'],
                'flavor' => $validated['flavor'],
                'shape' => $validated['shape'] ?? null,
                'color' => $validated['color'] ?? null,
                'decorations' => $validated['decorations'] ?? [],
                'design_config' => $validated['design_config'] ?? null,
                'theme' => $validated['theme'] ?? null,
                'message' => $validated['message'] ?? null,
                'reference_image' => $imagePath,
                'description' => $validated['description'] ?? null,
                'estimated_price' => null,
                'final_price' => null,
                'status' => 'pending',

                // Simpan hash token, bukan token aslinya.
                'notification_token_hash' => hash(
                    'sha256',
                    $notificationToken
                ),
            ]);

            // Buat notifikasi pertama untuk pelanggan.
            CustomCakeNotification::create([
                'custom_cake_id' => $customCake->id,
                'title' => 'Pengajuan Berhasil Dikirim',
                'message' => 'Terima kasih! Pengajuan custom cake kamu berhasil diterima. Admin akan meninjau desain dan menentukan harga pesananmu.',
                'type' => 'submission',
                'is_read' => false,
            ]);

            return $order;
        });

        // Arahkan pelanggan ke halaman notifikasi custom cake.
        return redirect()
            ->route('custom-cake.notifications', [
                'token' => $notificationToken,
            ])
            ->with('success', 'Pengajuan custom cake berhasil dikirim!');
    }
}
