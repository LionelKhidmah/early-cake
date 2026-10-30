<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Lupa Password — Early Cake</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#fff9f5] text-[#49352e]">

    <main class="flex min-h-screen items-center justify-center px-4 py-12">

        <div class="w-full max-w-md">

            <div class="mb-8 text-center">
                <a href="{{ route('home') }}"
                   class="text-3xl font-bold tracking-tight text-[#b34f69]">
                    Early Cake
                </a>

                <p class="mt-2 text-sm text-[#8a766f]">
                    Pemulihan akun administrator
                </p>
            </div>

            <div class="rounded-3xl border border-[#f0dfd7] bg-white p-7 shadow-xl shadow-[#b34f69]/5 sm:p-9">

                <h1 class="text-center text-2xl font-bold">
                    Lupa Password?
                </h1>

                <p class="mt-3 text-center text-sm leading-relaxed text-[#8a766f]">
                    Masukkan email admin yang terdaftar.
                    Kami akan mengirimkan tautan untuk mengatur ulang password.
                </p>

                @if (session('status'))
                    <div class="mt-6 rounded-xl bg-green-50 p-4 text-sm text-green-700">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mt-6 rounded-xl bg-red-50 p-4 text-sm text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST"
                      action="{{ route('admin.password.email') }}"
                      class="mt-7 space-y-5">

                    @csrf

                    <div>
                        <label for="email"
                               class="mb-2 block text-sm font-semibold">
                            Email Admin
                        </label>

                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="email"
                            placeholder="admin@example.com"
                            class="w-full rounded-xl border border-[#eaddd6] bg-white px-4 py-3 outline-none transition focus:border-[#b34f69] focus:ring-2 focus:ring-[#b34f69]/15"
                        >
                    </div>

                    <button
                        type="submit"
                        class="w-full rounded-xl bg-[#b34f69] px-5 py-3 font-semibold text-white transition hover:bg-[#963d56]">
                        Kirim Tautan Reset
                    </button>
                </form>

                <div class="mt-7 text-center">
                    <a href="{{ route('admin.login') }}"
                       class="text-sm font-medium text-[#b34f69] hover:underline">
                        ← Kembali ke Login
                    </a>
                </div>
            </div>

            <p class="mt-6 text-center text-xs text-[#8a766f]">
                © {{ date('Y') }} Early Cake. Admin Area.
            </p>

        </div>
    </main>

</body>
</html>
