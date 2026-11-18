@extends('layouts.admin')

@section('title', 'Pilihan Kue Kustom')
@section('breadcrumb', 'Pilihan Kue Kustom')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-[#a47b69]">EARLY CAKE / ADMIN</p>
            <h1 class="mt-1 text-2xl font-bold text-[#49352e] sm:text-3xl">
                Pilihan Kue Kustom
            </h1>
            <p class="mt-2 text-sm text-[#806d65]">
                Atur rasa, ukuran, dan dekorasi yang tersedia untuk pelanggan.
            </p>
        </div>

        <a href="{{ route('admin.custom-cake-options.create') }}"
           class="rounded-xl bg-[#a85e67] px-5 py-3 text-center text-sm font-semibold text-white hover:bg-[#914b54]">
            + Tambah Pilihan
        </a>
    </div>

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

    <div class="grid gap-4 sm:grid-cols-3">
        @foreach (['flavor' => 'Rasa', 'size' => 'Ukuran', 'decoration' => 'Dekorasi'] as $type => $label)
            <div class="rounded-2xl border border-[#f0e4dc] bg-white p-5 shadow-sm">
                <p class="text-sm text-[#806d65]">Pilihan {{ $label }}</p>
                <p class="mt-2 text-3xl font-bold text-[#49352e]">
                    {{ $options->getCollection()->where('option_type', $type)->count() }}
                </p>
                <p class="mt-1 text-xs text-[#a47b69]">Pada halaman ini</p>
            </div>
        @endforeach
    </div>

    <div class="overflow-hidden rounded-2xl border border-[#f0e4dc] bg-white shadow-sm">
        <div class="border-b border-[#f0e4dc] px-5 py-4">
            <h2 class="font-semibold text-[#49352e]">Daftar Pilihan</h2>
            <p class="mt-1 text-xs text-[#806d65]">
                Total {{ $options->total() }} pilihan tersimpan.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[850px] text-left text-sm">
                <thead class="bg-[#fff8f4] text-xs uppercase text-[#806d65]">
                    <tr>
                        <th class="px-5 py-4">No</th>
                        <th class="px-5 py-4">Nama</th>
                        <th class="px-5 py-4">Jenis</th>
                        <th class="px-5 py-4">Biaya Tambahan</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4">Urutan</th>
                        <th class="px-5 py-4 text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-[#f4ebe5]">
                    @forelse ($options as $option)
                        <tr class="hover:bg-[#fffaf7]">
                            <td class="px-5 py-4 text-[#806d65]">
                                {{ $options->firstItem() + $loop->index }}
                            </td>

                            <td class="px-5 py-4">
                                <p class="font-semibold text-[#49352e]">
                                    {{ $option->name }}
                                </p>
                                <p class="mt-1 max-w-xs text-xs text-[#806d65]">
                                    {{ $option->description ?: 'Tidak ada deskripsi' }}
                                </p>
                            </td>

                            <td class="px-5 py-4 text-[#806d65]">
                                {{ match($option->option_type) {
                                    'flavor' => 'Rasa',
                                    'size' => 'Ukuran',
                                    'decoration' => 'Dekorasi',
                                    default => $option->option_type,
                                } }}
                            </td>

                            <td class="px-5 py-4 text-[#49352e]">
                                Rp{{ number_format((float) $option->additional_price, 0, ',', '.') }}
                            </td>

                            <td class="px-5 py-4">
                                @if ($option->is_active)
                                    <span class="rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                                        Aktif
                                    </span>
                                @else
                                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>

                            <td class="px-5 py-4 text-[#806d65]">
                                {{ $option->sort_order }}
                            </td>

                            <td class="px-5 py-4">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.custom-cake-options.edit', $option) }}"
                                       class="rounded-lg bg-[#f8e8e8] px-3 py-2 text-xs font-semibold text-[#914b54] hover:bg-[#f2d7d9]">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.custom-cake-options.destroy', $option) }}"
                                        method="POST"
                                        class="delete-option-form"
                                        data-name="{{ $option->name }}">
                                        @csrf
                                        @method('DELETE')

                                    <button type="submit" class="rounded-lg border border-red-200 px-3 py-2 text-xs font-semibold text-red-600 hover:bg-red-50">
                                        Hapus
                                    </button>
                                </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-16 text-center">
                                <p class="text-3xl">🎂</p>
                                <p class="mt-3 font-semibold text-[#49352e]">
                                    Belum ada pilihan kue kustom
                                </p>
                                <p class="mt-2 text-sm text-[#806d65]">
                                    Tambahkan rasa, ukuran, atau dekorasi pertama.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-[#f0e4dc] px-5 py-4">
            {{ $options->links() }}
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.delete-option-form').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            Swal.fire({
                title: 'Hapus Pilihan?',
                text: `Apakah kamu yakin ingin menghapus ${form.dataset.name}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                focusCancel: true,
                confirmButtonColor: '#a85e67',
                cancelButtonColor: '#806d65',
                background: '#fffaf7',
                color: '#49352e',
            }).then((result) => {
                if (result.isConfirmed) {
                    HTMLFormElement.prototype.submit.call(form);
                }
            });
        });
    });

    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: @json(session('success')),
            confirmButtonColor: '#a85e67',
            background: '#fffaf7',
            color: '#49352e',
            timer: 2500,
            showConfirmButton: false
        });
    @endif
});
</script>
@endpush
