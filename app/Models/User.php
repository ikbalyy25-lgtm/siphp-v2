<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// ============================================================
//  MODEL: User
//  Satu model untuk semua role:
//  admin_master | kepala_dinas | admin_pasar
// ============================================================
class User extends Authenticatable
{
    use Notifiable;

    protected $table    = 'users';
    protected $fillable = ['name', 'username', 'password', 'role', 'pasar_id'];
    protected $hidden   = ['password', 'remember_token'];

    // ── Helper role check ──
    public function isAdminMaster(): bool   { return $this->role === 'admin_master'; }
    public function isKepalaDinas(): bool   { return $this->role === 'kepala_dinas'; }
    public function isAdminPasar(): bool    { return $this->role === 'admin_pasar'; }

    // ── Relasi ke pasar (untuk admin_pasar) ──
    public function pasar()
    {
        return $this->belongsTo(Pasar::class, 'pasar_id');
    }
}
