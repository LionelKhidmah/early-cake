@extends('layouts.admin')

@section('title', 'Tambah Promo')
@section('breadcrumb', 'Tambah Promo')

@section('content')
<div class="mx-auto max-w-5xl">

    <div class="mb-8">
        <a
            href="{{ route('admin.promos.index') }}"
            class="inline-flex items-center gap-2 text-sm font-semibold text-[#B75C78] hover:underline"
        >
            ← Kembali ke Manajemen Promo
        </a>

        <h1 class="mt-5 text-2xl font-bold text-[#49352E]">
            Tambah Promo Baru
        </h1>

        <p class="mt-2 text-sm text-[#806D65]">
            Buat promo baru yang akan ditampilkan kepada pelanggan Early Cake.
        </p>
    </div>

    @include('admin.promos._form')

</div>
@endsection
