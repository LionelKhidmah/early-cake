@extends('layouts.admin')

@section('title', 'Kue Kustom')
@section('breadcrumb', 'Kue Kustom')

@section('content')
@php
    $statusLabels = [
        'pending' => 'Menunggu',
        'approved' => 'Disetujui',
        'rejected' => 'Ditolak',
        'completed' => 'Selesai',
    ];

    $statusClasses = [
        'pending' => 'bg-amber-50 text-amber-700',
        'approved' => 'bg-emerald-50 text-emerald-700',
        'rejected' => 'bg-red-50 text-red-700',
        'completed' => 'bg-gray-100 text-gray-600',
    ];
@endphp

<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-[#403431]">
                Pengajuan Kue Kustom
            </h1>
            <p class="mt-1 text-sm text-[#968681]">
                Lihat dan kelola desain kue yang dikirim pelanggan.
            </p>
        </div>

        <div class="rounded-xl bg-white px-4 py-3 shadow-sm">
            <p class="text-xs text-[#968681]">Total pengajuan</p>
            <p class="text-xl font-bold text-[#B75C78]">
                {{ $customCakes->total() }}
            </p>
        </div>
    </div>

    @if (session('success'))
        <div class="rounded-xl bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="rounded-xl bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm">
        @if ($customCakes->isEmpty())
            <div class="px-6 py-16 text-center">
                <div class="mx-auto flex size-14 items-center justify-center rounded-2xl bg-[#FCE8ED] text-2xl">
                    🎂
                </div>

                <h2 class="mt-4 font-semibold text-[#403431]">
                    Belum ada pengajuan kue kustom
                </h2>

                <p class="mt-1 text-sm text-[#968681]">
                    Pengajuan dari pelanggan akan muncul di halaman ini.
                </p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full min-w-[900px] text-left">
                    <thead class="bg-[#FFF7F9] text-xs uppercase tracking-wider text-[#968681]">
                        <tr>
                            <th class="px-6 py-4">Pelanggan</th>
                            <th class="px-6 py-4">Nomor Pesanan</th>
                            <th class="px-6 py-4">Spesifikasi</th>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-[#F4E9E9]">
                        @foreach ($customCakes as $cake)
                            <tr class="transition hover:bg-[#FFFAFB]">
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-[#403431]">
                                        {{ $cake->order?->customer?->name ?? 'Pelanggan' }}
                                    </p>
                                    <p class="mt-1 text-xs text-[#968681]">
                                        {{ $cake->order?->customer?->phone ?? '-' }}
                                    </p>
                                </td>

                                <td class="px-6 py-4">
                                    <span class="font-mono text-sm text-[#6F5C56]">
                                        {{ $cake->order?->order_number ?? '-' }}
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    <p class="text-sm font-medium text-[#403431]">
                                        {{ $cake->shape ?: 'Bentuk belum dipilih' }}
                                    </p>
                                    <p class="mt-1 text-xs text-[#968681]">
                                        {{ $cake->size }} · {{ $cake->flavor }}
                                    </p>
                                </td>

                                <td class="whitespace-nowrap px-6 py-4 text-sm text-[#796A65]">
                                    {{ $cake->created_at?->format('d M Y') ?? '-' }}
                                </td>

                                <td class="px-6 py-4">
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses[$cake->status] ?? 'bg-gray-100 text-gray-600' }}">
                                        {{ $statusLabels[$cake->status] ?? ucfirst($cake->status) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.custom-cakes.show', $cake) }}"
                                           class="inline-flex items-center rounded-lg bg-[#FCE8ED] px-3.5 py-2 text-xs font-semibold text-[#A84D6A] transition hover:bg-[#F8D9E2]">
                                            Lihat Detail
                                        </a>

                                        <form action="{{ route('admin.custom-cakes.destroy', $cake) }}"
                                              method="POST"
                                              class="delete-custom-cake-form">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="inline-flex items-center rounded-lg bg-red-50 px-3.5 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-100">
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

            <div class="border-t border-[#F4E9E9] px-5 py-4">
                {{ $customCakes->links() }}
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.delete-custom-cake-form').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault();

            Swal.fire({
                title: 'Hapus pengajuan?',
                text: 'Pengajuan dan notifikasi terkait akan dihapus. Data pesanan tetap tersimpan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#B75C78',
                cancelButtonColor: '#A99A96',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-lg',
                    cancelButton: 'rounded-lg'
                }
            }).then(function (result) {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});
</script>
@endsection
