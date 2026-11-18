@extends('layouts.admin')

@section('title', 'Edit Pilihan Kue Kustom')
@section('breadcrumb', 'Edit Pilihan Kue Kustom')

@section('content')
<div class="mx-auto max-w-4xl space-y-6">

    <div>
        <a href="{{ route('admin.custom-cake-options.index') }}"
           class="text-sm font-medium text-[#a85e67] hover:underline">
            ← Kembali ke Pilihan Kue Kustom
        </a>

        <h1 class="mt-4 text-2xl font-bold text-[#49352e]">
            Edit Pilihan
        </h1>
        <p class="mt-2 text-sm text-[#806d65]">
            Perbarui informasi {{ $customCakeOption->name }}.
        </p>
    </div>

    <form action="{{ route('admin.custom-cake-options.update', $customCakeOption) }}"
          method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="space-y-5 rounded-2xl border border-[#f0e4dc] bg-white p-5 shadow-sm sm:p-7">

            <div>
                <label for="option_type" class="mb-2 block text-sm font-medium text-[#49352e]">
                    Jenis Pilihan <span class="text-red-500">*</span>
                </label>
                <select id="option_type" name="option_type" required
                        class="w-full rounded-xl border border-[#eadbd2] bg-white px-4 py-3 text-sm text-[#49352e] focus:border-[#bd7b83] focus:outline-none">
                    <option value="flavor" @selected(old('option_type', $customCakeOption->option_type) === 'flavor')>Rasa</option>
                    <option value="size" @selected(old('option_type', $customCakeOption->option_type) === 'size')>Ukuran</option>
                    <option value="decoration" @selected(old('option_type', $customCakeOption->option_type) === 'decoration')>Dekorasi</option>
                </select>
                @error('option_type')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="name" class="mb-2 block text-sm font-medium text-[#49352e]">
                    Nama Pilihan <span class="text-red-500">*</span>
                </label>
                <input type="text" id="name" name="name"
                       value="{{ old('name', $customCakeOption->name) }}"
                       maxlength="100" required
                       class="w-full rounded-xl border border-[#eadbd2] px-4 py-3 text-sm text-[#49352e] focus:border-[#bd7b83] focus:outline-none">
                @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="mb-2 block text-sm font-medium text-[#49352e]">
                    Deskripsi
                </label>
                <textarea id="description" name="description" rows="4"
                          class="w-full resize-none rounded-xl border border-[#eadbd2] px-4 py-3 text-sm text-[#49352e] focus:border-[#bd7b83] focus:outline-none">{{ old('description', $customCakeOption->description) }}</textarea>
                @error('description')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label for="additional_price" class="mb-2 block text-sm font-medium text-[#49352e]">
                        Biaya Tambahan (Rp) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="additional_price" name="additional_price"
                           value="{{ old('additional_price', $customCakeOption->additional_price) }}"
                           min="0" step="1000" required
                           class="w-full rounded-xl border border-[#eadbd2] px-4 py-3 text-sm text-[#49352e] focus:border-[#bd7b83] focus:outline-none">
                    @error('additional_price')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="sort_order" class="mb-2 block text-sm font-medium text-[#49352e]">
                        Urutan Tampilan
                    </label>
                    <input type="number" id="sort_order" name="sort_order"
                           value="{{ old('sort_order', $customCakeOption->sort_order) }}"
                           min="0"
                           class="w-full rounded-xl border border-[#eadbd2] px-4 py-3 text-sm text-[#49352e] focus:border-[#bd7b83] focus:outline-none">
                    @error('sort_order')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="is_active" class="mb-2 block text-sm font-medium text-[#49352e]">
                    Status <span class="text-red-500">*</span>
                </label>
                <select id="is_active" name="is_active" required
                        class="w-full rounded-xl border border-[#eadbd2] bg-white px-4 py-3 text-sm text-[#49352e] focus:border-[#bd7b83] focus:outline-none">
                    <option value="1" @selected(old('is_active', (string) (int) $customCakeOption->is_active) == '1')>Aktif</option>
                    <option value="0" @selected(old('is_active', (string) (int) $customCakeOption->is_active) == '0')>Nonaktif</option>
                </select>
                @error('is_active')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
            <a href="{{ route('admin.custom-cake-options.index') }}"
               class="rounded-xl border border-[#eadbd2] bg-white px-6 py-3 text-center text-sm font-semibold text-[#49352e] hover:bg-[#fff8f4]">
                Batal
            </a>
            <button type="submit"
                    class="rounded-xl bg-[#a85e67] px-6 py-3 text-sm font-semibold text-white hover:bg-[#914b54]">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
