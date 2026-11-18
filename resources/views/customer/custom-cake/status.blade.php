@extends('layouts.customer')

@section('title', 'Status Pengajuan Custom Cake — Early Cake')

@section('content')
@php
    $statusInfo = [
        'pending' => [
            'label' => 'Menunggu',
            'description' => 'Pengajuan kamu sudah diterima dan sedang menunggu pemeriksaan dari admin.',
            'class' => 'bg-amber-100 text-amber-700',
        ],
        'approved' => [
            'label' => 'Disetujui',
            'description' => 'Kabar baik! Pengajuan custom cake kamu telah disetujui.',
            'class' => 'bg-green-100 text-green-700',
        ],
        'rejected' => [
            'label' => 'Ditolak',
            'description' => 'Maaf, pengajuan custom cake kamu belum dapat kami terima.',
            'class' => 'bg-red-100 text-red-700',
        ],
        'completed' => [
            'label' => 'Selesai',
            'description' => 'Pesanan custom cake kamu telah selesai diproses.',
            'class' => 'bg-purple-100 text-purple-700',
        ],
    ];

    $currentStatus = $statusInfo[$customCake->status] ?? [
        'label' => 'Status Tidak Dikenal',
        'description' => 'Silakan hubungi admin untuk informasi lebih lanjut.',
        'class' => 'bg-gray-100 text-gray-700',
    ];

    $designConfig = $customCake->design_config ?? [];

    if (is_string($designConfig)) {
        $designConfig = json_decode($designConfig, true) ?? [];
    }

    if (!is_array($designConfig)) {
        $designConfig = [];
    }
@endphp

