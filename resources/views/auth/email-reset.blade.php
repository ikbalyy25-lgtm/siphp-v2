<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reset Password - SIPHP</title>
</head>
<body style="margin:0; padding:0; background-color:#f0fff4; font-family: Arial, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f0fff4; padding: 40px 0;">
        <tr>
            <td align="center">
                <table width="520" cellpadding="0" cellspacing="0"
                    style="background:#ffffff; border-radius:16px; overflow:hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08);">

                    {{-- Header --}}
                    <tr>
                        <td style="background: linear-gradient(135deg, #1e3a2f, #2d6a4f); padding: 32px; text-align:center;">
                            <h1 style="color: #d0f0c0; font-size: 22px; margin:0; font-weight:800; letter-spacing:2px;">SIPHP</h1>
                            <p style="color: rgba(208,240,192,0.7); font-size:12px; margin:6px 0 0;">Sistem Informasi Harga Pasar Parepare</p>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding: 36px 40px;">
                            <h2 style="color:#1e3a2f; font-size:18px; margin:0 0 12px;">Permintaan Reset Password</h2>
                            <p style="color:#555; font-size:14px; line-height:1.6; margin:0 0 24px;">
                                Kami menerima permintaan reset password untuk akun dengan email
                                <strong style="color:#2d6a4f;">{{ $email }}</strong>.
                                Klik tombol di bawah untuk membuat password baru.
                            </p>

                            <div style="text-align:center; margin: 28px 0;">
                                <a href="{{ route('password.reset', ['token' => $token, 'email' => $email]) }}"
                                    style="background: linear-gradient(135deg, #1e3a2f, #2d6a4f);
                                           color: #d0f0c0;
                                           padding: 14px 36px;
                                           text-decoration: none;
                                           border-radius: 50px;
                                           font-weight: bold;
                                           font-size: 14px;
                                           display: inline-block;
                                           letter-spacing: 0.5px;">
                                    Reset Password Saya
                                </a>
                            </div>

                            <p style="color:#999; font-size:12px; margin: 20px 0 0; line-height:1.6;">
                                Link ini akan <strong>kadaluarsa dalam 60 menit</strong>.<br>
                                Jika tombol tidak berfungsi, copy link berikut ke browser:<br>
                                <span style="color:#2d6a4f; word-break:break-all; font-size:11px;">
                                    {{ route('password.reset', ['token' => $token, 'email' => $email]) }}
                                </span>
                            </p>

                            <p style="color:#bbb; font-size:11px; margin: 20px 0 0;">
                                Jika Anda tidak meminta reset password, abaikan email ini.
                            </p>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background:#f8fffe; padding:20px 40px; text-align:center; border-top:1px solid #e8f5e9;">
                            <p style="color:#aaa; font-size:11px; margin:0;">
                                &copy; 2026 SIPHP — MAROA TEAM &bull; Dinas Perdagangan Kota Parepare
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
