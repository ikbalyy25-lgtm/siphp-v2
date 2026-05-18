@extends('layouts.app')

@section('content')
    <main class="w-full min-h-screen bg-gray-50 p-6 md:p-10 flex flex-col items-center">

        <div class="w-full max-w-2xl">

            <div class="mb-6 flex justify-between items-center">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800">Buat Akun Pedagang</h2>
                    <p class="text-gray-500 mt-1">Lengkapi data login untuk calon pedagang ini.</p>
                </div>
                <a href="{{ route('admin.pengajuan.index') }}"
                    class="text-gray-500 hover:text-blue-600 font-medium transition flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i> Batal
                </a>
            </div>

            <div class="bg-white p-8 rounded-xl shadow-lg border border-gray-100">

                <form action="{{ route('admin.pengajuan.approve', $calon->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="kontak" value="{{ $calon->kontak }}">

                    <div class="bg-blue-50 p-5 rounded-lg mb-8 border border-blue-100">
                        <h3 class="font-bold text-blue-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-user-circle"></i> Data Pengaju
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-1">Nama Lengkap</label>
                                <input type="text" name="nama_pedagang" value="{{ $calon->nama }}" readonly
                                    class="w-full bg-transparent border-b border-blue-200 font-bold text-gray-800 py-1 focus:outline-none cursor-default">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-1">Kontak / WA</label>
                                <input type="text" value="{{ $calon->kontak }}" readonly
                                    class="w-full bg-transparent border-b border-blue-200 font-medium text-gray-800 py-1 focus:outline-none cursor-default">
                            </div>
                            <div class="col-span-1 md:col-span-2">
                                <label class="block text-xs font-bold text-gray-500 mb-1">Lokasi yang diinginkan</label>
                                <p class="text-sm text-gray-700 italic bg-white/50 p-2 rounded border border-blue-100">
                                    "{{ $calon->lokasi_penjualan }}"
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-5">

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Tetapkan Pasar <span
                                    class="text-red-500">*</span></label>
                            <div class="relative">
                                <i class="fas fa-store absolute left-3 top-3 text-gray-400"></i>
                                <select name="pasar_id" required
                                    class="w-full pl-10 border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition appearance-none bg-white">
                                    <option value="">-- Pilih Pasar Resmi --</option>
                                    @foreach ($pasars as $p)
                                        <option value="{{ $p->id }}">{{ $p->nama_pasar }}</option>
                                    @endforeach
                                </select>
                                <i
                                    class="fas fa-chevron-down absolute right-4 top-3.5 text-xs text-gray-400 pointer-events-none"></i>
                            </div>
                            <p class="text-xs text-gray-400 mt-1">Pastikan sesuai dengan lokasi yang diinginkan pengaju di
                                atas.</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Username Baru <span
                                        class="text-red-500">*</span></label>
                                <div class="relative">
                                    <i class="fas fa-user absolute left-3 top-3 text-gray-400"></i>
                                    <input type="text" name="username" placeholder="Contoh: alya_pedagang" required
                                        class="w-full pl-10 border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Password Baru <span
                                        class="text-red-500">*</span></label>
                                <div class="relative">
                                    <i class="fas fa-lock absolute left-3 top-3 text-gray-400"></i>
                                    <input type="text" name="password" placeholder="Contoh: alya123" required
                                        class="w-full pl-10 border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end">
                        <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition transform hover:-translate-y-1 flex items-center gap-2">
                            <i class="fas fa-check-circle"></i> Buat Akun Sekarang
                        </button>
                    </div>

                </form>
            </div>

            <div class="text-center mt-10 text-gray-400 text-xs">
                &copy; {{ date('Y') }} Sistem Informasi Harga Pasar
            </div>

        </div>

    </main>
@endsection
