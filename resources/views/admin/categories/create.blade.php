
@extends('layouts.admin')

@section('title', 'Tambah Kategori')
@section('breadcrumb', 'Tambah Kategori')

@section('content')
<div class="mx-auto max-w-4xl space-y-6">

    {{-- Header --}}
    <div>
        <a href="{{ route('admin.categories.index') }}"
           class="inline-flex items-center gap-2 text-sm font-medium text-[#a85e67] hover:underline">
            ← Kembali ke Manajemen Kategori
        </a>

        <h1 class="mt-4 text-2xl font-bold text-[#49352e] sm:text-3xl">
            Tambah Kategori
        </h1>

        <p class="mt-2 text-sm text-[#806d65]">
            Buat kategori baru untuk mengelompokkan produk Early Cake.
        </p>
    </div>

    {{-- Form --}}
    <form action="{{ route('admin.categories.store') }}"
          method="POST"
          class="space-y-6">

        @csrf

        <div class="rounded-2xl border border-[#f0e4dc] bg-white p-5 shadow-md sm:p-7">

            <div class="border-b border-[#f0e4dc] pb-5">
                <h2 class="text-lg font-semibold text-[#49352e]">
                    Informasi Kategori
                </h2>

                <p class="mt-1 text-sm text-[#806d65]">
                    Lengkapi informasi kategori produk.
                </p>
            </div>

            <div class="mt-6 space-y-5">

                {{-- Nama Kategori --}}
                <div>
                    <label for="name"
                           class="mb-2 block text-sm font-medium text-[#49352e]">
                        Nama Kategori <span class="text-red-500">*</span>
                    </label>

                    <input type="text"
                           id="name"
                           name="name"
                           value="{{ old('name') }}"
                           maxlength="255"
                           required
                           placeholder="Contoh: Bento Cake"
                           class="w-full rounded-xl border border-[#eadbd2] bg-white px-4 py-3 text-sm text-[#49352e] focus:border-[#bd7b83] focus:outline-none">

                    @error('name')
                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label for="description"
                           class="mb-2 block text-sm font-medium text-[#49352e]">
                        Deskripsi Kategori
                    </label>

                    <textarea id="description"
                              name="description"
                              rows="5"
                              placeholder="Tuliskan deskripsi singkat kategori..."
                              class="w-full resize-none rounded-xl border border-[#eadbd2] bg-white px-4 py-3 text-sm text-[#49352e] focus:border-[#bd7b83] focus:outline-none">{{ old('description') }}</textarea>

                    <p class="mt-2 text-xs text-[#806d65]">
                        Opsional. Jelaskan jenis produk yang termasuk dalam kategori ini.
                    </p>

                    @error('description')
                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

            </div>
        </div>

        {{-- Tombol --}}
        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

            <a href="{{ route('admin.categories.index') }}"
               class="rounded-xl border border-[#eadbd2] bg-white px-6 py-3 text-center text-sm font-semibold text-[#49352e] transition hover:bg-[#fff8f4]">
                Batal
            </a>

            <button type="submit"
                    class="rounded-xl bg-[#a85e67] px-6 py-3 text-sm font-semibold text-white transition hover:bg-[#914b54]">
                Simpan Kategori
            </button>

        </div>
    </form>
</div>
@endsection
