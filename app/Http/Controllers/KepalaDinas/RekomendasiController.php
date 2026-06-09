<?php

namespace App\Http\Controllers\KepalaDinas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Symfony\Component\Process\Process;

// ============================================================
//  KepalaDinas\RekomendasiController
//  Kepala Dinas melihat rekomendasi harga optimal XGBoost
// ============================================================
class RekomendasiController extends Controller
{
    public function index(Request $request)
    {
        $kategori = $request->get('kategori', 'pokok');

        // Ambil semua komoditas unik untuk dropdown
        $komoditasList = DB::table('harga_harians')
            ->where('status', 'published')
            ->where('kategori', $kategori)
            ->distinct()
            ->orderBy('nama_barang')
            ->pluck('nama_barang');

        $ringkasan = [
            'total_komoditas' => $komoditasList->count(),
            'total_pasar' => DB::table('pasars')->count(),
            'terakhir_update' => DB::table('harga_harians')
                ->where('status', 'published')
                ->where('kategori', $kategori)
                ->max('tanggal'),
        ];

        return view('kepala_dinas.rekomendasi', compact('kategori', 'komoditasList', 'ringkasan'));
    }

    public function analyze(Request $request)
    {
        $namaBarang = $request->get('nama_barang');
        $kategori = $request->get('kategori', 'pokok');

        if (!$namaBarang) {
            return response()->json(['success' => false, 'message' => 'Nama barang harus diisi']);
        }

        // Ambil data historis dari database
        $historis = DB::table('harga_harians')
            ->where('status', 'published')
            ->where('nama_barang', $namaBarang)
            ->where('kategori', $kategori)
            ->orderBy('tanggal', 'asc')
            ->get(['tanggal', 'harga_hari_ini as harga']);

        if ($historis->count() < 5) {
            return response()->json([
                'success' => false, 
                'message' => 'Data historis tidak mencukupi (minimal 5 data).'
            ]);
        }

        $pythonPath = env('PYTHON_PATH', 'python');
        $scriptPath = base_path('ai_engine/xgboost_predict.py');

        // Pass system environment variables to fix Python init error on Windows
        $env = [
            'SystemRoot' => getenv('SystemRoot') ?: 'C:\\WINDOWS',
            'PATH' => getenv('PATH'),
            'TEMP' => getenv('TEMP'),
            'TMP' => getenv('TMP'),
            'USERPROFILE' => getenv('USERPROFILE')
        ];

        $process = new Process([$pythonPath, $scriptPath], null, $env);
        $process->setInput(json_encode($historis->toArray()));
        $process->run();

        if (!$process->isSuccessful()) {
            return response()->json([
                'success' => false,
                'message' => 'Error menjalankan model AI: ' . $process->getErrorOutput()
            ]);
        }

        $output = json_decode($process->getOutput(), true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($output)) {
            return response()->json([
                'success' => false,
                'message' => 'Output invalid dari Python script: ' . $process->getOutput()
            ]);
        }

        if (isset($output['error'])) {
            return response()->json(['success' => false, 'message' => $output['error']]);
        }

        return response()->json($output);
    }
}
