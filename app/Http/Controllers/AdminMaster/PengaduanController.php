<?php

namespace App\Http\Controllers\AdminMaster;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

// ============================================================
//  AdminMaster\PengaduanController
//  Admin Master melihat & menghapus pengaduan masyarakat
// ============================================================
class PengaduanController extends Controller
{
    public function index()
    {
        $pengaduans = DB::table('pengaduan')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.pengaduan.index', compact('pengaduans'));
    }

    public function destroy(string $id)
    {
        DB::table('pengaduan')->where('id', $id)->delete();
        return back()->with('success', 'Pengaduan berhasil dihapus.');
    }
}
