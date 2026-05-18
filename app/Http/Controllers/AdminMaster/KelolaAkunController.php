<?php

namespace App\Http\Controllers\AdminMaster;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

// ============================================================
//  KelolaAkunController
//  Hanya bisa diakses Admin Master
//  Fitur: CRUD akun Admin Pasar & Kepala Dinas/Kasubag
// ============================================================
class KelolaAkunController extends Controller
{
    // ── ADMIN PASAR ──

    public function indexAdminPasar()
    {
        $admins = User::where('role', 'admin_pasar')->with('pasar')->get();
        $pasars = DB::table('pasars')->get();
        return view('admin_master.kelola.admin_pasar', compact('admins', 'pasars'));
    }

    public function createAdminPasar()
    {
        $pasars = DB::table('pasars')->get();
        // Pasar yang sudah punya admin
        $sudahAda = User::where('role', 'admin_pasar')->pluck('pasar_id')->toArray();
        $pasarTersedia = $pasars->whereNotIn('id', $sudahAda);
        return view('admin_master.kelola.create_admin_pasar', compact('pasarTersedia'));
    }

    public function storeAdminPasar(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'username' => 'required|string|unique:users,username|max:50',
            'password' => 'required|string|min:6',
            'pasar_id' => 'required|exists:pasars,id',
        ], [
            'name.required'      => 'Nama wajib diisi',
            'username.required'  => 'Username wajib diisi',
            'username.unique'    => 'Username sudah dipakai',
            'password.required'  => 'Password wajib diisi',
            'password.min'       => 'Password minimal 6 karakter',
            'pasar_id.required'  => 'Pasar wajib dipilih',
        ]);

        // Cek apakah pasar sudah punya admin
        $sudahAda = User::where('role', 'admin_pasar')
            ->where('pasar_id', $request->pasar_id)->exists();

        if ($sudahAda) {
            return back()->with('error', 'Pasar ini sudah memiliki admin pasar.')->withInput();
        }

        User::create([
            'name'     => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role'     => 'admin_pasar',
            'pasar_id' => $request->pasar_id,
        ]);

        return redirect()->route('admin_master.kelola.admin_pasar')
            ->with('success', 'Akun Admin Pasar berhasil dibuat.');
    }

    public function destroyAdminPasar(string $id)
    {
        $user = User::where('role', 'admin_pasar')->findOrFail($id);
        $user->delete();
        return back()->with('success', 'Akun Admin Pasar berhasil dihapus.');
    }

    public function resetPasswordAdminPasar(Request $request, string $id)
    {
        $request->validate(['password' => 'required|min:6|confirmed']);
        $user = User::where('role', 'admin_pasar')->findOrFail($id);
        $user->update(['password' => Hash::make($request->password)]);
        return back()->with('success', "Password {$user->name} berhasil direset.");
    }

    // ── KEPALA DINAS / KASUBAG ──

    public function indexKepalaDinas()
    {
        $users = User::where('role', 'kepala_dinas')->get();
        return view('admin_master.kelola.kepala_dinas', compact('users'));
    }

    public function createKepalaDinas()
    {
        return view('admin_master.kelola.create_kepala_dinas');
    }

    public function storeKepalaDinas(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'username' => 'required|string|unique:users,username|max:50',
            'password' => 'required|string|min:6',
        ], [
            'name.required'     => 'Nama wajib diisi',
            'username.required' => 'Username wajib diisi',
            'username.unique'   => 'Username sudah dipakai',
            'password.required' => 'Password wajib diisi',
            'password.min'      => 'Password minimal 6 karakter',
        ]);

        User::create([
            'name'     => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role'     => 'kepala_dinas',
            'pasar_id' => null,
        ]);

        return redirect()->route('admin_master.kelola.kepala_dinas')
            ->with('success', 'Akun Kepala Dinas/Kasubag berhasil dibuat.');
    }

    public function destroyKepalaDinas(string $id)
    {
        $user = User::where('role', 'kepala_dinas')->findOrFail($id);
        $user->delete();
        return back()->with('success', 'Akun Kepala Dinas berhasil dihapus.');
    }
}
