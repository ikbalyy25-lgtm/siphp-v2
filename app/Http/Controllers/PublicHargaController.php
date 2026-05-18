<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublicHargaController extends Controller
{
    public function show($kategori, $pasar_id)
    {
        // 1. Ambil Nama Pasar
        $pasar = DB::table('pasars')->where('id', $pasar_id)->first();

        // Jika pasar tidak ditemukan (user iseng ganti ID di URL)
        if (!$pasar) {
            return redirect('/')->with('error', 'Pasar tidak ditemukan');
        }

        // 2. Ambil Harga yang statusnya 'update' (Published)
        // Kita filter berdasarkan pasar_id dan kategori
        $data_harga = DB::table('harga_harians')
            ->where('pasar_id', $pasar_id)
            ->where('kategori', $kategori)
            ->where('status', 'update') // HANYA YANG SUDAH DI-PUBLISH
            ->orderBy('created_at', 'desc')
            ->get();

        // 3. Kirim ke View
        return view('public.harga_show', compact('pasar', 'kategori', 'data_harga'));
    }
}
