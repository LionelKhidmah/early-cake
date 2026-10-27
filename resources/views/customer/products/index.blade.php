@extends('layouts.customer')

@section('title', 'Menu Produk — Early Cake')

@section('description', 'Temukan pilihan kue Early Cake yang dibuat fresh untuk momen spesialmu.')

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">

        {{-- Header menu --}}
        <header class="max-w-2xl">
            <p class="text-sm font-semibold uppercase tracking-widest text-rose-700">
                Dari Dapur Early Cake
            </p>

            <h1 class="mt-3 text-3xl font-bold tracking-tight text-choco sm:text-5xl">
                Temukan Kue Favoritmu
            </h1>

            <p class="mt-4 text-sm leading-7 text-gray-600 sm:text-base">
                Pilihan kue spesial yang dibuat dengan sepenuh hati
                untuk menemani setiap cerita dan momen bahagiamu.
            </p>
        </header>

        {{-- Pencarian, kategori, dan pengurutan --}}
        <form
            method="GET"
            action="{{ route('products.index') }}"
            class="mt-8 grid gap-4 rounded-2xl border border-rose-100 bg-white p-4 shadow-sm sm:grid-cols-2 lg:grid-cols-4"
        >
            <div class="lg:col-span-2">
                <label for="search" class="mb-2 block text-sm font-medium text-choco">
                    Cari kue favorit
                </label>

                <input
                    id="search"
                    type="search"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nama atau rasa kue..."
                    class="w-full rounded-xl border border-rose-200 bg-white px-4 py-3 text-sm outline-none focus:border-rose-400 focus:ring-2 focus:ring-rose-100"
                >
            </div>

            <div>
                <label for="category" class="mb-2 block text-sm font-medium text-choco">
                    Kategori
                </label>

                <select
                    id="category"
                    name="category"
                    class="w-full rounded-xl border border-rose-200 bg-white px-3 py-3 text-sm outline-none focus:border-rose-400 focus:ring-2 focus:ring-rose-100"
                >
                    <option value="">Semua kategori</option>

                    @foreach ($categories as $category)
                        <option
                            value="{{ $category->id }}"
                            @selected((string) request('category') === (string) $category->id)
                        >
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="sort" class="mb-2 block text-sm font-medium text-choco">
                    Urutkan
                </label>

                <select
                    id="sort"
                    name="sort"
                    class="w-full rounded-xl border border-rose-200 bg-white px-3 py-3 text-sm outline-none focus:border-rose-400 focus:ring-2 focus:ring-rose-100"
                >
                    <option value="latest" @selected(request('sort', 'latest') === 'latest')>
                        Terbaru
                    </option>

                    <option value="price-asc" @selected(request('sort') === 'price-asc')>
                        Harga terendah
                    </option>

                    <option value="price-desc" @selected(request('sort') === 'price-desc')>
                        Harga tertinggi
                    </option>

                    <option value="name" @selected(request('sort') === 'name')>
                        Nama A–Z
                    </option>
                </select>
            </div>

            <div class="flex flex-wrap gap-2 sm:col-span-2 lg:col-span-4">
                <button
                    type="submit"
                    class="rounded-xl bg-rose-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-rose-800 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-rose-700"
                >
                    Cari Produk
                </button>

                <a
                    href="{{ route('products.index') }}"
                    class="rounded-xl border border-rose-200 bg-white px-5 py-3 text-sm font-semibold text-choco transition hover:bg-rose-50"
                >
                    Reset Filter
                </a>
            </div>
        </form>

        {{-- Informasi jumlah produk --}}
        <div class="mt-8 flex flex-wrap items-center justify-between gap-2">
            <h2 class="text-lg font-semibold text-choco">
                Koleksi Kue Kami
            </h2>

            <p class="text-sm text-gray-500">
                Menampilkan {{ $products->count() }} dari
                {{ $products->total() }} produk
            </p>
        </div>

        {{-- Daftar produk --}}
        @if ($products->isNotEmpty())
            <div class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">

                @foreach ($products as $product)
                    <x-customer.product-card :product="$product" />
                @endforeach

            </div>

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $products->links() }}
            </div>
        @else
            {{-- Kondisi produk tidak ditemukan --}}
            <div class="mt-6 rounded-2xl border border-dashed border-rose-200 bg-white px-6 py-12 text-center">
                <div class="text-4xl" aria-hidden="true">🎂</div>

                <h3 class="mt-4 text-lg font-semibold text-choco">
                    Produk belum ditemukan
                </h3>

                <p class="mt-2 text-sm text-gray-600">
                    Coba gunakan kata kunci atau kategori lainnya.
                </p>

                <a
                    href="{{ route('products.index') }}"
                    class="mt-5 inline-flex rounded-xl bg-rose-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-rose-800"
                >
                    Lihat Semua Produk
                </a>
            </div>
        @endif

        {{-- Ajakan custom cake --}}
        <section class="mt-12 flex flex-col gap-4 rounded-2xl bg-rose-100 p-6 sm:flex-row sm:items-center sm:justify-between sm:p-8">
            <div>
                <p class="text-sm font-semibold text-rose-800">
                    Punya desain kue impian?
                </p>

                <h2 class="mt-2 text-xl font-bold text-choco">
                    Yuk, buat kue custom versimu!
                </h2>

                <p class="mt-2 text-sm leading-6 text-gray-600">
                    Ceritakan ide dan kebutuhan kuemu kepada Early Cake.
                </p>
            </div>

            <a
                href="{{ route('custom-cake.index') }}"
                class="inline-flex shrink-0 items-center justify-center rounded-xl bg-rose-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-rose-800"
            >
                Buat Kue Custom
            </a>
        </section>

    </section>
@endsection