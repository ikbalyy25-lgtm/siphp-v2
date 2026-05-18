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
        if (!$id) return null;
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
        $pasar = $this->getPasarAktif();
        if (!$pasar) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Pilih pasar terlebih dahulu.');
        }
        return view('admin.harga.create', compact('pasar', 'kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang'    => 'required|string',
            'tanggal'        => 'required|date',
            'harga_hari_ini' => 'required|numeric|min:0',
            'kategori'       => 'required|in:pokok,subsidi,penting',
        ]);

        $pasarId = Session::get('pasar_aktif_id');

        DB::table('harga_harians')->insert([
            'pasar_id'       => $pasarId,
            'nama_barang'    => $request->nama_barang,
            'kategori'       => $request->kategori,
            'tanggal'        => $request->tanggal,
            'harga_hari_ini' => $request->harga_hari_ini,
            'status'         => 'pending',
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        return redirect()->route('admin.harga.index', $request->kategori)
            ->with('success', 'Data harga berhasil disimpan.');
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
