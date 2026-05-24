<?php

namespace App\Http\Controllers\AdminMaster;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

// ============================================================
//  AdminMaster\HargaController
//  Admin Master kelola harga berdasarkan pasar aktif (session)
//  + Panggil AI prediksi saat toggle ke 'update'
// ============================================================
class HargaController extends Controller
{
    private function getPasarAktif()
    {
        $id = Session::get('pasar_aktif_id');
        if (!$id) {
            $firstPasar = DB::table('pasars')->orderBy('nama_pasar')->first();
            if ($firstPasar) {
                Session::put('pasar_aktif_id', $firstPasar->id);
                return $firstPasar;
            }
            return null;
        }
        return DB::table('pasars')->where('id', $id)->first();
    }

    public function index(string $kategori)
    {
        $pasar = $this->getPasarAktif();
        if (!$pasar) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Pilih pasar terlebih dahulu.');
        }

        $data_harga = DB::table('harga_harians')
            ->where('pasar_id', $pasar->id)
            ->where('kategori', $kategori)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.harga.index', compact('pasar', 'data_harga', 'kategori'));
    }

     public function create(string $kategori)
     {
         return redirect()->route('admin.dashboard')
             ->with('error', 'Input komoditas hanya dapat dilakukan oleh Admin Pasar.');
     }
 
     public function store(Request $request)
     {
         return redirect()->route('admin.dashboard')
             ->with('error', 'Input komoditas hanya dapat dilakukan oleh Admin Pasar.');
     }

    public function destroy(string $id)
    {
        $pasarId = Session::get('pasar_aktif_id');
        DB::table('harga_harians')
            ->where('id', $id)
            ->where('pasar_id', $pasarId)
            ->delete();

        return back()->with('success', 'Data harga berhasil dihapus.');
    }
}
