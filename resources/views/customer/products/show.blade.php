@extends('layouts.customer')

@section('title', $product->name . ' — Early Cake')

@section('description', 'Lihat detail ' . $product->name . ' dari Early Cake.')

@section('content')

<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">{{-- Tombol kembali --}}
<a href="{{ route('products.index') }}"
    class="inline-flex items-center gap-2 text-sm font-medium text-rose-700 transition hover:text-rose-900">
    <span aria-hidden="true">&larr;</span>
    Kembali ke Menu Produk
</a>

{{-- Notifikasi berhasil --}}
@if (session('success'))
    <div role="status"
        class="mt-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
        {{ session('success') }}
    </div>
@endif

{{-- Notifikasi kesalahan --}}
@if ($errors->any())
    <div role="alert"
        class="mt-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
        <p class="font-semibold">Ada yang perlu diperiksa:</p>
        <ul class="mt-2 list-inside list-disc">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="mt-6 grid items-start gap-8 md:grid-cols-2 md:gap-12">

    {{-- Foto produk --}}
    <div class="overflow-hidden rounded-3xl border border-rose-200 bg-white shadow-sm">
        <div class="aspect-[4/5] bg-rose-50">
            <x-product-image :product="$product" class="size-full object-cover" />
        </div>
    </div>

    {{-- Informasi produk --}}
    <div class="flex flex-col justify-center">

        @if ($product->category)
            <p class="text-sm font-semibold uppercase tracking-wider text-rose-700">
                {{ $product->category->name }}
            </p>
        @endif

        <h1 class="mt-3 text-3xl font-bold leading-tight text-choco sm:text-4xl">
            {{ $product->name }}
        </h1>

        <p class="mt-4 text-2xl font-bold text-rose-700">
            Rp{{ number_format((float) $product->price, 0, ',', '.') }}
        </p>

        {{-- Deskripsi --}}
        <div class="mt-6">
            <h2 class="text-base font-semibold text-choco">
                Deskripsi Produk
            </h2>

            <p class="mt-2 whitespace-pre-line text-sm leading-7 text-gray-600">
                {{ $product->description ?: 'Kue spesial dari Early Cake yang dibuat untuk menemani momen istimewamu.' }}
            </p>
        </div>

        {{-- Informasi stok --}}
        <div class="mt-5 rounded-xl border border-rose-100 bg-rose-50/70 p-4">
            <p class="text-sm font-semibold text-choco">
                Ketersediaan Produk
            </p>

            @if ($product->is_available && $product->stock > 0)
                <p class="mt-1 text-sm text-green-700">
                    Tersedia — {{ $product->stock }} produk
                </p>
            @else
                <p class="mt-1 text-sm font-medium text-rose-700">
                    Stok sedang habis atau produk tidak tersedia.
                </p>
            @endif
        </div>

        {{-- Form tambah ke keranjang --}}
        @if ($product->is_available && $product->stock > 0)
            <form action="{{ route('cart.add') }}" method="POST"
                class="mt-6 rounded-2xl border border-rose-200 bg-white p-5 shadow-sm">
                @csrf

                <input type="hidden" name="product_id" value="{{ $product->id }}">

                <label for="quantity"
                    class="mb-2 block text-sm font-semibold text-choco">
                    Jumlah Produk
                </label>

                <input
                    type="number"
                    id="quantity"
                    name="quantity"
                    value="{{ old('quantity', 1) }}"
                    min="1"
                    max="{{ min(20, $product->stock) }}"
                    required
                    class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm text-gray-800 focus:border-rose-500 focus:outline-none focus:ring-2 focus:ring-rose-200"
                >

                <p class="mt-2 text-xs leading-5 text-gray-500">
                    Maksimal 20 produk per jenis dalam keranjang dan mengikuti stok yang tersedia.
                </p>

                <button type="submit"
                    class="mt-4 flex w-full items-center justify-center gap-2 rounded-xl border-2 border-rose-700 bg-rose-700 px-5 py-3 font-semibold text-white transition hover:border-rose-800 hover:bg-rose-800 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-rose-700">
                    <span aria-hidden="true">🛒</span>
                    Tambah ke Keranjang
                </button>
            </form>
        @else
            <button type="button" disabled
                class="mt-6 w-full cursor-not-allowed rounded-xl border-2 border-gray-200 bg-gray-100 px-5 py-3 font-semibold text-gray-400">
                Produk Tidak Tersedia
            </button>
        @endif

        {{-- Tombol lainnya --}}
        <div class="mt-4 grid gap-3 sm:grid-cols-2">
            <a href="{{ route('custom-cake.index') }}"
                class="flex items-center justify-center rounded-xl border-2 border-rose-300 bg-white px-4 py-3 text-center text-sm font-semibold text-rose-800 transition hover:border-rose-500 hover:bg-rose-50">
                Pesan Kue Custom
            </a>

            <a href="{{ route('products.index') }}"
                class="flex items-center justify-center rounded-xl border-2 border-gray-200 bg-white px-4 py-3 text-center text-sm font-semibold text-gray-700 transition hover:border-rose-300 hover:bg-rose-50">
                Lihat Produk Lain
            </a>
        </div>

        <p class="mt-5 text-xs leading-6 text-gray-500">
            Harga produk belum termasuk ongkos kirim. Ketersediaan produk mengikuti stok yang tercatat di Early Cake.
        </p>

    </div>
</div>

</section>
@endsection