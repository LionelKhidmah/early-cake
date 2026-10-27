@props(['product'])

<article class="group overflow-hidden rounded-2xl border border-rose-100 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg">

    {{-- Foto produk --}}
    <a href="{{ route('products.show', $product) }}"
       class="relative block aspect-[4/3] overflow-hidden bg-rose-50">

        @if ($product->image)
    <img
        src="{{ asset('storage/'.$product->image) }}"
        alt="{{ $product->name }}"
        class="h-full w-full object-cover"
        loading="lazy"
    >
    @else
    <div class="flex h-full items-center justify-center bg-rose-50">
        <span class="text-sm text-gray-500">
            Foto belum tersedia
        </span>
    </div>
    @endif

        @if ($product->is_available)
            <span class="absolute left-3 top-3 rounded-full bg-white/95 px-3 py-1 text-xs font-semibold text-rose-700 shadow-sm">
                Tersedia
            </span>
        @else
            <span class="absolute left-3 top-3 rounded-full bg-white/95 px-3 py-1 text-xs font-semibold text-gray-500 shadow-sm">
                Habis
            </span>
        @endif
    </a>

    {{-- Informasi produk --}}
    <div class="p-4 sm:p-5">
        @if ($product->category)
            <p class="text-xs font-medium uppercase tracking-wider text-rose-600">
                {{ $product->category->name }}
            </p>
        @endif

        <h3 class="mt-2 line-clamp-1 text-lg font-bold text-choco">
            <a href="{{ route('products.show', $product) }}"
               class="transition hover:text-rose-700">
                {{ $product->name }}
            </a>
        </h3>

        <p class="mt-2 line-clamp-2 min-h-10 text-sm leading-5 text-gray-600">
            {{ $product->description ?: 'Dibuat dengan sepenuh hati untuk menemani momen spesialmu.' }}
        </p>

        <div class="mt-4 flex items-center justify-between gap-3">
            <p class="text-base font-bold text-rose-700">
                Rp{{ number_format((float) $product->price, 0, ',', '.') }}
            </p>

            <a href="{{ route('products.show', $product) }}"
               class="inline-flex shrink-0 items-center rounded-xl bg-rose-700 px-3 py-2 text-xs font-semibold text-white transition hover:bg-rose-800">
                Lihat Detail
            </a>
        </div>
    </div>
</article>