<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Menampilkan halaman reset password admin.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', [
            'request' => $request,
        ]);
    }

    /**
     * Memproses reset password khusus akun admin.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => [
                'required',
                'confirmed',
                Rules\Password::defaults(),
            ],
        ]);

        // Pastikan email terdaftar sebagai admin.
        $admin = User::query()
            ->where('email', $validated['email'])
            ->where('role', 'admin')
            ->first();

        if (! $admin) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Tautan reset password tidak valid atau sudah kedaluwarsa.',
                ]);
        }

        // Laravel memvalidasi token reset dan masa berlakunya.
        $status = Password::reset(
            $request->only(
                'email',
                'password',
                'password_confirmation',
                'token'
            ),
            function (User $user, string $password) {
                // Pemeriksaan tambahan sebelum mengubah password.
                if (! $user->isAdmin()) {
                    return;
                }

                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()
                ->route('admin.login')
                ->with(
                    'status',
                    'Password berhasil diubah. Silakan login kembali.'
                );
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => 'Tautan reset password tidak valid atau sudah kedaluwarsa.',
            ]);
    }
}
