@extends('layouts.admin')

@section('title', 'Detail Kue Kustom')
@section('breadcrumb', 'Kue Kustom / Detail')

@section('content')
@php
    $config = $customCake->design_config;

    if (is_string($config)) {
        $config = json_decode($config, true);
    }

    if (!is_array($config)) {
        $config = [];
    }

    $safeColor = fn ($color, $fallback) =>
        is_string($color) && preg_match('/^#[0-9a-fA-F]{6}$/', $color)
            ? strtoupper($color)
            : $fallback;

    $topColor = $safeColor(
        $config['top_color'] ?? $config['cake_color'] ?? $customCake->color ?? null,
        '#F4A6B7'
    );
    $sideColor = $safeColor($config['side_color'] ?? null, '#D88FA1');
    $messageColor = $safeColor($config['message_color'] ?? null, '#4A2C27');

    $message = (string) ($config['message'] ?? $customCake->message ?? '');

    $decorations = $config['decorations'] ?? $customCake->decorations ?? [];

    if (!is_array($decorations)) {
        $decorations = [];
    }

    $decorations = array_values(array_map('strval', array_filter($decorations, 'is_scalar')));

    $shape = $config['shape'] ?? $customCake->shape ?? 'Bulat';

    if (!in_array($shape, ['Bulat', 'Persegi', 'Hati'], true)) {
        $shape = 'Bulat';
    }

    $fontSize = isset($config['message_font_size']) && is_numeric($config['message_font_size'])
        ? max(8, min(28, (int) $config['message_font_size']))
        : null;

    $position = is_array($config['message_position_top'] ?? null)
        ? $config['message_position_top']
        : [];

    $previewConfig = [
        'shape' => $shape,
        'top_color' => $topColor,
        'side_color' => $sideColor,
        'message_color' => $messageColor,
        'message' => $message,
        'decorations' => $decorations,
        'message_font_size' => $fontSize,
        'message_font_size_default' => is_numeric($config['message_font_size_default'] ?? null)
            ? (int) $config['message_font_size_default']
            : null,
        'message_position_top' => [
            'x' => is_numeric($position['x'] ?? null) ? (float) $position['x'] : 0,
            'y' => is_numeric($position['y'] ?? null) ? (float) $position['y'] : 0,
        ],
    ];

    $previewJson = json_encode($previewConfig, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    $statusLabels = [
        'pending' => 'Menunggu',
        'approved' => 'Disetujui',
        'rejected' => 'Ditolak',
        'completed' => 'Selesai',
    ];

    $statusClasses = [
        'pending' => 'bg-amber-50 text-amber-700',
        'approved' => 'bg-emerald-50 text-emerald-700',
        'rejected' => 'bg-red-50 text-red-700',
        'completed' => 'bg-gray-100 text-gray-600',
    ];
@endphp

<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <a href="{{ route('admin.custom-cakes.index') }}"
               class="text-sm font-medium text-[#B75C78] hover:underline">
                ← Kembali ke daftar
            </a>

            <h1 class="mt-2 text-2xl font-bold text-[#403431]">
                Detail Pengajuan Kue Kustom
            </h1>

            <p class="mt-1 text-sm text-[#968681]">
                {{ $customCake->order?->order_number ?? 'Nomor pesanan tidak tersedia' }}
            </p>
        </div>

        <span class="inline-flex rounded-full px-4 py-2 text-sm font-semibold {{ $statusClasses[$customCake->status] ?? 'bg-gray-100 text-gray-600' }}">
            {{ $statusLabels[$customCake->status] ?? ucfirst($customCake->status) }}
        </span>
    </div>

    {{-- INFORMASI PELANGGAN --}}
    <section class="rounded-2xl bg-white p-5 shadow-sm sm:p-6">
        <h2 class="font-bold text-[#403431]">Informasi Pelanggan</h2>

        <div class="mt-5 grid gap-5 sm:grid-cols-2">
            <div>
                <p class="text-xs text-[#968681]">Nama pelanggan</p>
                <p class="mt-1 font-semibold text-[#403431]">
                    {{ $customCake->order?->customer?->name ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs text-[#968681]">Nomor WhatsApp</p>
                <p class="mt-1 font-semibold text-[#403431]">
                    {{ $customCake->order?->customer?->phone ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs text-[#968681]">Email</p>
                <p class="mt-1 font-semibold text-[#403431]">
                    {{ $customCake->order?->customer?->email ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs text-[#968681]">Tanggal pengajuan</p>
                <p class="mt-1 font-semibold text-[#403431]">
                    {{ $customCake->created_at?->format('d M Y, H:i') ?? '-' }}
                </p>
            </div>

            <div class="sm:col-span-2">
                <p class="text-xs text-[#968681]">Alamat</p>
                <p class="mt-1 text-sm leading-6 text-[#403431]">
                    {{ $customCake->order?->customer?->address ?? $customCake->order?->delivery_address ?? '-' }}
                </p>
            </div>
        </div>
    </section>

    {{-- PREVIEW DESAIN --}}
    <section class="rounded-2xl bg-white p-5 shadow-sm sm:p-6">
        <div>
            <h2 class="font-bold text-[#403431]">Preview Desain Kue</h2>
            <p class="mt-1 text-sm text-[#968681]">
                Tampilan yang sama persis dengan yang dilihat pelanggan saat mendesain.
            </p>
        </div>

        <div class="mt-5 grid gap-5 lg:grid-cols-2">
            <div class="rounded-2xl bg-[#FFF9F5] p-4">
                <p class="mb-3 text-center text-xs font-bold uppercase tracking-widest text-[#B75C78]">
                    Tampak Depan
                </p>

                <div data-cake-preview="front"
                     data-config="{{ $previewJson }}"
                     class="mx-auto flex aspect-[400/274] w-full max-w-[420px] items-center justify-center">
                    <p class="text-center text-sm text-[#968681]">Memuat preview...</p>
                </div>
            </div>

            <div class="rounded-2xl bg-[#FFF9F5] p-4">
                <p class="mb-3 text-center text-xs font-bold uppercase tracking-widest text-[#B75C78]">
                    Tampak Atas
                </p>

                <div data-cake-preview="top"
                     data-config="{{ $previewJson }}"
                     class="mx-auto flex aspect-[400/274] w-full max-w-[420px] items-center justify-center">
                    <p class="text-center text-sm text-[#968681]">Memuat preview...</p>
                </div>
            </div>
        </div>

        <noscript>
            <p class="mt-3 text-xs text-red-500">
                Preview desain memerlukan JavaScript. Lihat detail desain pada bagian Spesifikasi di bawah.
            </p>
        </noscript>

        <p class="mt-3 text-xs leading-5 text-[#A3948F]">
            Preview berupa ilustrasi 2D, hasil akhir dapat sedikit berbeda dari ilustrasi.
        </p>
    </section>

    {{-- SPESIFIKASI --}}
    <section class="rounded-2xl bg-white p-5 shadow-sm sm:p-6">
        <h2 class="font-bold text-[#403431]">Spesifikasi Desain</h2>

        <div class="mt-5 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            <div>
                <p class="text-xs text-[#968681]">Jenis kue</p>
                <p class="mt-1 font-semibold text-[#403431]">{{ $customCake->cake_type }}</p>
            </div>

            <div>
                <p class="text-xs text-[#968681]">Ukuran</p>
                <p class="mt-1 font-semibold text-[#403431]">{{ $customCake->size }}</p>
            </div>

            <div>
                <p class="text-xs text-[#968681]">Rasa</p>
                <p class="mt-1 font-semibold text-[#403431]">{{ $customCake->flavor }}</p>
            </div>

            <div>
                <p class="text-xs text-[#968681]">Bentuk</p>
                <p class="mt-1 font-semibold text-[#403431]">{{ $shape }}</p>
            </div>

            <div>
                <p class="text-xs text-[#968681]">Warna atas</p>
                <div class="mt-2 flex items-center gap-2">
                    <span class="size-5 rounded-full border border-black/10"
                          style="background: {{ $topColor }}"></span>
                    <span class="text-sm font-medium text-[#403431]">{{ $topColor }}</span>
                </div>
            </div>

            <div>
                <p class="text-xs text-[#968681]">Warna samping</p>
                <div class="mt-2 flex items-center gap-2">
                    <span class="size-5 rounded-full border border-black/10"
                          style="background: {{ $sideColor }}"></span>
                    <span class="text-sm font-medium text-[#403431]">{{ $sideColor }}</span>
                </div>
            </div>

            <div class="sm:col-span-2 lg:col-span-3">
                <p class="text-xs text-[#968681]">Dekorasi</p>
                @if (count($decorations))
                    <div class="mt-2 flex flex-wrap gap-2">
                        @foreach ($decorations as $decoration)
                            <span class="rounded-full bg-[#FCE8ED] px-3 py-1.5 text-xs font-medium text-[#A84D6A]">
                                {{ $decoration }}
                            </span>
                        @endforeach
                    </div>
                @else
                    <p class="mt-1 text-sm text-[#968681]">Tidak ada dekorasi tambahan.</p>
                @endif
            </div>

            <div class="sm:col-span-2">
                <p class="text-xs text-[#968681]">Tulisan pada kue</p>
                <p class="mt-1 whitespace-pre-line font-serif text-lg font-semibold text-[#403431]">
                    {{ $message !== '' ? $message : 'Tidak ada tulisan' }}
                </p>
            </div>

            <div>
                <p class="text-xs text-[#968681]">Warna &amp; ukuran tulisan</p>
                <div class="mt-2 flex items-center gap-2">
                    <span class="size-5 rounded-full border border-black/10"
                          style="background: {{ $messageColor }}"></span>
                    <span class="text-sm font-medium text-[#403431]">
                        {{ $messageColor }} &middot; {{ $fontSize ? $fontSize . ' px' : 'ukuran bawaan' }}
                    </span>
                </div>
            </div>

            <div>
                <p class="text-xs text-[#968681]">Tema</p>
                <p class="mt-1 text-sm text-[#403431]">{{ $customCake->theme ?: '-' }}</p>
            </div>

            <div class="sm:col-span-2">
                <p class="text-xs text-[#968681]">Deskripsi tambahan</p>
                <p class="mt-1 whitespace-pre-line text-sm leading-6 text-[#403431]">
                    {{ $customCake->description ?: '-' }}
                </p>
            </div>
        </div>
    </section>

    {{-- FOTO REFERENSI --}}
    <section class="rounded-2xl bg-white p-5 shadow-sm sm:p-6">
        <h2 class="font-bold text-[#403431]">Foto Referensi</h2>

        @if ($customCake->reference_image)
            @php
                $referenceImageUrl = asset('storage/' . $customCake->reference_image);
            @endphp

            <a href="{{ $referenceImageUrl }}"
               target="_blank"
               rel="noopener noreferrer"
               class="mt-4 block w-fit">

                <img
                    src="{{ $referenceImageUrl }}"
                    alt="Foto referensi kue dari pelanggan"
                    class="max-h-[360px] max-w-full rounded-xl border border-[#F4E9E9] object-contain"
                    onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"
                >

                <p class="mt-2 hidden text-sm text-red-500">
                    Foto gagal dimuat. Periksa lokasi file penyimpanan.
                </p>
            </a>
        @else
            <p class="mt-3 text-sm text-[#968681]">
                Pelanggan tidak mengunggah foto referensi.
            </p>
        @endif
    </section>

    {{-- HARGA --}}
    <section class="rounded-2xl bg-white p-5 shadow-sm sm:p-6">
        <h2 class="font-bold text-[#403431]">Penentuan Harga</h2>

        <form action="{{ route('admin.custom-cakes.update-price', $customCake) }}"
              method="POST"
              class="mt-5 space-y-4">
            @csrf
            @method('PATCH')

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="estimated_price" class="mb-2 block text-sm font-medium text-[#6F5C56]">
                        Estimasi Harga (Rp)
                    </label>

                    <input id="estimated_price"
                           type="number"
                           name="estimated_price"
                           min="0"
                           step="1000"
                           value="{{ old('estimated_price', $customCake->estimated_price) }}"
                           class="w-full rounded-xl border border-[#EEDDE2] px-4 py-3 text-sm outline-none focus:border-[#B75C78] focus:ring-2 focus:ring-[#FCE8ED]">

                    @error('estimated_price')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="final_price" class="mb-2 block text-sm font-medium text-[#6F5C56]">
                        Harga Final (Rp)
                    </label>

                    <input id="final_price"
                           type="number"
                           name="final_price"
                           min="0"
                           step="1000"
                           required
                           value="{{ old('final_price', $customCake->final_price) }}"
                           class="w-full rounded-xl border border-[#EEDDE2] px-4 py-3 text-sm outline-none focus:border-[#B75C78] focus:ring-2 focus:ring-[#FCE8ED]">

                    @error('final_price')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            @if (!in_array($customCake->status, ['rejected', 'completed']))
                <button type="submit"
                        class="rounded-xl bg-[#B75C78] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#A84D6A]">
                    Simpan Harga
                </button>
            @else
                <p class="text-sm text-[#968681]">
                    Harga tidak dapat diubah karena pengajuan berstatus
                    {{ $statusLabels[$customCake->status] ?? $customCake->status }}.
                </p>
            @endif
        </form>
    </section>

    {{-- STATUS DAN ALASAN PENOLAKAN --}}
    <section class="rounded-2xl bg-white p-5 shadow-sm sm:p-6">
        <h2 class="font-bold text-[#403431]">Status Pengajuan</h2>

        @if ($customCake->status === 'rejected' && $customCake->rejection_reason)
            <div class="mt-4 rounded-xl border border-red-100 bg-red-50 p-4">
                <p class="text-sm font-semibold text-red-700">
                    Alasan Penolakan
                </p>

                <p class="mt-2 whitespace-pre-line text-sm leading-6 text-red-600">{{ $customCake->rejection_reason }}</p>
            </div>
        @endif

        <form action="{{ route('admin.custom-cakes.update-status', $customCake) }}"
              method="POST"
              class="mt-5 space-y-4">
            @csrf
            @method('PATCH')

            <div class="w-full sm:max-w-xs">
                <label for="status" class="mb-2 block text-sm font-medium text-[#6F5C56]">
                    Ubah Status
                </label>

                <select id="status"
                        name="status"
                        class="w-full rounded-xl border border-[#EEDDE2] px-4 py-3 text-sm outline-none focus:border-[#B75C78] focus:ring-2 focus:ring-[#FCE8ED]">
                    @foreach ($statusLabels as $value => $label)
                        <option value="{{ $value }}"
                            @selected(old('status', $customCake->status) === $value)>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>

                @error('status')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div id="rejection-reason-wrapper"
                 class="{{ old('status', $customCake->status) === 'rejected' ? '' : 'hidden' }}">

                <label for="rejection_reason"
                       class="mb-2 block text-sm font-medium text-[#6F5C56]">
                    Alasan Penolakan
                    <span class="text-red-500">*</span>
                </label>

                <textarea
                    id="rejection_reason"
                    name="rejection_reason"
                    rows="4"
                    maxlength="1000"
                    placeholder="Tuliskan alasan pengajuan custom cake ditolak..."
                    class="w-full rounded-xl border border-[#EEDDE2] px-4 py-3 text-sm outline-none focus:border-[#B75C78] focus:ring-2 focus:ring-[#FCE8ED]"
                >{{ old('rejection_reason', $customCake->rejection_reason) }}</textarea>

                @error('rejection_reason')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror

                <p class="mt-1 text-xs text-[#968681]">
                    Alasan ini akan ditampilkan kepada pelanggan.
                </p>
            </div>

            <button type="submit"
                    class="rounded-xl bg-[#FCE8ED] px-5 py-3 text-sm font-semibold text-[#A84D6A] transition hover:bg-[#F8D9E2]">
                Perbarui Status
            </button>
        </form>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const statusSelect = document.getElementById('status');
    const reasonWrapper = document.getElementById('rejection-reason-wrapper');
    const reasonInput = document.getElementById('rejection_reason');

    if (!statusSelect || !reasonWrapper || !reasonInput) {
        return;
    }

    function toggleRejectionReason() {
        const isRejected = statusSelect.value === 'rejected';

        reasonWrapper.classList.toggle('hidden', !isRejected);
        reasonInput.required = isRejected;
    }

    statusSelect.addEventListener('change', toggleRejectionReason);

    toggleRejectionReason();
});
</script>

{{--
    Renderer preview yang sama dengan halaman customer.
    cake-builder.js mendeteksi elemen [data-cake-preview] lalu menggambarnya
    dari design_config.
--}}
@vite('resources/js/cake-builder.js')
@endsection
