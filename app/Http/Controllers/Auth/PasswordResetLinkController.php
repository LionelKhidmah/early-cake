<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = $request->input('email');

        // Hanya akun admin yang boleh menerima email reset.
        $isAdmin = User::where('email', $email)
            ->where('role', 'admin')
            ->exists();

        if ($isAdmin) {
            Password::sendResetLink([
                'email' => $email,
            ]);
        }

        // Respons sama untuk email terdaftar maupun tidak.
        // Mencegah pengunjung mengetahui email admin.
        return back()->with(
            'status',
            'Jika email terdaftar sebagai admin, tautan reset password akan dikirim.'
        );
    }
}
