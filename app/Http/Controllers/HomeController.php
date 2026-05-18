<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

// ============================================================
//  HomeController
//  Halaman publik utama SIPHP
//  Mengirim data pasar & retail ke home view
// ============================================================
class HomeController extends Controller
{
    public function index()
    {
        $pasars  = DB::table('pasars')->orderBy('nama_pasar')->get();
        $retails = DB::table('retails')->latest()->get();

        return view('home', compact('pasars', 'retails'));
    }
}
