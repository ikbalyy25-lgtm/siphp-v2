<?php

namespace App\Http\Controllers;

use App\Models\Retail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

// ============================================================
//  AdminRetailController
//  CRUD toko retail yang tampil di portal publik
// ============================================================
class AdminRetailController extends Controller
{
    public function index()
    {
        $retails = Retail::latest()->get();
        return view('admin.retail.index', compact('retails'));
    }

    public function create()
    {
        return view('admin.retail.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_toko' => 'required|string|max:100',
            'kategori'  => 'required|string|max:50',
            'alamat'    => 'required|string',
            'kontak'    => 'nullable|string|max:20',
            'jam_buka'  => 'nullable|string|max:50',
            'link_maps' => 'nullable|url',
            'gambar'    => 'nullable|image|max:2048',
        ], [
            'nama_toko.required' => 'Nama toko wajib diisi',
            'kategori.required'  => 'Kategori wajib diisi',
            'alamat.required'    => 'Alamat wajib diisi',
            'gambar.image'       => 'File harus berupa gambar',
            'gambar.max'         => 'Ukuran gambar maksimal 2MB',
            'link_maps.url'      => 'Link maps harus berupa URL yang valid',
        ]);

        $data = $request->only(['nama_toko', 'kategori', 'alamat', 'kontak', 'jam_buka', 'link_maps']);

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('retail', 'public');
        }

        Retail::create($data);

        return redirect()->route('admin.retail.index')
            ->with('success', 'Toko retail berhasil ditambahkan.');
    }

    public function destroy(string $id)
    {
        $retail = Retail::findOrFail($id);

        if ($retail->gambar) {
            Storage::disk('public')->delete($retail->gambar);
        }

        $retail->delete();

        return back()->with('success', 'Toko retail berhasil dihapus.');
    }
}
