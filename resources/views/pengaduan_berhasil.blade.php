@extends('layouts.app')

@section('content')
<section class="relative w-full min-h-screen bg-white flex items-center justify-center px-10">

    <div class="w-full grid grid-cols-1 md:grid-cols-2 items-center">

        {{-- BAGIAN TEKS --}}
        <div class="space-y-10">
            <h1 class="text-[70px] font-extrabold leading-[1.1]">
                Berhasil<br>Melakukan<br>Pengaduan
            </h1>

            <p class="text-xl font-medium">
                Terima kasih!
            </p>
        </div>

        {{-- GAMBAR --}}
        <div class="flex justify-end">
            <img src="{{ asset('img/donepengajuan.png') }}" 
                 alt="Berhasil Mengajukan Akun"
                 class="w-[85%] -mt-30 object-contain">
        </div>

    </div>

    {{-- TOMBOL KEMBALI DI KANAN BAWAH --}}
    <a href="{{ url('/') }}"
       class="absolute bottom-10 right-20 bg-pink-500 text-white text-lg px-10 py-3 rounded-xl font-semibold shadow-md hover:bg-pink-600 transition">
        Kembali
    </a>

</section>
@endsection
