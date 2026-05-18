<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// ============================================================
//  MIDDLEWARE: RoleMiddleware
//  Dipakai di routes untuk batasi akses per role
//
//  Penggunaan di routes:
//  ->middleware('role:admin_master')
//  ->middleware('role:admin_master,kepala_dinas')
//  ->middleware('role:admin_master,admin_pasar')
// ============================================================
class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): mixed
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        
        // Kompatibilitas role lama: petakan 'admin' menjadi 'admin_master'
        $userRole = $user->role === 'admin' ? 'admin_master' : $user->role;

        if (!in_array($userRole, $roles)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
