<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin | Early Cake</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#fcf8f5] text-[#3d2622] antialiased">

    <main class="grid min-h-screen lg:grid-cols-2">

        <section class="flex items-center justify-center px-6 py-12 sm:px-12 lg:px-16">
            <div class="w-full max-w-md">

                <a href="{{ url('/') }}" class="text-sm text-[#8a766f] hover:text-[#b34f69]">
                    ← Kembali ke website
                </a>

                <div class="mb-10 mt-10">
                    <p class="text-sm font-semibold uppercase tracking-[0.3em] text-[#b34f69]">
                        Early Cake
                    </p>
                    <h1 class="mt-4 text-3xl font-semibold tracking-tight sm:text-4xl">
                        Selamat datang,
                        <span class="block text-[#b34f69]">Admin.</span>
                    </h1>
                    <p class="mt-3 leading-7 text-[#8a766f]">
                        Masuk untuk mengelola produk, pesanan, dan pembayaran Early Cake.
                    </p>
                </div>

                @if ($errors->any())
                    <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('admin.login.store') }}" method="POST" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="mb-2 block text-sm font-medium">
                            Email admin
                        </label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="Masukkan email admin"
                            class="w-full rounded-xl border border-[#eaddd6] bg-white px-4 py-3 outline-none transition focus:border-[#b34f69] focus:ring-2 focus:ring-[#b34f69]/15"
                        >
                    </div>

                    <div>
                        <label for="password" class="mb-2 block text-sm font-medium">
                            Kata sandi
                        </label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            required
                            autocomplete="current-password"
                            placeholder="Masukkan kata sandi"
                            class="w-full rounded-xl border border-[#eaddd6] bg-white px-4 py-3 outline-none transition focus:border-[#b34f69] focus:ring-2 focus:ring-[#b34f69]/15"
                        >
                    </div>

                    <div class="text-right">
                        <a href="{{ route('password.request') }}" class="text-sm font-medium text-[#b34f69] hover:underline">
                            Lupa kata sandi?
                        </a>
                    </div>

                    <label class="flex items-center gap-3 text-sm text-[#8a766f]">
                        <input
                            type="checkbox"
                            name="remember"
                            value="1"
                            class="rounded border-[#eaddd6] text-[#b34f69] focus:ring-[#b34f69]"
                        >
                        Ingat saya
                    </label>

                    <button
                        type="submit"
                        class="w-full rounded-xl bg-[#51352f] px-5 py-3.5 font-medium text-white transition hover:bg-[#3d2622] focus:outline-none focus:ring-2 focus:ring-[#b34f69] focus:ring-offset-2"
                    >
                        Masuk ke Dashboard
                    </button>
                </form>

                <p class="mt-8 text-center text-xs leading-6 text-[#8a766f]">
                    Halaman khusus pengelola Early Cake.
                    <br>
                    Akses hanya untuk akun admin yang terdaftar.
                </p>
            </div>
        </section>

        <section class="relative hidden min-h-screen overflow-hidden bg-[#f4e5dc] lg:flex lg:items-center lg:justify-center lg:p-12">
            <div class="absolute -right-20 -top-20 h-72 w-72 rounded-full bg-[#e8c5bd]/50 blur-3xl"></div>
            <div class="absolute -bottom-20 -left-20 h-80 w-80 rounded-full bg-[#e8c5bd]/50 blur-3xl"></div>

            <div class="relative max-w-lg">
                <div class="rounded-[2rem] border border-white/70 bg-white/65 p-5 shadow-xl shadow-[#51352f]/10 backdrop-blur-sm">
                    <img
                        src="https://images.unsplash.com/photo-1578985545062-69928b1d9587?auto=format&fit=crop&w=1100&q=85"
                        alt="Kue tart untuk inspirasi Early Cake"
                        class="h-[420px] w-full rounded-[1.5rem] object-cover"
                    >

                    <div class="px-3 pb-3 pt-6">
                        <p class="text-xs font-semibold uppercase tracking-[0.25em] text-[#b34f69]">
                            Made with love
                        </p>
                        <h2 class="mt-3 text-3xl font-semibold leading-snug">
                            Setiap pesanan punya cerita manis.
                        </h2>
                        <p class="mt-3 leading-7 text-[#8a766f]">
                            Kelola pesanan dan hadirkan pengalaman terbaik untuk setiap pelanggan Early Cake.
                        </p>
                    </div>
                </div>
            </div>
        </section>

    </main>

</body>
</html>