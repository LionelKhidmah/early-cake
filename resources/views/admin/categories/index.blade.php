@extends('layouts.admin')

@section('title', 'Manajemen Kategori')
@section('breadcrumb', 'Manajemen Kategori')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-[#a47b69]">
                EARLY CAKE / ADMIN
            </p>

            <h1 class="mt-1 text-2xl font-bold text-[#49352e] sm:text-3xl">
                Manajemen Kategori
            </h1>

            <p class="mt-2 text-sm text-[#806d65]">
                Kelola kategori produk Early Cake.
            </p>
        </div>

        <a href="{{ route('admin.categories.create') }}"
           class="rounded-xl bg-[#a85e67] px-5 py-3 text-center text-sm font-semibold text-white hover:bg-[#914b54]">
            + Tambah Kategori
        </a>
    </div>

    {{-- Notifikasi --}}
    @if (session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    {{-- Statistik --}}
    <div class="rounded-2xl border border-[#f0e4dc] bg-white p-5 shadow-sm">
        <p class="text-sm text-[#806d65]">Total Kategori</p>

        <p class="mt-2 text-3xl font-bold text-[#49352e]">
            {{ $categories->total() }}
        </p>
    </div>

    {{-- Daftar kategori --}}
    <div class="overflow-hidden rounded-2xl border border-[#f0e4dc] bg-white shadow-sm">

        <div class="border-b border-[#f0e4dc] px-5 py-4">
            <h2 class="font-semibold text-[#49352e]">
                Daftar Kategori
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[650px] text-left text-sm">

                <thead class="bg-[#fff8f4] text-xs uppercase text-[#806d65]">
                    <tr>
                        <th class="px-5 py-4">No</th>
                        <th class="px-5 py-4">Nama Kategori</th>
                        <th class="px-5 py-4">Deskripsi</th>
                        <th class="px-5 py-4 text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-[#f4ebe5]">

                    @forelse ($categories as $category)
                        <tr class="hover:bg-[#fffaf7]">

                            <td class="px-5 py-4 text-[#806d65]">
                                {{ $categories->firstItem() + $loop->index }}
                            </td>

                            <td class="px-5 py-4 font-semibold text-[#49352e]">
                                {{ $category->name }}
                            </td>

                            <td class="px-5 py-4 text-[#806d65]">
                                {{ $category->description ?: '-' }}
                            </td>

                            <td class="px-5 py-4">
                                <div class="flex justify-end gap-2">

                                    <a href="{{ route('admin.categories.edit', $category) }}"
                                       class="rounded-lg bg-[#f8e8e8] px-3 py-2 text-xs font-semibold text-[#914b54] hover:bg-[#f2d7d9]">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.categories.destroy', $category) }}"
                                          method="POST"
                                          class="delete-category-form">

                                        @csrf
                                        @method('DELETE')

                                        <button type="button"
                                                class="delete-category-button rounded-lg border border-red-200 px-3 py-2 text-xs font-semibold text-red-600 hover:bg-red-50"
                                                data-category-name="{{ $category->name }}">
                                            Hapus
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-16 text-center">
                                <p class="text-4xl">🧁</p>
                                <p class="mt-3 font-semibold text-[#49352e]">
                                    Belum ada kategori
                                </p>
                                <p class="mt-2 text-sm text-[#806d65]">
                                    Tambahkan kategori pertama untuk produk Early Cake.
                                </p>
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        <div class="border-t border-[#f0e4dc] px-5 py-4">
            {{ $categories->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.delete-category-button').forEach(function (button) {

        button.addEventListener('click', async function () {

            const form = button.closest('.delete-category-form');
            const categoryName = button.dataset.categoryName;

            if (!window.Swal) {
                alert('SweetAlert2 gagal dimuat.');
                return;
            }

            const result = await Swal.fire({
                title: 'Hapus kategori?',
                text: `Yakin ingin menghapus kategori "${categoryName}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#a85e67',
                cancelButtonColor: '#806d65',
                reverseButtons: true,
                background: '#fffaf7',
                color: '#49352e'
            });

            if (result.isConfirmed) {
                form.submit();
            }

        });

    });

});
</script>
@endpush