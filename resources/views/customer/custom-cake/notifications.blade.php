@extends('layouts.customer')

@section('title', 'Notifikasi Custom Cake - Early Cake')

@section('content')
<div class="min-h-screen bg-[#fffaf5] px-4 py-12">
    <div class="mx-auto max-w-3xl">

        <a href="{{ route('custom-cake.index') }}"
           class="text-sm font-medium text-rose-500 hover:text-rose-700">
            ← Kembali ke Custom Cake
        </a>

        <div class="mt-6">
            <p class="text-sm font-semibold uppercase tracking-widest text-rose-400">
                Early Cake
            </p>

            <h1 class="mt-2 text-3xl font-bold text-[#49352f]">
                Notifikasi Custom Cake
            </h1>

            <p class="mt-2 text-sm text-[#907d73]">
                Pantau perkembangan pengajuan dan informasi harga kue custom kamu.
            </p>
        </div>

        <div class="mt-8 rounded-2xl border border-rose-100 bg-white p-5">
            <p class="text-sm text-[#907d73]">Nomor pengajuan</p>

            <p class="mt-1 font-bold text-[#49352f]">
                {{ $customCake->order->order_number ?? '-' }}
            </p>

            <div class="mt-4 flex flex-wrap gap-3 text-sm">
                <span class="rounded-full bg-rose-50 px-3 py-1 text-rose-600">
                    Status: {{ ucfirst($customCake->status) }}
                </span>

                @if($customCake->final_price !== null)
                    <span class="rounded-full bg-amber-50 px-3 py-1 text-amber-700">
                        Harga: Rp{{ number_format((float) $customCake->final_price, 0, ',', '.') }}
                    </span>
                @else
                    <span class="rounded-full bg-gray-100 px-3 py-1 text-gray-600">
                        Harga belum ditentukan
                    </span>
                @endif
            </div>
        </div>

        @if(session('success'))
            <div class="mt-5 rounded-xl bg-green-50 p-4 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        {{-- Push Notification --}}
        <div class="mt-6 rounded-2xl border border-rose-100 bg-white p-5">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="font-bold text-[#49352f]">
                        Aktifkan Notifikasi HP
                    </h2>

                    <p class="mt-1 text-sm leading-6 text-[#907d73]">
                        Dapatkan pemberitahuan langsung ketika ada perubahan status atau harga custom cake.
                    </p>
                </div>

                <button
                    type="button"
                    id="enable-push-notification"
                    data-public-key="{{ config('services.webpush.public_key') }}"
                    data-subscribe-url="{{ route('custom-cake.notifications.subscribe', $token) }}"
                    data-csrf-token="{{ csrf_token() }}"
                    data-sw-url="{{ asset('sw.js') }}"
                    data-sw-scope="{{ rtrim(url('/'), '/') . '/' }}"
                    class="shrink-0 rounded-full bg-rose-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-rose-600 disabled:cursor-not-allowed disabled:opacity-60"
                >
                    Aktifkan Notifikasi
                </button>
            </div>

            <p class="mt-3 text-xs text-[#a3948c]">
                Izinkan notifikasi pada browser agar pembaruan dapat diterima.
            </p>
        </div>

        {{-- Riwayat Notifikasi --}}
        <div class="mt-8">
            <h2 class="text-lg font-bold text-[#49352f]">
                Riwayat Notifikasi
            </h2>

            <div class="mt-4 space-y-4">
                @forelse($notifications as $notification)
                    <div class="rounded-2xl border border-rose-100 bg-white p-5">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <h3 class="font-semibold text-[#49352f]">
                                    {{ $notification->title }}
                                </h3>

                                <p class="mt-2 text-sm leading-6 text-[#76645b]">
                                    {{ $notification->message }}
                                </p>

                                <p class="mt-3 text-xs text-[#a3948c]">
                                    {{ $notification->created_at->format('d M Y, H:i') }} WIB
                                </p>
                            </div>

                            @if(!$notification->is_read)
                                <span class="shrink-0 rounded-full bg-rose-100 px-2.5 py-1 text-xs font-semibold text-rose-600">
                                    Baru
                                </span>
                            @endif
                        </div>

                        @if(!$notification->is_read)
                            <form
                                method="POST"
                                action="{{ route('custom-cake.notifications.read', [
                                    'token' => $token,
                                    'notification' => $notification->id,
                                ]) }}"
                                class="mt-4"
                            >
                                @csrf
                                @method('PATCH')

                                <button
                                    type="submit"
                                    class="text-sm font-semibold text-rose-500 hover:text-rose-700"
                                >
                                    Tandai sudah dibaca
                                </button>
                            </form>
                        @endif
                    </div>
                @empty
                    <div class="rounded-2xl border border-dashed border-rose-200 bg-white p-10 text-center">
                        <p class="font-semibold text-[#49352f]">
                            Belum ada notifikasi
                        </p>

                        <p class="mt-2 text-sm text-[#907d73]">
                            Informasi perkembangan pengajuanmu akan muncul di sini.
                        </p>
                    </div>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/custom-cake-push.js') }}" defer></script>
@endpush
