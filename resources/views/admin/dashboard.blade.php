@extends('layouts.admin')

@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')
<div class="mx-auto max-w-7xl space-y-6">

    {{-- Heading --}}
    <section>
        <p class="text-[10px] font-semibold uppercase tracking-wider text-brand">
            {{ now()->translatedFormat('l, d F Y') }}
        </p>

        <h1 class="mt-1 text-3xl font-semibold tracking-tight">
            Selamat {{ now()->hour < 11 ? 'pagi' : (now()->hour < 15 ? 'siang' : (now()->hour < 18 ? 'sore' : 'malam')) }},
            {{ $adminFirstName ?? 'Admin' }}!
        </h1>

        <p class="mt-2 text-sm text-muted">
            Selamat datang di dashboard Early Cake.
            Pantau pesanan dan aktivitas tokomu di sini.
        </p>
    </section>

    {{-- Statistik --}}
    <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

        @php
            $stats = $stats ?? [
                [
                    'label' => 'Total Produk',
                    'value' => '—',
                    'note' => 'Data produk',
                    'icon' => 'package',
                ],
                [
                    'label' => 'Pesanan Baru',
                    'value' => '—',
                    'note' => 'Menunggu diproses',
                    'icon' => 'bag',
                ],
                [
                    'label' => 'Pesanan Selesai',
                    'value' => '—',
                    'note' => 'Pesanan terselesaikan',
                    'icon' => 'check',
                ],
                [
                    'label' => 'Pembayaran Menunggu',
                    'value' => '—',
                    'note' => 'Perlu verifikasi',
                    'icon' => 'wallet',
                ],
            ];
        @endphp

        @foreach ($stats as $stat)
            <article class="rounded-2xl bg-white p-5 shadow-md">

                <div class="flex items-center gap-2 text-muted">
                    <span class="text-brand">
                        @switch($stat['icon'] ?? '')
                            @case('package')
                                <svg class="size-5" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.6"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 7.5l-9-4.5-9 4.5m18 0l-9 4.5m9-4.5v9l-9 4.5m0-9L3 7.5m9 4.5v9M3 7.5v9l9 4.5"/>
                                </svg>
                                @break

                            @case('bag')
                                <svg class="size-5" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.6"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M6 7h12l1 13H5L6 7z"/>
                                    <path d="M9 10V6a3 3 0 016 0v4"/>
                                </svg>
                                @break

                            @case('check')
                                <svg class="size-5" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.6"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="9"/>
                                    <path d="m8 12.5 2.7 2.7L16 9.8"/>
                                </svg>
                                @break

                            @case('wallet')
                                <svg class="size-5" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.6"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="6" width="18" height="14" rx="2"/>
                                    <path d="M16 14h2M7 6V4h11v2"/>
                                </svg>
                                @break
                        @endswitch
                    </span>

                    <span class="text-xs">
                        {{ $stat['label'] }}
                    </span>
                </div>

                <p class="mt-5 text-3xl font-semibold tracking-tight">
                    {{ $stat['value'] }}
                </p>

                <p class="mt-3 text-[11px] text-muted">
                    {{ $stat['note'] }}
                </p>
            </article>
        @endforeach
    </section>

    {{-- Grafik penjualan dan aktivitas --}}
    <section class="grid grid-cols-1 gap-5 xl:grid-cols-3">

        <article class="rounded-2xl bg-white p-5 shadow-md xl:col-span-2">
            <div class="flex flex-wrap items-start justify-between gap-2">
                <div>
                    <h2 class="text-base font-semibold">
                        Penjualan Early Cake
                    </h2>
                    <p class="mt-1 text-xs text-muted">
                        Ringkasan pendapatan selama tujuh hari terakhir
                    </p>
                </div>

                <span class="rounded-full bg-cream px-3 py-1 text-[10px] text-muted">
                    7 hari terakhir
                </span>
            </div>

            <div class="mt-6 h-64">
                <canvas
                    id="sales-chart"
                    data-labels='@json($salesChart["labels"] ?? [])'
                    data-values='@json($salesChart["values"] ?? [])'>
                </canvas>

                <div id="sales-chart-empty"
                    class="hidden flex h-full items-center justify-center text-center text-sm text-muted">
                    Belum ada data penjualan untuk ditampilkan.
                </div>
            </div>

            <div class="mt-5 flex items-center justify-between border-t border-line pt-4">
                <span class="text-sm text-muted">
                    Total pendapatan
                </span>

                <span class="text-base font-semibold">
                    Rp{{ number_format($totalRevenue ?? 0, 0, ',', '.') }}
                </span>
            </div>
        </article>

        <article class="rounded-2xl bg-white p-5 shadow-md">
            <h2 class="text-base font-semibold">
                Aktivitas Terbaru
            </h2>

            <p class="mt-1 text-xs text-muted">
                Informasi aktivitas toko
            </p>

            @if (!empty($activities))
                <ul class="mt-5 space-y-5">
                    @foreach ($activities as $activity)
                        <li class="border-l-2 border-rose-200 pl-3">
                            <p class="text-[10px] text-brand">
                                {{ $activity['time'] ?? '' }}
                            </p>

                            <p class="mt-1 text-sm font-medium">
                                {{ $activity['title'] ?? 'Aktivitas toko' }}
                            </p>

                            <p class="mt-1 text-xs leading-relaxed text-muted">
                                {{ $activity['detail'] ?? '' }}
                            </p>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="mt-6 rounded-lg bg-cream p-4 text-xs leading-relaxed text-muted">
                    Aktivitas terbaru akan muncul di sini setelah data tersedia.
                </div>
            @endif
        </article>
    </section>

    {{-- Pesanan terbaru --}}
    <section class="rounded-2xl bg-white p-5 shadow-md">

        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-base font-semibold">
                    Pesanan Terbaru
                </h2>

                <p class="mt-1 text-xs text-muted">
                    Pantau pesanan pelanggan Early Cake
                </p>
            </div>

            @if (\Illuminate\Support\Facades\Route::has('admin.orders.index'))
                <a href="{{ route('admin.orders.index') }}"
                    class="rounded-lg  px-4 py-2 text-xs font-medium transition hover:bg-cream">
                    Kelola Pesanan →
                </a>
            @endif
        </div>

        <div class="mt-5 overflow-x-auto">
            <table class="w-full min-w-[600px] text-left text-xs">

                <thead>
                    <tr class="bg-cream text-[10px] uppercase tracking-wider text-muted">
                        <th class="rounded-l-lg px-4 py-3 font-medium">
                            Nomor Pesanan
                        </th>
                        <th class="px-4 py-3 font-medium">
                            Pelanggan
                        </th>
                        <th class="px-4 py-3 font-medium">
                            Total
                        </th>
                        <th class="rounded-r-lg px-4 py-3 font-medium">
                            Status
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @forelse (($orders ?? []) as $order)
                        @php
                            $orderStatus = $order['status'] ?? 'Menunggu';
                            $orderNumber = $order['number'] ?? '-';
                            $customerName = $order['customer'] ?? '-';
                            $orderTotal = $order['total'] ?? 0;
                        @endphp

                        <tr class="border-b border-line last:border-b-0">
                            <td class="px-4 py-4 font-medium">
                                {{ $orderNumber }}
                            </td>

                            <td class="px-4 py-4">
                                {{ $customerName }}
                            </td>

                            <td class="px-4 py-4">
                                Rp{{ number_format((float) $orderTotal, 0, ',', '.') }}
                            </td>

                            <td class="px-4 py-4">
                                <span class="rounded-full bg-cream px-3 py-1 text-[10px]">
                                    {{ $orderStatus }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-10 text-center text-muted">
                                Belum ada pesanan yang dapat ditampilkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    {{-- Konfirmasi logout --}}
    <section
        id="konfirmasi-logout"
        class="scroll-mt-20 rounded-2xl  bg-white p-5 shadow-md">

        <p class="text-[10px] font-semibold uppercase tracking-wider text-brand">
            Pengaturan Akun
        </p>

        <h2 class="mt-2 text-xl font-semibold">
            Selesai mengelola toko?
        </h2>

        <p class="mt-2 text-sm leading-relaxed text-muted">
            Kamu akan keluar dari akun
            {{ $adminFullName ?? 'Admin Early Cake' }}.
            Pastikan perubahan yang diperlukan sudah disimpan.
        </p>

        <div class="mt-5 flex flex-wrap gap-3">
            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button
                    type="submit"
                    class="rounded-lg bg-rose-kue-dark px-5 py-2.5 text-xs font-semibold text-white transition hover:bg-cream">
                    Ya, Logout
                </button>
            </form>

            <a
                href="{{ route('admin.dashboard') }}"
                class="rounded-lg border border-line px-5 py-2.5 text-xs font-medium transition hover:bg-cream">
                Batal
            </a>
        </div>
    </section>

</div>
@endsection