@extends('layouts.admin')

@section('title', 'Edit Promo')
@section('breadcrumb', 'Edit Promo')

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
            Edit Promo
        </h1>

        <p class="mt-2 text-sm text-[#806D65]">
            Perbarui informasi promo {{ $promo->title }}.
        </p>
    </div>

    @include('admin.promos._form')

</div>
@endsection
