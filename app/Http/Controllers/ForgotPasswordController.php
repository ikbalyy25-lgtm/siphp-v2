<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class ForgotPasswordController extends Controller
{
    // 1. Tampilkan Form Input Email
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    // 2. Kirim Link Reset ke Email
    public function sendResetLinkEmail(Request $request)
    {
        // Validasi input
        $request->validate(['email' => 'required|email']);

        // Menggunakan broker 'pedagangs' (Wajib setting di config/auth.php)
        $status = Password::broker('pedagangs')->sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with(['status' => __($status)]);
        }

        // Kalau gagal, kemungkinan email tidak ada di tabel pedagangs
        return back()->withErrors(['email' => __($status)]);
    }

    // 3. Tampilkan Form Ubah Password (setelah klik link email)
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.reset-password')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    // 4. Proses Simpan Password Baru ke Database
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::broker('pedagangs')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', 'Password berhasil diubah! Silakan login.');
        }

        return back()->withErrors(['email' => [__($status)]]);
    }
}
