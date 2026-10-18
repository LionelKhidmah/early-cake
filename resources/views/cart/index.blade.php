@extends('layouts.customer')

@section('title', 'Keranjang Belanja — Early Cake')

@section('content')

<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">{{-- Judul halaman --}}
<div class="mb-8">
    <a href="{{ route('products.index') }}"
        class="inline-flex items-center gap-2 text-sm font-medium text-rose-700 transition hover:text-rose-900">
        <span aria-hidden="true">&larr;</span>
        Kembali ke Menu Produk
    </a>

    <h1 class="mt-4 text-3xl font-bold text-choco sm:text-4xl">
        Keranjang Belanja
    </h1>

    <p class="mt-2 text-sm leading-6 text-gray-600">
        Periksa kembali produk dan jumlah pesananmu sebelum melanjutkan ke checkout.
    </p>
</div>

{{-- Notifikasi berhasil --}}
@if (session('success'))
    <div role="status" class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
        {{ session('success') }}
    </div>
@endif

{{-- Notifikasi kesalahan --}}
@if ($errors->any())
    <div role="alert" class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
        <p class="font-semibold">Ada yang perlu diperiksa:</p>
        <ul class="mt-2 list-inside list-disc">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (count($lines) === 0)

    {{-- Keranjang kosong --}}
    <div class="rounded-3xl border border-rose-200 bg-white px-6 py-14 text-center shadow-sm">
        <div class="mx-auto flex size-20 items-center justify-center rounded-full bg-rose-50 text-4xl" aria-hidden="true">
            🛒
        </div>

        <h2 class="mt-5 text-xl font-bold text-choco">
            Keranjangmu masih kosong
        </h2>

        <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-gray-600">
            Yuk, pilih kue favoritmu dari menu Early Cake dan tambahkan ke keranjang.
        </p>

        <a href="{{ route('products.index') }}"
            class="mt-6 inline-flex items-center justify-center rounded-xl border-2 border-rose-700 bg-rose-700 px-6 py-3 text-sm font-semibold text-white transition hover:bg-rose-800">
            Lihat Menu Produk
        </a>
    </div>

