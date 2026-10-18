@props(['product'])

<article class="group flex h-full flex-col overflow-hidden rounded-2xl border border-rose-100 bg-white transition duration-300 hover:-translate-y-1 hover:shadow-lg">

    {{-- Gambar Produk --}}
    <a
        href="{{ route('products.show', $product) }}"
        class="block overflow-hidden bg-rose-50"
        aria-label="Lihat detail {{ $product->name }}"
    >
        <div class="aspect-[4/3] overflow-hidden">
            <x-product-image
                :product="$product"
                class="size-full object-cover transition duration-500 group-hover:scale-105"
            />
        </div>
    </a>

    {{-- Informasi Produk --}}
    <div class="flex flex-1 flex-col p-4 sm:p-5">

        {{-- Kategori --}}
        @if ($product->category)
            <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-rose-kue">
                {{ $product->category->name }}
            </p>
        @endif

        {{-- Nama Produk --}}
        <h3 class="line-clamp-2 text-base font-bold leading-snug text-choco sm:text-lg">
            <a
                href="{{ route('products.show', $product) }}"
                class="transition hover:text-rose-kue"
            >
                {{ $product->name }}
            </a>
        </h3>

        {{-- Deskripsi --}}
        @if ($product->description)
            <p class="mt-2 line-clamp-2 text-sm leading-relaxed text-choco/65">
                {{ $product->description }}
            </p>
        @endif

        {{-- Harga dan Stok --}}
        <div class="mt-4 flex flex-wrap items-center justify-between gap-2">
            <p class="text-lg font-bold text-rose-kue">
                Rp{{ number_format((float) $product->price, 0, ',', '.') }}
            </p>

            @if ($product->is_available && $product->stock > 0)
                <span class="text-xs font-medium text-green-700">
                    Tersedia
                </span>
            @else
                <span class="text-xs font-medium text-red-600">
                    Stok habis
                </span>
            @endif
        </div>

        {{-- Tombol Aksi Vertikal --}}
        <div class="mt-auto flex flex-col gap-2 pt-5">

            {{-- Tambah ke Keranjang --}}
            @if ($product->is_available && $product->stock > 0)
                <form
                    method="POST"
                    action="{{ route('cart.add') }}"
                    class="w-full"
                >
                    @csrf

                    <input
                        type="hidden"
                        name="product_id"
                        value="{{ $product->id }}"
                    >

                    <input
                        type="hidden"
                        name="quantity"
                        value="1"
                    >

                    <button
                        type="submit"
                        class="inline-flex min-h-11 w-full items-center justify-center gap-2 rounded-xl bg-rose-kue px-4 py-3 text-sm font-semibold text-white transition hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-rose-300 focus:ring-offset-2"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="size-4 shrink-0"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8"
                            aria-hidden="true"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M3 3h2l2.2 11.2a2 2 0 0 0 2 1.6h7.9a2 2 0 0 0 2-1.6L21 8H6"
                            />
                            <circle cx="10" cy="20" r="1" />
                            <circle cx="18" cy="20" r="1" />
                        </svg>

                        Tambah ke Keranjang
                    </button>
                </form>
            @else
                <button
                    type="button"
                    disabled
                    class="inline-flex min-h-11 w-full cursor-not-allowed items-center justify-center rounded-xl bg-gray-100 px-4 py-3 text-sm font-semibold text-gray-400"
                >
                    Stok Habis
                </button>
            @endif

            {{-- Lihat Detail --}}
            <a
                href="{{ route('products.show', $product) }}"
                class="inline-flex min-h-11 w-full items-center justify-center rounded-xl border-2 border-rose-200 bg-white px-4 py-3 text-sm font-semibold text-rose-kue transition hover:border-rose-400 hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-rose-200 focus:ring-offset-2"
            >
                Lihat Detail
            </a>

        </div>
    </div>
</article>