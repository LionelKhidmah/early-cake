@extends('layouts.admin')

@section('title', 'Detail Promo')
@section('breadcrumb', 'Detail Promo')

@section('content')
<div class="mx-auto max-w-5xl space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <a href="{{ route('admin.promos.index') }}"
               class="text-sm font-semibold text-[#B75C78] hover:underline">
                ← Kembali ke Manajemen Promo
            </a>

            <h1 class="mt-4 text-2xl font-bold text-[#49352E]">
                Detail Promo
            </h1>

            <p class="mt-2 text-sm text-[#806D65]">
                Informasi lengkap promo Early Cake.
            </p>
        </div>

        <a href="{{ route('admin.promos.edit', $promo) }}"
           class="inline-flex justify-center rounded-xl bg-[#B75C78] px-5 py-3 text-sm font-semibold text-white hover:bg-[#9F4866]">
            Edit Promo
        </a>
    </div>

    {{-- INFORMASI UTAMA --}}
    <div class="overflow-hidden rounded-2xl border border-rose-100 bg-white shadow-sm">

        @if ($promo->image)
            <img src="{{ asset('storage/' . $promo->image) }}"
                 alt="{{ $promo->title }}"
                 class="aspect-[16/7] w-full object-cover">
        @else
            <div class="flex aspect-[16/7] items-center justify-center bg-[#FFF1F4] text-7xl">
                🎂
            </div>
        @endif

        <div class="p-6 sm:p-8">

            <div class="flex flex-wrap items-center gap-3">
                @if ($promo->badge)
                    <span class="rounded-full bg-rose-100 px-3 py-1 text-xs font-semibold text-[#B75C78]">
                        {{ $promo->badge }}
                    </span>
                @endif

                <span class="rounded-full px-3 py-1 text-xs font-semibold
                    {{ $promo->isCurrentlyActive()
                        ? 'bg-green-100 text-green-700'
                        : 'bg-gray-100 text-gray-600' }}">
                    {{ $promo->isCurrentlyActive() ? 'Aktif' : 'Tidak Aktif' }}
                </span>
            </div>

            <h2 class="mt-5 text-2xl font-bold text-[#49352E]">
                {{ $promo->title }}
            </h2>

            <p class="mt-3 whitespace-pre-line text-sm leading-relaxed text-[#806D65]">
                {{ $promo->description }}
            </p>
        </div>
    </div>

    {{-- DETAIL DISKON --}}
    <div class="rounded-2xl border border-rose-100 bg-white p-6 shadow-sm sm:p-8">

        <h3 class="text-lg font-bold text-[#49352E]">
            Informasi Diskon
        </h3>

        <dl class="mt-6 grid gap-6 sm:grid-cols-2">

            <div>
                <dt class="text-xs text-[#806D65]">Kode Promo</dt>
                <dd class="mt-2 text-xl font-bold text-[#B75C78]">
                    {{ $promo->code }}
                </dd>
            </div>

            <div>
                <dt class="text-xs text-[#806D65]">Jenis Diskon</dt>
                <dd class="mt-2 font-semibold text-[#49352E]">
                    {{ $promo->discount_type === 'percentage'
                        ? 'Persentase'
                        : 'Nominal' }}
                </dd>
            </div>

            <div>
                <dt class="text-xs text-[#806D65]">Nilai Diskon</dt>
                <dd class="mt-2 font-semibold text-[#49352E]">
                    @if ($promo->discount_type === 'percentage')
                        {{ number_format($promo->discount_value, 0) }}%
                    @else
                        Rp{{ number_format($promo->discount_value, 0, ',', '.') }}
                    @endif
                </dd>
            </div>

            <div>
                <dt class="text-xs text-[#806D65]">Maksimal Potongan</dt>
                <dd class="mt-2 font-semibold text-[#49352E]">
                    {{ $promo->max_discount !== null
                        ? 'Rp' . number_format($promo->max_discount, 0, ',', '.')
                        : 'Tidak dibatasi' }}
                </dd>
            </div>

            <div>
                <dt class="text-xs text-[#806D65]">Minimal Pembelian</dt>
                <dd class="mt-2 font-semibold text-[#49352E]">
                    Rp{{ number_format($promo->min_purchase, 0, ',', '.') }}
                </dd>
            </div>

            <div>
                <dt class="text-xs text-[#806D65]">Batas Penggunaan</dt>
                <dd class="mt-2 font-semibold text-[#49352E]">
                    {{ $promo->usage_limit ?? 'Tidak dibatasi' }}
                </dd>
            </div>

            <div>
                <dt class="text-xs text-[#806D65]">Sudah Digunakan</dt>
                <dd class="mt-2 font-semibold text-[#49352E]">
                    {{ $promo->used_count }} kali
                </dd>
            </div>

            <div>
                <dt class="text-xs text-[#806D65]">Khusus Pesanan Pertama</dt>
                <dd class="mt-2 font-semibold text-[#49352E]">
                    {{ $promo->first_order_only ? 'Ya' : 'Tidak' }}
                </dd>
            </div>

            <div>
                <dt class="text-xs text-[#806D65]">Tanggal Mulai</dt>
                <dd class="mt-2 font-semibold text-[#49352E]">
                    {{ $promo->starts_at->format('d M Y, H:i') }}
                </dd>
            </div>

            <div>
                <dt class="text-xs text-[#806D65]">Tanggal Berakhir</dt>
                <dd class="mt-2 font-semibold text-[#49352E]">
                    {{ $promo->ends_at->format('d M Y, H:i') }}
                </dd>
            </div>
        </dl>
    </div>

    {{-- SYARAT DAN KETENTUAN --}}
    <div class="rounded-2xl border border-rose-100 bg-white p-6 shadow-sm sm:p-8">

        <h3 class="text-lg font-bold text-[#49352E]">
            Syarat & Ketentuan
        </h3>

        @if (!empty($promo->terms))
            <ul class="mt-5 list-disc space-y-3 pl-5 text-sm leading-relaxed text-[#806D65]">
                @foreach ($promo->terms as $term)
                    <li>{{ $term }}</li>
                @endforeach
            </ul>
        @else
            <p class="mt-4 text-sm text-[#806D65]">
                Belum ada syarat dan ketentuan tambahan.
            </p>
        @endif
    </div>

    {{-- AKSI --}}
    <div class="flex flex-wrap justify-end gap-3">

        <a href="{{ route('admin.promos.index') }}"
           class="rounded-xl border border-rose-200 bg-white px-5 py-3 text-sm font-semibold text-[#806D65] hover:bg-rose-50">
            Kembali
        </a>

        <a href="{{ route('admin.promos.edit', $promo) }}"
           class="rounded-xl bg-[#B75C78] px-5 py-3 text-sm font-semibold text-white hover:bg-[#9F4866]">
            Edit Promo
        </a>
    </div>

</div>
@endsection
