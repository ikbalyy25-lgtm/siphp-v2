<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password — SIPHP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: #f0faf4; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
    </style>
</head>
<body>
<div style="width:100%;max-width:420px;">

    <div style="border-radius:24px;overflow:hidden;box-shadow:0 16px 48px rgba(45,106,79,0.15);border:1.5px solid #d1e8d8;">

        {{-- Header --}}
        <div style="background:linear-gradient(135deg,#1e3a2f,#2d6a4f);padding:36px 32px;text-align:center;">
            <div style="width:60px;height:60px;background:rgba(208,240,192,0.15);border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                <i class="fas fa-lock-open" style="font-size:24px;color:#d0f0c0;"></i>
            </div>
            <h1 style="font-size:20px;font-weight:800;color:#d0f0c0;margin:0 0 6px;">Lupa Password?</h1>
            <p style="font-size:13px;color:rgba(208,240,192,0.65);margin:0;">Masukkan username untuk mendapatkan link reset</p>
        </div>

        {{-- Body --}}
        <div style="background:white;padding:32px;">

            {{-- Sukses: tampilkan link reset --}}
            @if(session('reset_url'))
            <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-left:4px solid #22c55e;border-radius:12px;padding:16px;margin-bottom:20px;">
                <div style="font-weight:700;color:#15803d;font-size:13px;margin-bottom:10px;">
                    <i class="fas fa-check-circle" style="margin-right:6px;"></i>Username ditemukan!
                </div>
                <p style="font-size:12px;color:#166534;margin-bottom:12px;">Salin link di bawah ini dan buka di browser untuk reset password:</p>
                <div style="background:#dcfce7;border-radius:8px;padding:10px 12px;font-size:11px;color:#15803d;word-break:break-all;font-family:monospace;margin-bottom:12px;">
                    {{ session('reset_url') }}
                </div>
                <button onclick="copyLink('{{ session('reset_url') }}')"
                    style="background:linear-gradient(135deg,#1e3a2f,#2d6a4f);color:#d0f0c0;border:none;border-radius:9px;padding:9px 18px;font-size:12px;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:6px;font-family:'Plus Jakarta Sans',sans-serif;">
                    <i class="fas fa-copy"></i> Salin Link
                </button>
            </div>
            @endif

            @if(session('error'))
            <div style="background:#fef2f2;border:1px solid #fecaca;border-left:4px solid #ef4444;border-radius:12px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#dc2626;display:flex;align-items:center;gap:8px;">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
            @endif

            <form action="{{ route('password.email') }}" method="POST">
                @csrf
                <div style="margin-bottom:20px;">
                    <label style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.8px;color:#5a8a6a;display:block;margin-bottom:7px;">
                        Username
                    </label>
                    <div style="position:relative;">
                        <i class="fas fa-user" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#a3c4aa;font-size:13px;pointer-events:none;"></i>
                        <input type="text" name="username" required autofocus
                            placeholder="Masukkan username Anda"
                            value="{{ old('username') }}"
                            style="width:100%;padding:12px 14px 12px 40px;border-radius:12px;font-size:14px;border:1.5px solid #d1e8d8;background:#f8fdf9;color:#1a3a2a;outline:none;font-family:'Plus Jakarta Sans',sans-serif;transition:border-color 0.2s;"
                            onfocus="this.style.borderColor='#2d6a4f';this.style.boxShadow='0 0 0 3px rgba(45,106,79,0.1)'"
                            onblur="this.style.borderColor='#d1e8d8';this.style.boxShadow='none'">
                    </div>
                </div>

                <button type="submit"
                    style="width:100%;padding:13px;border:none;border-radius:12px;background:linear-gradient(135deg,#1e3a2f,#2d6a4f);color:#d0f0c0;font-weight:800;font-size:14px;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;font-family:'Plus Jakarta Sans',sans-serif;box-shadow:0 4px 14px rgba(45,106,79,0.25);">
                    <i class="fas fa-paper-plane"></i> DAPATKAN LINK RESET
                </button>
            </form>

            <div style="text-align:center;margin-top:20px;">
                <a href="{{ route('login') }}" style="font-size:12px;color:#8aaa9a;text-decoration:none;">
                    <i class="fas fa-arrow-left" style="margin-right:5px;"></i>Kembali ke Login
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function copyLink(url) {
    navigator.clipboard.writeText(url).then(function() {
        alert('Link berhasil disalin! Tempel di browser untuk reset password.');
    });
}
</script>
</body>
</html>