@else

    <div class="grid items-start gap-8 lg:grid-cols-[1fr_350px]">

        {{-- Daftar produk --}}
        <div class="space-y-4">

            @foreach ($lines as $line)
                @php
                    $product = $line['product'];
                @endphp

                <article class="rounded-2xl border border-rose-200 bg-white p-4 shadow-sm sm:p-5">
                    <div class="flex flex-col gap-4 sm:flex-row">

                        {{-- Foto produk --}}
                        <a href="{{ route('products.show', $product) }}"
                            class="block aspect-square w-full shrink-0 overflow-hidden rounded-xl bg-rose-50 sm:size-32"
                            aria-label="Lihat detail {{ $product->name }}">
                            <x-product-image :product="$product" class="size-full object-cover" />
                        </a>

                        {{-- Informasi produk --}}
                        <div class="flex min-w-0 flex-1 flex-col">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div>
                                    <p class="text-xs font-medium text-rose-700">
                                        {{ $product->category?->name ?? 'Kue pilihan' }}
                                    </p>

                                    <h2 class="mt-1 text-lg font-semibold text-choco">
                                        <a href="{{ route('products.show', $product) }}"
                                            class="transition hover:text-rose-700">
                                            {{ $product->name }}
                                        </a>
                                    </h2>

                                    <p class="mt-2 text-sm font-semibold text-rose-700">
                                        Rp{{ number_format((float) $product->price, 0, ',', '.') }}
                                    </p>
                                </div>

                                <form method="POST" action="{{ route('cart.remove') }}" class="js-cart-remove-form">
    				@csrf
    				<input type="hidden" name="key" value="{{ $line['key'] }}">
					<button type="submit" class="rounded-lg border border-rose-200 px-3 py-2 text-xs font-semibold text-rose-700 transition hover:border-rose-400 hover:bg-rose-50">
        					Hapus
    					</button>
				</form>
                            </div>

                            <div class="mt-4 flex flex-wrap items-end justify-between gap-4">
                                {{-- Ubah jumlah --}}
                                <form method="POST" action="{{ route('cart.update') }}" class="js-cart-update-form">
    				@csrf

    				<input type="hidden" name="key" value="{{ $line['key'] }}">

    			   <div class="inline-flex items-center overflow-hidden rounded-xl border border-rose-200 bg-white">
        			{{-- Tombol kurang --}}
        		   <button type="button" data-quantity-minus
            		 	class="flex h-10 w-10 items-center justify-center text-lg font-semibold text-rose-700 transition hover:bg-rose-50 disabled:cursor-not-allowed disabled:opacity-40"
            				aria-label="Kurangi jumlah">
           					 −
        		   </button>

        			{{-- Jumlah produk --}}
        		<input
            			type="number"
            			name="quantity"
           			min="1"
            			max="{{ min($maxQuantity, $line['product']->stock) }}"
            			value="{{ $line['quantity'] }}"
            			data-cart-quantity
            			data-saved-value="{{ $line['quantity'] }}"
            			required
            			class="h-10 w-12 appearance-none border-0 bg-white p-0 text-center text-sm font-semibold text-choco focus:outline-none focus:ring-0">

        			{{-- Tombol tambah --}}
        		<button type="button" data-quantity-plus
            			class="flex h-10 w-10 items-center justify-center text-lg font-semibold text-rose-700 transition hover:bg-rose-50 disabled:cursor-not-allowed disabled:opacity-40"
            				aria-label="Tambah jumlah">
            				+
        		</button>
    		</div>
	</form>

                                {{-- Subtotal --}}
                                <div class="text-left sm:text-right">
                                    <p class="text-xs text-gray-500">Subtotal</p>
                                    <p id="cart-subtotal-{{ $line['key']}}" class="mt-1 text-base font-bold text-choco">
                                        Rp{{ number_format($line['subtotal'], 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>

                            <p class="mt-3 text-xs text-gray-500">
                                Stok tersedia: {{ $product->stock }}
                            </p>
                        </div>
                    </div>
                </article>
            @endforeach

        </div>

        {{-- Ringkasan belanja --}}
        <aside class="rounded-2xl border border-rose-200 bg-white p-5 shadow-sm lg:sticky lg:top-24">
            <h2 class="text-lg font-bold text-choco">
                Ringkasan Belanja
            </h2>

            <div class="mt-5 flex items-center justify-between gap-3 text-sm text-gray-600">
                <span>Jumlah jenis produk</span>
                <span class="font-medium text-gray-800">{{ count($lines) }} produk</span>
            </div>

            <div class="mt-4 flex items-start justify-between gap-3 text-sm text-gray-600">
                <span>Ongkos kirim</span>
                <span class="text-right">Belum termasuk</span>
            </div>

            <div class="my-5 border-t border-dashed border-rose-200"></div>

            <div class="flex items-start justify-between gap-3">
                <span class="font-semibold text-choco">Total sementara</span>
                <span id="cart-total" class="text-right text-xl font-bold text-rose-700">
                    Rp{{ number_format($total, 0, ',', '.') }}
                </span>
            </div>

            <p class="mt-3 text-xs leading-5 text-gray-500">
                Total akhir dan ongkos kirim akan dikonfirmasi sesuai detail pesanan.
            </p>

            <a href="{{ route('checkout.index') }}"
                class="mt-6 flex w-full items-center justify-center rounded-xl border-2 border-rose-700 bg-rose-700 px-4 py-3 text-sm font-semibold text-white transition hover:bg-rose-800 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-rose-700">
                Lanjut ke Checkout
            </a>

            <a href="{{ route('products.index') }}"
                class="mt-3 flex w-full items-center justify-center rounded-xl border-2 border-rose-200 bg-white px-4 py-3 text-sm font-semibold text-rose-800 transition hover:border-rose-400 hover:bg-rose-50">
                Tambah Produk Lain
            </a>
        </aside>

    </div>

@endif

</section>
@endsection