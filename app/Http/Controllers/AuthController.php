<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }
        return view('auth.login');
    }

    public function processLogin(Request $request)
    {
        $throttleKey = 'login:' . Str::lower($request->username) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->with('error', "Terlalu banyak percobaan. Tunggu {$seconds} detik.")->withInput();
        }

        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username wajib diisi',
            'password.required' => 'Password wajib diisi',
        ]);

        $user = User::where('username', $request->username)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            RateLimiter::clear($throttleKey);
            Auth::login($user);
            $request->session()->regenerate();
            return $this->redirectByRole($user);
        }

        RateLimiter::hit($throttleKey, 3600);
        $remaining = 5 - RateLimiter::attempts($throttleKey);
        return back()
            ->with('error', "Username atau password salah. Sisa percobaan: {$remaining}")
            ->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Anda telah keluar dari sistem.');
    }

    // ── Redirect sesuai nama route yang terdaftar di web.php ──
    // admin_master → admin.dashboard  (prefix group alias)
    // kepala_dinas → kepala_dinas.dashboard
    // admin_pasar  → admin_pasar.dashboard
    private function redirectByRole(User $user)
    {
        return match ($user->role) {
            'admin_master', 'admin' => redirect()->route('admin.dashboard'),
            'kepala_dinas'          => redirect()->route('kepala_dinas.dashboard'),
            'admin_pasar'           => redirect()->route('admin_pasar.dashboard'),
            default                 => redirect('/'),
        };
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['username' => 'required|string']);
        $user = User::where('username', $request->username)->first();
        if (!$user) {
            return back()->with('error', 'Username tidak ditemukan.');
        }
        $token = Str::random(64);
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->username],
            ['token' => $token, 'created_at' => Carbon::now()]
        );
        $resetUrl = route('password.reset', ['token' => $token, 'email' => $request->username]);
        return back()->with('reset_url', $resetUrl);
    }

    public function showResetPassword(Request $request, string $token)
    {
        $valid = DB::table('password_reset_tokens')->where('token', $token)->first();
        if (!$valid) {
            return redirect()->route('password.request')
                ->with('error', 'Link tidak valid atau sudah digunakan.');
        }
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email', $valid->email ?? ''),
        ]);
    }

    public function processResetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required',
            'password' => 'required|min:6|confirmed',
        ]);
        $check = DB::table('password_reset_tokens')
            ->where(['email' => $request->email, 'token' => $request->token])
            ->first();
        if (!$check || Carbon::parse($check->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->with('error', 'Link sudah kadaluarsa. Silakan minta ulang.');
        }
        User::where('username', $request->email)
            ->update(['password' => Hash::make($request->password)]);
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();
        return redirect()->route('login')->with('success', 'Password berhasil diubah! Silakan login.');
    }
}
