<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - SIPHP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="min-h-screen flex items-center justify-center px-4" style="background: linear-gradient(135deg, #f0fff4, #e8f8e8);">

    <div class="w-full max-w-md">
        <div class="rounded-2xl overflow-hidden shadow-2xl" style="border: 1px solid rgba(208,240,192,0.5);">

            {{-- Header --}}
            <div class="px-8 py-7 text-center" style="background: linear-gradient(135deg, #1e3a2f, #2d6a4f);">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-4"
                    style="background: rgba(208,240,192,0.15);">
                    <i class="fas fa-key text-2xl" style="color: #d0f0c0;"></i>
                </div>
                <h1 class="text-xl font-extrabold" style="color: #d0f0c0;">Buat Password Baru</h1>
                <p class="text-xs mt-1" style="color: rgba(208,240,192,0.6);">Pastikan password baru mudah diingat</p>
            </div>

            {{-- Body --}}
            <div class="px-8 py-7 bg-white">

                @if($errors->any())
                    <div class="mb-5 px-4 py-3 rounded-xl text-sm font-medium"
                        style="background: #fef2f2; color: #dc2626; border: 1px solid #fca5a5;">
                        <ul class="list-disc pl-4 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('password.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    {{-- Email --}}
                    <div class="mb-4">
                        <label class="block text-xs font-bold uppercase tracking-wider mb-2" style="color: #1e3a2f;">Username</label>
                        <div class="relative">
                            <i class="fas fa-user absolute left-3.5 top-1/2 -translate-y-1/2 text-sm" style="color: #4ade80;"></i>
                            <input type="text" name="email" value="{{ $email ?? old('email') }}" required
                                placeholder="Username Anda"
                                class="w-full pl-10 pr-4 py-3 rounded-xl text-sm border focus:outline-none focus:ring-2 transition"
                                style="border-color: #d0f0c0;">
                        </div>
                    </div>

                    {{-- Password Baru --}}
                    <div class="mb-4">
                        <label class="block text-xs font-bold uppercase tracking-wider mb-2" style="color: #1e3a2f;">Password Baru</label>
                        <div class="relative">
                            <i class="fas fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-sm" style="color: #4ade80;"></i>
                            <input type="password" name="password" id="password" required
                                placeholder="Minimal 6 karakter"
                                class="w-full pl-10 pr-10 py-3 rounded-xl text-sm border focus:outline-none focus:ring-2 transition"
                                style="border-color: #d0f0c0;">
                            <button type="button" onclick="togglePass('password','eyePass')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-eye text-sm" id="eyePass"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Konfirmasi --}}
                    <div class="mb-6">
                        <label class="block text-xs font-bold uppercase tracking-wider mb-2" style="color: #1e3a2f;">Konfirmasi Password</label>
                        <div class="relative">
                            <i class="fas fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-sm" style="color: #4ade80;"></i>
                            <input type="password" name="password_confirmation" id="passConf" required
                                placeholder="Ketik ulang password baru"
                                class="w-full pl-10 pr-10 py-3 rounded-xl text-sm border focus:outline-none focus:ring-2 transition"
                                style="border-color: #d0f0c0;">
                            <button type="button" onclick="togglePass('passConf','eyeConf')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-eye text-sm" id="eyeConf"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full py-3 rounded-xl font-bold text-sm shadow-lg transition hover:opacity-90"
                        style="background: linear-gradient(135deg, #1e3a2f, #2d6a4f); color: #d0f0c0;">
                        <i class="fas fa-check mr-2"></i> SIMPAN PASSWORD BARU
                    </button>
                </form>

                <div class="text-center mt-5">
                    <a href="{{ route('login') }}" class="text-xs text-gray-400 hover:text-gray-600 transition">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali ke Login
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
    function togglePass(fieldId, eyeId) {
        const f = document.getElementById(fieldId);
        const e = document.getElementById(eyeId);
        f.type = f.type === 'password' ? 'text' : 'password';
        e.className = f.type === 'text' ? 'fas fa-eye-slash text-sm' : 'fas fa-eye text-sm';
    }
    </script>
</body>
</html>