<section class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">

    <a href="{{ route('custom-cake.status.check') }}"
       class="mb-6 inline-flex items-center gap-2 text-sm font-medium text-rose-700 hover:text-rose-900">
        ← Kembali ke cek status
    </a>

    <div class="mb-8">
        <p class="text-sm font-semibold uppercase tracking-wider text-rose-600">
            Early Cake
        </p>

        <h1 class="mt-2 text-3xl font-bold text-gray-900">
            Status Pengajuan Custom Cake
        </h1>

        <p class="mt-2 text-gray-600">
            Pantau perkembangan pengajuan dan informasi custom cake kamu di sini.
        </p>
    </div>

    {{-- STATUS --}}
    <div class="mb-8 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">

        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <p class="text-sm text-gray-500">Kode Pengajuan</p>

                <p class="mt-1 text-xl font-bold tracking-wide text-gray-900">
                    {{ $order->order_number }}
                </p>
            </div>

            <span class="inline-flex w-fit rounded-full px-4 py-2 text-sm font-semibold {{ $currentStatus['class'] }}">
                {{ $currentStatus['label'] }}
            </span>
        </div>

        <div class="mt-5 rounded-xl bg-gray-50 p-4">
            <p class="text-sm leading-relaxed text-gray-700">
                {{ $currentStatus['description'] }}
            </p>
        </div>

        {{-- ALASAN PENOLAKAN --}}
        @if ($customCake->status === 'rejected')
            <div class="mt-4 rounded-xl border border-red-100 bg-red-50 p-4">

                <p class="text-sm font-semibold text-red-700">
                    Alasan Penolakan
                </p>

                <p class="mt-2 whitespace-pre-line text-sm leading-6 text-red-600">
                    {{ $customCake->rejection_reason ?: 'Belum ada keterangan dari admin.' }}
                </p>

                <div class="mt-5 border-t border-red-200 pt-4">

                    <p class="text-sm leading-6 text-gray-600">
                        Kamu masih bisa membuat desain baru dengan menyesuaikan
                        masukan dari admin.
                    </p>

                    {{-- TOMBOL DESAIN ULANG --}}
                    <form action="{{ route('custom-cake.redesign', $order->order_number) }}"
                          method="POST"
                          class="mt-4">
                        @csrf

                        <button type="submit"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-rose-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-rose-600 sm:w-auto">
                            <span>Desain Ulang Custom Cake</span>
                            <span>→</span>
                        </button>
                    </form>

                </div>
            </div>
        @endif

    </div>

    {{-- PREVIEW DESAIN KUE --}}
    <div class="mb-8 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">

        <div class="mb-5">
            <h2 class="text-lg font-bold text-gray-900">
                Preview Desain Kue
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Berikut gambaran desain kue berdasarkan pilihan yang kamu kirim.
            </p>
        </div>

        @if (!empty($designConfig))

            <div class="grid gap-5 sm:grid-cols-2">

                {{-- TAMPAK DEPAN --}}
                <div class="overflow-hidden rounded-xl border border-gray-100 bg-[#fffaf5] p-4">

                    <div class="mb-3 text-center">
                        <p class="text-sm font-semibold text-gray-800">
                            Tampak Depan
                        </p>

                        <p class="mt-1 text-xs text-gray-500">
                            Tampilan bagian samping kue
                        </p>
                    </div>

                    <div
                        data-cake-preview="front"
                        data-config="{{ json_encode($designConfig, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_HEX_TAG) }}"
                        class="flex min-h-[240px] items-center justify-center"
                    ></div>

                </div>

                {{-- TAMPAK ATAS --}}
                <div class="overflow-hidden rounded-xl border border-gray-100 bg-[#fffaf5] p-4">

                    <div class="mb-3 text-center">
                        <p class="text-sm font-semibold text-gray-800">
                            Tampak Atas
                        </p>

                        <p class="mt-1 text-xs text-gray-500">
                            Tampilan bagian atas kue
                        </p>
                    </div>

                    <div
                        data-cake-preview="top"
                        data-config="{{ json_encode($designConfig, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_HEX_TAG) }}"
                        class="flex min-h-[240px] items-center justify-center"
                    ></div>

                </div>

            </div>

        @else

            <div class="rounded-xl bg-gray-50 px-5 py-8 text-center">
                <p class="text-sm text-gray-500">
                    Preview desain belum tersedia untuk pengajuan ini.
                </p>
            </div>

        @endif

    </div>

    {{-- INFORMASI PENGAJUAN --}}
    <div class="mb-8 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">

        <h2 class="text-lg font-bold text-gray-900">
            Informasi Pengajuan
        </h2>

        <div class="mt-5 grid gap-5 sm:grid-cols-2">

            <div>
                <p class="text-sm text-gray-500">Nama Pemesan</p>
                <p class="mt-1 font-medium text-gray-900">
                    {{ $order->customer->name ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Tanggal Pengajuan</p>
                <p class="mt-1 font-medium text-gray-900">
                    {{ $customCake->created_at?->format('d M Y, H:i') ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Jenis Kue</p>
                <p class="mt-1 font-medium text-gray-900">
                    {{ $customCake->cake_type ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Ukuran</p>
                <p class="mt-1 font-medium text-gray-900">
                    {{ $customCake->size ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Rasa</p>
                <p class="mt-1 font-medium text-gray-900">
                    {{ $customCake->flavor ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Bentuk</p>
                <p class="mt-1 font-medium text-gray-900">
                    {{ $customCake->shape ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Warna</p>

                <div class="mt-1 flex items-center gap-2">

                    @if ($customCake->color)
                        <span
                            class="h-5 w-5 rounded-full border border-gray-200"
                            style="background-color: {{ $customCake->color }}"
                        ></span>
                    @endif

                    <span class="font-medium text-gray-900">
                        {{ $customCake->color ?? '-' }}
                    </span>

                </div>
            </div>

            <div>
                <p class="text-sm text-gray-500">Tema</p>
                <p class="mt-1 font-medium text-gray-900">
                    {{ $customCake->theme ?? '-' }}
                </p>
            </div>

        </div>

        @if ($customCake->message)
            <div class="mt-5">
                <p class="text-sm text-gray-500">Tulisan pada Kue</p>

                <p class="mt-1 whitespace-pre-line font-medium text-gray-900">
                    {{ $customCake->message }}
                </p>
            </div>
        @endif

        @if ($customCake->description)
            <div class="mt-5">
                <p class="text-sm text-gray-500">Deskripsi Tambahan</p>

                <p class="mt-1 whitespace-pre-line text-gray-900">
                    {{ $customCake->description }}
                </p>
            </div>
        @endif

        @if ($customCake->reference_image)
            <div class="mt-6">

                <p class="mb-3 text-sm text-gray-500">
                    Foto Referensi
                </p>

                <img
                    src="{{ Storage::url($customCake->reference_image) }}"
                    alt="Foto referensi custom cake"
                    class="max-h-80 rounded-xl border border-gray-100 object-contain"
                >

            </div>
        @endif

    </div>

    {{-- INFORMASI HARGA --}}
    <div class="mb-8 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">

        <h2 class="text-lg font-bold text-gray-900">
            Informasi Harga
        </h2>

        <div class="mt-5 grid gap-5 sm:grid-cols-2">

            <div>
                <p class="text-sm text-gray-500">Estimasi Harga</p>

                <p class="mt-1 text-lg font-semibold text-gray-900">
                    {{ $customCake->estimated_price !== null
                        ? 'Rp ' . number_format($customCake->estimated_price, 0, ',', '.')
                        : 'Belum tersedia' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Harga Final</p>

                <p class="mt-1 text-lg font-semibold {{ $customCake->status === 'approved' ? 'text-rose-700' : 'text-gray-900' }}">
                    {{ $customCake->final_price !== null
                        ? 'Rp ' . number_format($customCake->final_price, 0, ',', '.')
                        : 'Belum ditentukan' }}
                </p>
            </div>

        </div>

        {{-- CHECKOUT JIKA DISETUJUI --}}
        @if ($customCake->status === 'approved')

            <div class="mt-6 rounded-2xl border border-emerald-100 bg-emerald-50 p-5">

                <h3 class="text-base font-semibold text-emerald-800">
                    Pengajuan Disetujui!
                </h3>

                <p class="mt-2 text-sm leading-6 text-emerald-700">
                    Custom cake kamu sudah disetujui oleh admin.
                    Silakan lanjutkan ke proses checkout.
                </p>

                @if ($customCake->final_price !== null)
                    <div class="mt-4">

                        <p class="text-sm text-emerald-700">
                            Harga final
                        </p>

                        <p class="text-2xl font-bold text-emerald-800">
                            Rp{{ number_format($customCake->final_price, 0, ',', '.') }}
                        </p>

                    </div>
                @endif

                <a href="{{ route('checkout.index') }}"
                   class="mt-5 inline-flex w-full items-center justify-center rounded-xl bg-rose-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-rose-600">

                    Lanjut ke Checkout
                    <span class="ml-2">→</span>

                </a>

                <p class="mt-2 text-xs text-emerald-700">
                    Integrasi detail pesanan custom cake ke checkout akan dilanjutkan.
                </p>

            </div>

        @endif

    </div>

    {{-- RIWAYAT NOTIFIKASI --}}
    <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">

        <h2 class="text-lg font-bold text-gray-900">
            Riwayat Pembaruan
        </h2>

        @forelse ($notifications as $notification)

            <div class="border-b border-gray-100 py-4 last:border-0">

                <p class="font-semibold text-gray-900">
                    {{ $notification->title }}
                </p>

                <p class="mt-1 text-sm leading-relaxed text-gray-600">
                    {{ $notification->message }}
                </p>

                <p class="mt-2 text-xs text-gray-400">
                    {{ $notification->created_at?->format('d M Y, H:i') }}
                </p>

            </div>

        @empty

            <p class="mt-4 text-sm text-gray-500">
                Belum ada pembaruan untuk pengajuan ini.
            </p>

        @endforelse

    </div>

</section>
@endsection

@push('scripts')
    @vite('resources/js/cake-builder.js')
@endpush
