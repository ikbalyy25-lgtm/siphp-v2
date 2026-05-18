<?php

namespace App\Http\Controllers\AdminMaster;

use App\Http\Controllers\Controller;
use App\Models\InputPedagang;
use App\Models\HargaHarian;
use Illuminate\Support\Facades\DB;

// ============================================================
//  AdminMaster\AntrianController
//  Kelola antrian input harga dari Admin Pasar:
//  - Lihat semua pending
//  - Approve → status 'published', tampil ke publik
//  - Tolak   → hapus dari antrian
//  - Approve semua sekaligus (bulk approve)
// ============================================================
class AntrianController extends Controller
{
    public function index()
    {
        $antrian = HargaHarian::with(['pasar', 'inputPedagang.user'])
            ->pending()
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('pasar_id');

        $totalPending = HargaHarian::pending()->count();

        return view('admin.antrian.index', compact('antrian', 'totalPending'));
    }

    // Approve satu data
    public function approve(int $id)
    {
        $harga = HargaHarian::pending()->findOrFail($id);

        DB::transaction(function () use ($harga) {
            $harga->update(['status' => 'published']);

            // Update status di tabel input_pedagang
            if ($harga->input_pedagang_id) {
                InputPedagang::where('id', $harga->input_pedagang_id)
                    ->update(['status' => 'diapprove']);
            }
        });

        return back()->with('success', "Harga {$harga->nama_barang} dari Pasar {$harga->pasar->nama_pasar} berhasil disetujui.");
    }

    // Tolak satu data
    public function tolak(int $id)
    {
        $harga = HargaHarian::pending()->findOrFail($id);
        $namaBarang = $harga->nama_barang;
        $namapasar  = $harga->pasar->nama_pasar;

        DB::transaction(function () use ($harga) {
            if ($harga->input_pedagang_id) {
                InputPedagang::where('id', $harga->input_pedagang_id)
                    ->update(['status' => 'ditolak']);
            }
            $harga->delete();
        });

        return back()->with('info', "Harga {$namaBarang} dari Pasar {$namapasar} ditolak dan dihapus.");
    }

    // Approve semua pending sekaligus
    public function approveAll()
    {
        $pending = HargaHarian::pending()->get();
        $count   = $pending->count();

        if ($count === 0) {
            return back()->with('info', 'Tidak ada data yang perlu disetujui.');
        }

        DB::transaction(function () use ($pending) {
            $ids = $pending->pluck('id');
            HargaHarian::whereIn('id', $ids)->update(['status' => 'published']);

            $inputIds = $pending->pluck('input_pedagang_id')->filter();
            if ($inputIds->count()) {
                InputPedagang::whereIn('id', $inputIds)->update(['status' => 'diapprove']);
            }
        });

        return back()->with('success', "{$count} data harga berhasil disetujui sekaligus.");
    }
}
