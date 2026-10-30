@props(['product'])

@php
    $isAvailable = (bool) $product->is_available && $product->stock > 0;
@endphp

<article
    class="group flex h-full flex-col overflow-hidden
           rounded-2xl border border-rose-100
           bg-white shadow-sm transition duration-300
           hover:-translate-y-1 hover:shadow-lg">

    {{-- FOTO PRODUK --}}
    <a
        href="{{ route('products.show', $product) }}"
        class="relative block aspect-[4/3]
               overflow-hidden bg-rose-50">

        @if ($product->image)
            <img
                src="{{ asset('storage/' . $product->image) }}"
                alt="{{ $product->name }}"
                loading="lazy"
                class="h-full w-full object-cover
                       transition-transform duration-500
                       group-hover:scale-105">
        @else
            <div
                class="flex h-full items-center justify-center
                       bg-rose-50">

                <span class="text-sm text-gray-500">
                    Foto belum tersedia
                </span>
            </div>
        @endif

        {{-- STATUS PRODUK --}}
        <span
            class="absolute left-3 top-3 rounded-full
                   bg-white/95 px-3 py-1
                   text-xs font-semibold shadow-sm
                   {{ $isAvailable
                       ? 'text-rose-700'
                       : 'text-gray-500' }}">

            {{ $isAvailable ? 'Tersedia' : 'Habis' }}
        </span>
    </a>

    {{-- INFORMASI PRODUK --}}
    <div class="flex flex-1 flex-col p-4 sm:p-5">

        {{-- KATEGORI --}}
        @if ($product->category)
            <p
                class="text-xs font-medium uppercase
                       tracking-wider text-rose-600">

                {{ $product->category->name }}
            </p>
        @endif

        {{-- NAMA PRODUK --}}
        <h3
            class="mt-2 line-clamp-1 text-lg
                   font-bold text-choco">

            <a
                href="{{ route('products.show', $product) }}"
                class="transition hover:text-rose-700">

                {{ $product->name }}
            </a>
        </h3>

        {{-- DESKRIPSI --}}
        <p
            class="mt-2 line-clamp-2 min-h-10
                   text-sm leading-5 text-gray-600">

            {{ $product->description
                ?: 'Dibuat dengan sepenuh hati untuk menemani momen spesialmu.' }}
        </p>

        {{-- HARGA --}}
        <div class="mt-auto pt-4">

            <p class="text-base font-bold text-rose-700">
                Rp{{ number_format((float) $product->price, 0, ',', '.') }}
            </p>

            {{-- ACTION BUTTONS --}}
            <div class="mt-4 grid grid-cols-2 gap-2">

                {{-- DETAIL PRODUK --}}
                <a
                    href="{{ route('products.show', $product) }}"
                    class="inline-flex min-h-11 items-center
                           justify-center rounded-xl
                           border border-rose-200
                           px-2 py-2 text-center text-xs
                           font-semibold text-rose-700
                           transition hover:bg-rose-50">

                    Lihat Detail
                </a>

                {{-- TAMBAH KE KERANJANG --}}
                @if ($isAvailable)

                    <form
                        action="{{ route('cart.add') }}"
                        method="POST"
                        class="w-full">

                        @csrf

                        <input
                            type="hidden"
                            name="product_id"
                            value="{{ $product->id }}">

                        <input
                            type="hidden"
                            name="quantity"
                            value="1">

                        <button
                            type="submit"
                            class="inline-flex min-h-11 w-full
                                   items-center justify-center
                                   gap-1.5 rounded-xl
                                   bg-rose-700 px-2 py-2
                                   text-xs font-semibold
                                   text-white transition
                                   hover:bg-rose-800
                                   focus-visible:outline
                                   focus-visible:outline-2
                                   focus-visible:outline-offset-2
                                   focus-visible:outline-rose-700">

                            <svg
                                class="size-4 shrink-0"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round">

                                <circle cx="9" cy="21" r="1"/>
                                <circle cx="20" cy="21" r="1"/>

                                <path
                                    d="M1 1h4l2.5 13h12l2-9H6"/>

                                <path d="M12 7v6M9 10h6"/>
                            </svg>

                            <span>+ Keranjang</span>
                        </button>
                    </form>

                @else

                    {{-- TOMBOL JIKA STOK HABIS --}}
                    <button
                        type="button"
                        disabled
                        class="inline-flex min-h-11
                               items-center justify-center
                               rounded-xl bg-gray-200
                               px-2 py-2 text-xs
                               font-semibold text-gray-500
                               cursor-not-allowed">

                        Stok Habis
                    </button>

                @endif

            </div>
        </div>
    </div>
</article>
