@extends('layouts.admin')

@section('title', 'Manajemen Promo')
@section('breadcrumb', 'Manajemen Promo')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-[#49352E]">
                Manajemen Promo
            </h1>

            <p class="mt-2 text-sm text-[#806D65]">
                Kelola promo, diskon, dan penawaran spesial Early Cake.
            </p>
        </div>

        <a href="{{ route('admin.promos.create') }}"
           class="inline-flex items-center justify-center rounded-xl bg-[#B75C78] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#9F4866]">
            + Tambah Promo
        </a>
    </div>

    {{-- STATISTIK --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

        <div class="rounded-2xl border border-rose-100 bg-white p-5 shadow-sm">
            <p class="text-sm text-[#806D65]">Total Promo</p>
            <h2 class="mt-2 text-3xl font-bold text-[#49352E]">
                {{ $totalPromos }}
            </h2>
        </div>

        <div class="rounded-2xl border border-rose-100 bg-white p-5 shadow-sm">
            <p class="text-sm text-[#806D65]">Promo Aktif</p>
            <h2 class="mt-2 text-3xl font-bold text-green-600">
                {{ $activePromos }}
            </h2>
        </div>

        <div class="rounded-2xl border border-rose-100 bg-white p-5 shadow-sm">
            <p class="text-sm text-[#806D65]">Promo Tidak Aktif</p>
            <h2 class="mt-2 text-3xl font-bold text-[#B75C78]">
                {{ $inactivePromos }}
            </h2>
        </div>

    </div>

    {{-- FILTER --}}
    <form action="{{ route('admin.promos.index') }}"
          method="GET"
          class="flex flex-col gap-3 rounded-2xl border border-rose-100 bg-white p-5 shadow-sm sm:flex-row">

        <input type="search"
               name="search"
               value="{{ request('search') }}"
               placeholder="Cari nama atau kode promo..."
               class="w-full rounded-xl border border-rose-200 px-4 py-3 text-sm focus:border-rose-400 focus:ring-rose-200">

        <select name="status"
                class="rounded-xl border border-rose-200 px-4 py-3 text-sm focus:border-rose-400 focus:ring-rose-200">

            <option value="">Semua Status</option>

            <option value="active"
                    @selected(request('status') === 'active')>
                Aktif
            </option>

            <option value="inactive"
                    @selected(request('status') === 'inactive')>
                Tidak Aktif
            </option>
        </select>

        <button type="submit"
                class="rounded-xl bg-[#49352E] px-6 py-3 text-sm font-semibold text-white transition hover:bg-[#382821]">
            Filter
        </button>
    </form>

    {{-- TABEL PROMO --}}
    <div class="overflow-hidden rounded-2xl border border-rose-100 bg-white shadow-sm">

        <div class="overflow-x-auto">
            <table class="w-full min-w-[850px] text-left text-sm">

                <thead class="bg-[#FFF1F4] text-[#49352E]">
                    <tr>
                        <th class="px-5 py-4">Promo</th>
                        <th class="px-5 py-4">Kode</th>
                        <th class="px-5 py-4">Diskon</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4">Periode</th>
                        <th class="px-5 py-4 text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-rose-100">

                    @forelse ($promos as $promo)
                        <tr class="hover:bg-[#FFF9FA]">

                            {{-- PROMO --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">

                                    @if ($promo->image)
                                        <img
                                            src="{{ asset('storage/' . $promo->image) }}"
                                            alt="{{ $promo->title }}"
                                            class="h-14 w-14 rounded-xl object-cover"
                                        >
                                    @else
                                        <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-rose-100 text-2xl">
                                            🎂
                                        </div>
                                    @endif

                                    <div>
                                        <p class="font-semibold text-[#49352E]">
                                            {{ $promo->title }}
                                        </p>

                                        <p class="mt-1 text-xs text-[#806D65]">
                                            {{ $promo->badge ?? 'Promo Early Cake' }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            {{-- KODE --}}
                            <td class="px-5 py-4 font-semibold text-[#B75C78]">
                                {{ $promo->code }}
                            </td>

                            {{-- DISKON --}}
                            <td class="px-5 py-4">
                                @if ($promo->discount_type === 'percentage')
                                    {{ number_format($promo->discount_value, 0) }}%
                                @else
                                    Rp{{ number_format($promo->discount_value, 0, ',', '.') }}
                                @endif
                            </td>

                            {{-- STATUS --}}
                            <td class="px-5 py-4">
                                @if ($promo->isCurrentlyActive())
                                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                        Aktif
                                    </span>
                                @else
                                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">
                                        Tidak Aktif
                                    </span>
                                @endif
                            </td>

                            {{-- PERIODE --}}
                            <td class="px-5 py-4 text-xs text-[#806D65]">
                                {{ $promo->starts_at->format('d M Y') }}
                                -
                                {{ $promo->ends_at->format('d M Y') }}
                            </td>

                            {{-- AKSI --}}
                            <td class="px-5 py-4">
                                <div class="flex justify-end gap-2">

                                    <a href="{{ route('admin.promos.show', $promo) }}"
                                       class="rounded-lg bg-[#FFF1F4] px-3 py-2 text-xs font-semibold text-[#49352E] transition hover:bg-rose-100">
                                        Detail
                                    </a>

                                    <a href="{{ route('admin.promos.edit', $promo) }}"
                                       class="rounded-lg bg-rose-50 px-3 py-2 text-xs font-semibold text-[#B75C78] transition hover:bg-rose-100">
                                        Edit
                                    </a>

                                    {{-- FORM HAPUS --}}
                                    <form
                                        action="{{ route('admin.promos.destroy', $promo) }}"
                                        method="POST"
                                        class="delete-promo-form">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="button"
                                            class="delete-promo-button rounded-lg bg-red-50 px-3 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-100"
                                            data-promo-title="{{ $promo->title }}">
                                            Hapus
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-16 text-center">

                                <div class="text-5xl">🎟️</div>

                                <h3 class="mt-4 text-lg font-bold text-[#49352E]">
                                    Belum Ada Promo
                                </h3>

                                <p class="mt-2 text-sm text-[#806D65]">
                                    Tambahkan promo pertama untuk pelanggan Early Cake.
                                </p>

                                <a href="{{ route('admin.promos.create') }}"
                                   class="mt-5 inline-flex rounded-xl bg-[#B75C78] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#9F4866]">
                                    + Tambah Promo
                                </a>
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if ($promos->hasPages())
            <div class="border-t border-rose-100 px-5 py-4">
                {{ $promos->links() }}
            </div>
        @endif

    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const swalConfig = {
            confirmButtonColor: '#B75C78',
            cancelButtonColor: '#806D65',
            buttonsStyling: true
        };

        // KONFIRMASI HAPUS PROMO
        document.querySelectorAll('.delete-promo-button').forEach(function (button) {

            button.addEventListener('click', function () {

                const form = button.closest('.delete-promo-form');
                const promoTitle = button.dataset.promoTitle;

                if (!form) {
                    return;
                }

                Swal.fire({
                    ...swalConfig,
                    title: 'Hapus Promo?',
                    text: `Promo "${promoTitle}" akan dihapus secara permanen.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    focusCancel: true,
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'rounded-lg',
                        cancelButton: 'rounded-lg'
                    }
                }).then(function (result) {

                    if (result.isConfirmed) {
                        button.disabled = true;
                        form.submit();
                    }

                });

            });

        });

        // NOTIFIKASI BERHASIL
        @if (session('success'))
            Swal.fire({
                ...swalConfig,
                title: 'Berhasil!',
                text: @json(session('success')),
                icon: 'success',
                confirmButtonText: 'Oke',
                timer: 2500,
                timerProgressBar: true
            });
        @endif

        // NOTIFIKASI ERROR
        @if (session('error'))
            Swal.fire({
                ...swalConfig,
                title: 'Terjadi Kesalahan',
                text: @json(session('error')),
                icon: 'error',
                confirmButtonText: 'Mengerti'
            });
        @endif

    });
</script>
@endpush
