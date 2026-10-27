@extends('layouts.admin')

@section('title', 'Detail Produk')
@section('breadcrumb', 'Detail Produk')

@section('content')
<div class="mx-auto max-w-5xl space-y-6">

    <div>
        <a href="{{ route('admin.products.index') }}"
           class="text-sm font-medium text-[#a85e67] hover:underline">
            ← Kembali ke Manajemen Produk
        </a>

        <div class="mt-3 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#49352e] sm:text-3xl">
                    Detail Produk
                </h1>
                <p class="mt-2 text-sm text-[#806d65]">
                    Informasi lengkap produk Early Cake.
                </p>
            </div>

            <a href="{{ route('admin.products.edit', $product) }}"
               class="rounded-xl bg-[#a85e67] px-5 py-3 text-center text-sm font-semibold text-white hover:bg-[#914b54]">
                Edit Produk
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

        {{-- Foto Produk --}}
        <div class="overflow-hidden rounded-2xl border border-[#f0e4dc] bg-white p-5 shadow-sm">
            <div class="flex min-h-72 items-center justify-center overflow-hidden rounded-xl bg-[#fff8f4]">
                @if ($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}"
                         alt="{{ $product->name }}"
                         class="max-h-[420px] w-full object-contain">
                @else
                    <div class="py-12 text-center">
                        <div class="text-7xl">🎂</div>
                        <p class="mt-4 text-sm text-[#806d65]">
                            Belum ada foto produk
                        </p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Informasi Produk --}}
        <div class="rounded-2xl border border-[#f0e4dc] bg-white p-5 shadow-sm sm:p-7">
            <p class="text-sm font-medium text-[#a47b69]">
                {{ $product->category?->name ?? 'Tanpa kategori' }}
            </p>

            <h2 class="mt-2 text-2xl font-bold text-[#49352e]">
                {{ $product->name }}
            </h2>

            <p class="mt-4 text-2xl font-bold text-[#a85e67]">
                Rp{{ number_format((float) $product->price, 0, ',', '.') }}
            </p>

            <div class="mt-6 space-y-4 border-t border-[#f0e4dc] pt-5">
                <div class="flex items-center justify-between gap-4">
                    <span class="text-sm text-[#806d65]">Stok</span>
                    <span class="font-semibold text-[#49352e]">
                        {{ $product->stock }} unit
                    </span>
                </div>

                <div class="flex items-center justify-between gap-4">
                    <span class="text-sm text-[#806d65]">Ketersediaan</span>
                    <span class="rounded-full px-3 py-1 text-xs font-semibold
                        {{ $product->is_available
                            ? 'bg-green-50 text-green-700'
                            : 'bg-amber-50 text-amber-700' }}">
                        {{ $product->is_available ? 'Tersedia' : 'Tidak tersedia' }}
                    </span>
                </div>

                <div class="flex items-center justify-between gap-4">
                    <span class="text-sm text-[#806d65]">Terakhir diperbarui</span>
                    <span class="text-right text-sm font-medium text-[#49352e]">
                        {{ $product->updated_at?->format('d/m/Y H:i') ?? '-' }}
                    </span>
                </div>
            </div>

            <div class="mt-6 border-t border-[#f0e4dc] pt-5">
                <h3 class="font-semibold text-[#49352e]">Deskripsi Produk</h3>

                <p class="mt-3 whitespace-pre-line text-sm leading-7 text-[#806d65]">{{ $product->description ?: 'Belum ada deskripsi untuk produk ini.' }}</p>
            </div>

            <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                <a href="{{ route('admin.products.edit', $product) }}"
                   class="flex-1 rounded-xl bg-[#a85e67] px-4 py-3 text-center text-sm font-semibold text-white hover:bg-[#914b54]">
                    Edit Produk
                </a>

                <a href="{{ route('admin.products.index') }}"
                   class="flex-1 rounded-xl border border-[#eadbd2] px-4 py-3 text-center text-sm font-semibold text-[#49352e] hover:bg-[#fff8f4]">
                    Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection