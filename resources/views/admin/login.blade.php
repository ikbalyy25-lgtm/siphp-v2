<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — SIPHP Kota Parepare</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            background: #f0faf4;
            display: flex;
            align-items: stretch;
        }

        /* ── KIRI: Foto + Branding ── */
        .panel-left {
            flex: 1;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .panel-left img.bg {
            position: absolute; inset: 0;
            width: 100%; height: 100%;
            object-fit: cover;
            filter: brightness(0.45) saturate(0.9);
        }

        /* Overlay gradient dari bawah */
        .panel-left::before {
            content: '';
            position: absolute; inset: 0; z-index: 1;
            background: linear-gradient(
                to bottom,
                rgba(30,58,47,0.3) 0%,
                rgba(13,40,27,0.75) 60%,
                rgba(10,27,18,0.92) 100%
            );
        }

        /* Strip hijau atas */
        .top-strip {
            position: relative; z-index: 2;
            background: rgba(208,240,192,0.12);
            border-bottom: 1px solid rgba(208,240,192,0.2);
            backdrop-filter: blur(8px);
            padding: 10px 36px;
            display: flex; align-items: center; gap: 10px;
        }

        .top-strip .dot {
            width: 7px; height: 7px; border-radius: 50%;
            background: #4ade80;
            animation: blink 2s infinite;
        }
        @keyframes blink { 0%,100%{opacity:1} 50%{opacity:0.2} }

        .top-strip span {
            font-size: 11px; font-weight: 600;
            color: rgba(208,240,192,0.9);
            letter-spacing: 0.8px;
            text-transform: uppercase;
        }

        .panel-left-content {
            position: relative; z-index: 2;
            flex: 1;
            display: flex; flex-direction: column;
            justify-content: flex-end;
            padding: 40px 44px;
        }

        .instansi-badge {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(208,240,192,0.12);
            border: 1px solid rgba(208,240,192,0.25);
            border-radius: 8px;
            padding: 7px 14px;
            font-size: 11px; font-weight: 700;
            color: rgba(208,240,192,0.85);
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 18px;
            width: fit-content;
            backdrop-filter: blur(6px);
        }

        .panel-left h1 {
            font-size: clamp(22px, 2.8vw, 34px);
            font-weight: 800;
            color: #ffffff;
            line-height: 1.25;
            margin-bottom: 14px;
        }

        .panel-left h1 em {
            font-style: normal;
            color: #d0f0c0;
        }

        .panel-left p {
            font-size: 13px;
            color: rgba(255,255,255,0.5);
            line-height: 1.7;
            max-width: 360px;
            margin-bottom: 28px;
        }

        /* Stat row */
        .stat-row {
            display: flex; gap: 12px;
        }

        .stat-box {
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.12);
            backdrop-filter: blur(8px);
            border-radius: 12px;
            padding: 12px 18px;
            text-align: center;
        }

        .stat-box .n {
            font-size: 22px; font-weight: 800;
            color: #d0f0c0; display: block;
        }

        .stat-box .l {
            font-size: 10px; font-weight: 600;
            color: rgba(255,255,255,0.45);
            text-transform: uppercase; letter-spacing: 0.8px;
        }

        /* ── KANAN: Form Login ── */
        .panel-right {
            width: 500px;
            flex-shrink: 0;
            background: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 52px 56px;
            box-shadow: -8px 0 40px rgba(30,58,47,0.08);
            position: relative;
        }

        /* Garis atas dekoratif */
        .panel-right::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0;
            height: 4px;
            background: linear-gradient(90deg, #2d6a4f, #d0f0c0, #4ade80);
        }

        /* Header form */
        .form-header {
            display: flex; align-items: center; gap: 14px;
            margin-bottom: 32px;
            padding-bottom: 24px;
            border-bottom: 1.5px solid #e8f5ee;
        }

        .form-header img {
            width: 48px; height: 48px;
            flex-shrink: 0;
        }

        .form-header .title {
            font-size: 15px; font-weight: 800;
            color: #1a3a2a;
            line-height: 1.3;
        }

        .form-header .sub {
            font-size: 11px;
            color: #6b9a7a;
            margin-top: 2px;
        }

        /* Alert */
        .alert-err {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-left: 3px solid #ef4444;
            border-radius: 10px;
            padding: 11px 14px;
            font-size: 13px; color: #b91c1c;
            margin-bottom: 20px;
            display: flex; align-items: flex-start; gap: 8px;
        }

        .alert-ok {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-left: 3px solid #22c55e;
            border-radius: 10px;
            padding: 11px 14px;
            font-size: 13px; color: #15803d;
            margin-bottom: 20px;
        }

        /* Input group */
        .inp-group {
            margin-bottom: 18px;
        }

        .inp-group label {
            display: block;
            font-size: 11px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.8px;
            color: #5a8a6a;
            margin-bottom: 7px;
        }

        .inp-wrap {
            display: flex; align-items: center;
            border: 1.5px solid #d1e8d8;
            border-radius: 12px;
            background: #f8fdf9;
            overflow: hidden;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .inp-wrap:focus-within {
            border-color: #2d6a4f;
            box-shadow: 0 0 0 3px rgba(45,106,79,0.1);
            background: #fff;
        }

        .inp-icon {
            width: 44px;
            display: flex; align-items: center; justify-content: center;
            color: #a3c4aa;
            font-size: 13px;
            flex-shrink: 0;
        }

        .inp-wrap:focus-within .inp-icon { color: #2d6a4f; }

        .inp-wrap input {
            flex: 1;
            padding: 12px 4px;
            border: none; outline: none;
            background: transparent;
            font-size: 14px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #1a3a2a;
        }

        .inp-wrap input::placeholder { color: #b0ccb8; }

        /* Sembunyikan ikon mata bawaan browser */
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear { display: none !important; }

        .btn-eye {
            background: none; border: none;
            padding: 0 14px;
            color: #a3c4aa; cursor: pointer;
            font-size: 13px;
            transition: color 0.2s;
        }
        .btn-eye:hover { color: #2d6a4f; }

        /* Links row */
        .links-row {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 24px;
        }

        .link-daftar {
            font-size: 12px; font-weight: 600;
            color: #2d6a4f;
            text-decoration: none;
            border: 1.5px solid #b7dfc4;
            border-radius: 999px;
            padding: 5px 14px;
            background: #f0faf4;
            transition: background 0.2s, border-color 0.2s;
        }
        .link-daftar:hover { background: #d0f0c0; border-color: #2d6a4f; }

        .link-lupa {
            font-size: 12px; font-weight: 500;
            color: #8aaa9a;
            text-decoration: none;
            transition: color 0.2s;
        }
        .link-lupa:hover { color: #2d6a4f; }

        /* Submit */
        .btn-masuk {
            width: 100%;
            padding: 14px;
            border: none; border-radius: 12px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 14px; font-weight: 800;
            letter-spacing: 0.8px;
            cursor: pointer;
            background: linear-gradient(135deg, #2d6a4f 0%, #3d8a65 100%);
            color: white;
            transition: opacity 0.2s, transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 4px 16px rgba(45,106,79,0.3);
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .btn-masuk:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(45,106,79,0.35);
        }
        .btn-masuk:active { transform: translateY(0); }
        .btn-masuk:disabled { opacity: 0.5; cursor: not-allowed; transform: none; box-shadow: none; }

        /* Footer */
        .form-footer {
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid #e8f5ee;
            text-align: center;
            font-size: 11px;
            color: #a3c4aa;
            line-height: 1.6;
        }

        @media (max-width: 768px) {
            .panel-left { display: none; }
            .panel-right { width: 100%; }
        }
    </style>
</head>
<body>

{{-- PANEL KIRI --}}
<div class="panel-left">
    <img class="bg" src="{{ asset('img/glogin.png') }}" alt=""
        onerror="this.parentElement.style.background='linear-gradient(160deg,#0d2b1a,#1e3a2f)'">

    <div class="top-strip">
        <span class="dot"></span>
        <span>Dinas Perdagangan Kota Parepare</span>
    </div>

    <div class="panel-left-content">
        <div class="instansi-badge">
            <i class="fas fa-landmark" style="font-size:10px;"></i>
            Pemerintah Kota Parepare
        </div>

        <h1>Sistem Informasi<br>Penyedia <em>Harga Pasar</em></h1>

        <p>Platform digital pengelolaan dan monitoring harga komoditas pasar secara transparan dan akuntabel.</p>

        <div class="stat-row">
            <div class="stat-box">
                <span class="n">5</span>
                <span class="l">Pasar</span>
            </div>
            <div class="stat-box">
                <span class="n">41</span>
                <span class="l">Komoditas</span>
            </div>
            <div class="stat-box">
                <span class="n">3</span>
                <span class="l">Kategori</span>
            </div>
            <div class="stat-box">
                <span class="n">Live</span>
                <span class="l">Update</span>
            </div>
        </div>
    </div>
</div>

{{-- PANEL KANAN --}}
<div class="panel-right">

    <div class="form-header">
        <img src="{{ asset('img/logo.png') }}" alt="Logo SIPHP"
            onerror="this.style.display='none'">
        <div>
            <div class="title">SIPHP — Portal Masuk</div>
            <div class="sub">Admin &amp; Pedagang Pasar Kota Parepare</div>
        </div>
    </div>

    @if(session('error'))
    <div class="alert-err" id="error-alert">
        <i class="fas fa-exclamation-circle" style="margin-top:1px; flex-shrink:0;"></i>
        <span id="error-message">{{ session('error') }}</span>
    </div>
    @endif

    @if(session('status'))
    <div class="alert-ok">
        <i class="fas fa-check-circle" style="margin-right:7px;"></i>
        {{ session('status') }}
    </div>
    @endif

    <form action="{{ route('login.process') }}" method="POST">
        @csrf

        <div class="inp-group">
            <label>Username</label>
            <div class="inp-wrap">
                <span class="inp-icon"><i class="fas fa-user"></i></span>
                <input type="text" name="username"
                    placeholder="Masukkan username"
                    value="{{ old('username') }}"
                    autocomplete="off">
            </div>
        </div>

        <div class="inp-group">
            <label>Password</label>
            <div class="inp-wrap">
                <span class="inp-icon"><i class="fas fa-lock"></i></span>
                <input type="password" id="password" name="password"
                    placeholder="Masukkan password">
                <button type="button" class="btn-eye" onclick="togglePw()">
                    <i class="fas fa-eye" id="eyeIcon"></i>
                </button>
            </div>
        </div>

        <div class="links-row">
            <a href="{{ route('password.request') }}" class="link-lupa">
                Lupa Password?
            </a>
        </div>

        <button type="submit" class="btn-masuk" id="submit-btn">
            <i class="fas fa-sign-in-alt" style="font-size:13px;"></i>
            MASUK
        </button>
    </form>

    <div class="form-footer">
        <strong style="color:#2d6a4f;">SIPHP</strong> — Sistem Informasi Penyedia Harga Pasar<br>
        &copy; 2026 Dinas Perdagangan Kota Parepare · MAROA TEAM
    </div>
</div>

<script>
function togglePw() {
    const inp = document.getElementById('password');
    const ico = document.getElementById('eyeIcon');
    const hidden = inp.type === 'password';
    inp.type  = hidden ? 'text' : 'password';
    ico.className = hidden ? 'fas fa-eye-slash' : 'fas fa-eye';
}

document.addEventListener('DOMContentLoaded', function () {
    const errEl  = document.getElementById('error-message');
    const alertD = document.getElementById('error-alert');
    const btn    = document.getElementById('submit-btn');
    const pw     = document.getElementById('password');

    if (errEl && errEl.innerText.includes('tunggu')) {
        const match = errEl.innerText.match(/(\d+)/);
        if (match) {
            let t = parseInt(match[0]);
            if (btn) btn.disabled = true;
            if (pw)  pw.readOnly  = true;

            const timer = setInterval(() => {
                t--;
                if (t <= 0) {
                    clearInterval(timer);
                    if (alertD) alertD.remove();
                    if (btn) btn.disabled = false;
                    if (pw)  pw.readOnly  = false;
                } else {
                    errEl.innerText = errEl.innerText.replace(/\d+/, t);
                }
            }, 1000);
        }
    }
});
</script>
</body>
</html>
