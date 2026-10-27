@extends('layouts.admin')

@section('title', 'Manajemen Produk')
@section('breadcrumb', 'Manajemen Produk')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-[#a47b69]">EARLY CAKE / ADMIN</p>
            <h1 class="mt-1 text-2xl font-bold text-[#49352e] sm:text-3xl">
                Manajemen Produk
            </h1>
            <p class="mt-2 text-sm text-[#806d65]">
                Kelola produk kue dan camilan Early Cake.
            </p>
        </div>

        <a href="{{ route('admin.products.create') }}"
           class="rounded-xl bg-[#a85e67] px-5 py-3 text-center text-sm font-semibold text-white hover:bg-[#914b54]">
            + Tambah Produk
        </a>
    </div>

    @if (session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <p class="font-semibold">Ada data yang perlu diperbaiki:</p>
            <ul class="mt-2 list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="rounded-2xl border border-[#f0e4dc] bg-white p-5 shadow-sm">
            <p class="text-sm text-[#806d65]">Total Produk</p>
            <p class="mt-2 text-3xl font-bold text-[#49352e]">
                {{ $products->total() }}
            </p>
        </div>

        <div class="rounded-2xl border border-[#f0e4dc] bg-white p-5 shadow-sm">
            <p class="text-sm text-[#806d65]">Kategori</p>
            <p class="mt-2 text-3xl font-bold text-[#49352e]">
                {{ $categories->count() }}
            </p>
        </div>

        <div class="rounded-2xl border border-[#f0e4dc] bg-white p-5 shadow-sm">
            <p class="text-sm text-[#806d65]">Produk Tersedia</p>
            <p class="mt-2 text-3xl font-bold text-[#49352e]">
                {{ \App\Models\Product::where('is_available', true)->count() }}
            </p>
        </div>
    </div>

    <div class="rounded-2xl border border-[#f0e4dc] bg-white p-5 shadow-sm">
        <form method="GET"
              action="{{ route('admin.products.index') }}"
              class="grid grid-cols-1 gap-3 md:grid-cols-4">

            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Cari nama produk..."
                   class="w-full rounded-xl border border-[#eadbd2] px-4 py-3 text-sm focus:border-[#bd7b83] focus:outline-none">

            <select name="category"
                    class="w-full rounded-xl border border-[#eadbd2] bg-white px-4 py-3 text-sm focus:border-[#bd7b83] focus:outline-none">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}"
                        @selected((string) request('category') === (string) $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            <select name="availability"
                    class="w-full rounded-xl border border-[#eadbd2] bg-white px-4 py-3 text-sm focus:border-[#bd7b83] focus:outline-none">
                <option value="">Semua Status</option>
                <option value="1" @selected(request('availability') === '1')>
                    Tersedia
                </option>
                <option value="0" @selected(request('availability') === '0')>
                    Tidak tersedia
                </option>
            </select>

            <div class="flex gap-2">
                <button type="submit"
                        class="flex-1 rounded-xl bg-[#49352e] px-4 py-3 text-sm font-semibold text-white hover:bg-[#63483e]">
                    Cari
                </button>

                <a href="{{ route('admin.products.index') }}"
                   class="rounded-xl border border-[#eadbd2] px-4 py-3 text-sm text-[#49352e] hover:bg-[#fff8f4]">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="overflow-hidden rounded-2xl border border-[#f0e4dc] bg-white shadow-sm">
        <div class="border-b border-[#f0e4dc] px-5 py-4">
            <h2 class="font-semibold text-[#49352e]">Daftar Produk</h2>
            <p class="mt-1 text-sm text-[#806d65]">
                {{ $products->total() }} produk ditemukan
            </p>
        </div>

        @if ($products->isEmpty())
            <div class="px-5 py-16 text-center">
                <p class="text-4xl">🧁</p>
                <h3 class="mt-4 font-semibold text-[#49352e]">
                    Produk belum ditemukan
                </h3>
                <p class="mt-2 text-sm text-[#806d65]">
                    Coba ubah pencarian atau tambahkan produk baru.
                </p>
            </div>
        @else
            <div class="hidden overflow-x-auto md:block">
                <table class="w-full text-left text-sm">
                    <thead class="bg-[#fff8f4] text-xs uppercase text-[#806d65]">
                        <tr>
                            <th class="px-5 py-4">Produk</th>
                            <th class="px-5 py-4">Kategori</th>
                            <th class="px-5 py-4">Harga</th>
                            <th class="px-5 py-4">Stok</th>
                            <th class="px-5 py-4">Status</th>
                            <th class="px-5 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-[#f4ebe5]">
                        @foreach ($products as $product)
                            <tr class="hover:bg-[#fffaf7]">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-14 w-14 shrink-0 items-center justify-center overflow-hidden rounded-xl bg-[#fff0e8]">
                                            @if ($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}"
                                                     alt="{{ $product->name }}"
                                                     class="h-full w-full object-cover">
                                            @else
                                                <span class="text-2xl">🎂</span>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-semibold text-[#49352e]">
                                                {{ $product->name }}
                                            </p>
                                            <p class="mt-1 max-w-xs text-xs text-[#806d65]">
                                                {{ \Illuminate\Support\Str::limit($product->description ?? 'Produk Early Cake', 65) }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-4 text-[#806d65]">
                                    {{ $product->category?->name ?? 'Tanpa kategori' }}
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 font-semibold text-[#49352e]">
                                    Rp{{ number_format((float) $product->price, 0, ',', '.') }}
                                </td>

                                <td class="px-5 py-4 text-[#49352e]">
                                    {{ $product->stock }}
                                </td>

                                <td class="px-5 py-4">
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold
                                        {{ $product->is_available
                                            ? 'bg-green-50 text-green-700'
                                            : 'bg-amber-50 text-amber-700' }}">
                                        {{ $product->is_available ? 'Tersedia' : 'Tidak tersedia' }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex flex-wrap justify-end gap-2">
                                        <a href="{{ route('admin.products.show', $product) }}"
                                            class="inline-flex items-center rounded-lg bg-blue-50 px-3 py-2 text-sm font-medium text-blue-700 hover:bg-blue-100">
                                            Detail
                                        </a>
                                        <a href="{{ route('admin.products.edit', $product) }}"
                                           class="rounded-lg bg-[#f8e8e8] px-3 py-2 text-xs font-semibold text-[#914b54] hover:bg-[#f2d7d9]">
                                            Edit
                                        </a>

                                        <form action="{{ route('admin.products.destroy', $product) }}"
                                              method="POST"
                                              onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="rounded-lg border border-red-200 px-3 py-2 text-xs font-semibold text-red-600 hover:bg-red-50">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="divide-y divide-[#f4ebe5] md:hidden">
                @foreach ($products as $product)
                    <div class="space-y-3 p-4">
                        <div class="flex gap-3">
                            <div class="flex h-16 w-16 shrink-0 items-center justify-center overflow-hidden rounded-xl bg-[#fff0e8]">
                                @if ($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}"
                                         alt="{{ $product->name }}"
                                         class="h-full w-full object-cover">
                                @else
                                    <span class="text-2xl">🎂</span>
                                @endif
                            </div>

                            <div class="min-w-0 flex-1">
                                <h3 class="font-semibold text-[#49352e]">
                                    {{ $product->name }}
                                </h3>
                                <p class="mt-1 text-xs text-[#806d65]">
                                    {{ $product->category?->name ?? 'Tanpa kategori' }}
                                </p>
                                <p class="mt-2 font-semibold text-[#49352e]">
                                    Rp{{ number_format((float) $product->price, 0, ',', '.') }}
                                </p>
                                <p class="mt-1 text-xs text-[#806d65]">
                                    Stok: {{ $product->stock }}
                                    · {{ $product->is_available ? 'Tersedia' : 'Tidak tersedia' }}
                                </p>
                            </div>
                        </div>

                        <a href="{{ route('admin.products.show', $product) }}" class="rounded-lg border border-[#eadbd2] px-3 py-2 text-xs font-medium text-[#49352e] hover:bg-[#fff0e8]">
                            Detail
                        </a>

                        <div class="flex gap-2">
                            <a href="{{ route('admin.products.edit', $product) }}"
                               class="flex-1 rounded-xl bg-[#f8e8e8] px-3 py-2.5 text-center text-sm font-semibold text-[#914b54]">
                                Edit
                            </a>

                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="delete-product-form">
                                @csrf
                                @method('DELETE')

                                <button type="button"
                                    class="delete-product-button rounded-lg border border-red-200 px-3 py-2 text-xs font-semibold text-red-600 hover:bg-red-50"
                                    data-product-name="{{ $product->name }}">
                                        Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="border-t border-[#f0e4dc] px-5 py-4">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.delete-product-button').forEach(function (button) {
        button.addEventListener('click', async function () {
            const form = button.closest('.delete-product-form');
            const productName = button.dataset.productName;

            if (!window.Swal) {
                alert('SweetAlert2 gagal dimuat. Periksa koneksi internet.');
                return;
            }

            const result = await Swal.fire({
                title: 'Hapus produk?',
                text: `Produk "${productName}" akan dihapus dari katalog.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus produk',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                focusCancel: true,
                confirmButtonColor: '#a85e67',
                cancelButtonColor: '#806d65',
                background: '#fffaf7',
                color: '#49352e',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl',
                    cancelButton: 'rounded-xl'
                }
            });

            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
@endpush