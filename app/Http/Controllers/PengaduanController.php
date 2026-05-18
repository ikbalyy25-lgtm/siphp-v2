<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use Illuminate\Http\Request;

class PengaduanController extends Controller
{
    public function index()
    {
        return view('pengaduan');
    }  

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'          => ['required', 'regex:/^[A-Za-z ]+$/'],
            'pasar'         => ['required', 'string'],
            'nomor_telepon' => ['required', 'numeric'],
            'kategori'      => 'required',
            'pesan'         => ['required', 'max:300'],
        ], [
            'nama.required'          => 'Nama wajib diisi',
            'nama.regex'             => 'Nama hanya boleh huruf & spasi',
            'pasar.required'         => 'Pasar wajib dipilih',
            'nomor_telepon.required' => 'Nomor telepon wajib diisi',
            'nomor_telepon.numeric'  => 'Nomor telepon hanya angka',
            'kategori.required'      => 'Kategori wajib dipilih',
            'pesan.required'         => 'Pesan wajib diisi',
            'pesan.max'              => 'Pesan maksimal 300 karakter',
        ]);

        Pengaduan::create($validated);

        return view('pengaduan_berhasil');
    }
}
