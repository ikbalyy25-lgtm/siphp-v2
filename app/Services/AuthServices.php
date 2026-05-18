<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AuthServices
{
    /*
    |--------------------------------------------------------------------------
    | Method Granular (Untuk SIPHPTest)
    |--------------------------------------------------------------------------
    */

    public function isBothEmpty($username, $password)
    {
        return empty($username) && empty($password);
    }

    public function isUsernameEmpty($username)
    {
        return empty($username);
    }

    public function isPasswordEmpty($password)
    {
        return empty($password);
    }

    public function isPasswordHasInvalidSpace($password)
    {
        // Jika di-trim beda panjangnya, berarti ada spasi di awal/akhir
        return trim($password) !== $password;
    }

    public function isPasswordCorrect($inputPassword, $hashedPassword)
    {
        return Hash::check($inputPassword, $hashedPassword);
    }

    /*
    |--------------------------------------------------------------------------
    | Method Utama (Untuk ServicesTest)
    |--------------------------------------------------------------------------
    */

    public function login($username, $password)
    {
        // 1. Validasi Input Kosong (Menggunakan method granular di atas)
        if ($this->isUsernameEmpty($username) || $this->isPasswordEmpty($password)) {
            return ['success' => false, 'message' => 'Username/Password kosong'];
        }

        // 2. Validasi Spasi
        if ($this->isPasswordHasInvalidSpace($password)) {
            return ['success' => false, 'message' => 'Password mengandung spasi'];
        }

        // 3. Cari User di Database
        $admin = Admin::where('username', $username)->first();

        // 4. Cek User dan Password
        if (!$admin || !$this->isPasswordCorrect($password, $admin->password)) {
            return ['success' => false, 'message' => 'Kredensial salah'];
        }

        // 5. Login Berhasil
        return [
            'success' => true,
            'role' => 'admin',
            'user' => $admin
        ];
    }
}
