<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pengaktifan Restaurant</title>
</head>
<body>
    <div style="font-family: sans-serif; color: #333; max-width: 600px; margin: auto; border: 1px solid #eee; padding: 20px; border-radius: 10px;">
        <h2 style="color: #1a73e8; text-align: center;">Pengaktifan Restaurant</h2>

        <p>Halo <strong>AnoBos</strong>,</p>
        <p>Kami menerima permintaan untuk Pengaktifan Akun {{ $user->name }}. Silakan klik tombol di bawah ini untuk melanjutkan:</p>

        <div style="text-align: center; margin: 35px 0;">
            <a href="{{ $enableAccountUrl }}"
               style="background-color: #1a73e8; color: white; padding: 14px 28px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
               Aktifkan Akun
            </a>
        </div>

        <div style="background: #fff9c4; border-left: 4px solid #fbc02d; padding: 15px; margin: 20px 0;">
            <p style="margin: 0; font-size: 13px; color: #616161;">
                <strong>Catatan:</strong> Link ini akan kedaluwarsa dalam waktu singkat demi keamanan akun Anda. Jika tombol di atas tidak berfungsi, salin dan tempel tautan berikut di browser Anda:
            </p>
            <p style="margin: 10px 0 0 0; font-size: 11px; word-break: break-all; color: #1a73e8;">
                {{ $enableAccountUrl }}
            </p>
        </div>

        <hr style="border: 0; border-top: 1px solid #eee; margin: 25px 0;" />

        <p style="font-size: 12px; color: #777;">
            Jika AnoBos tidak merasa permintaan ini dapat di izinkan, abaikan email ini. akun restaurant akan disabled dan akan di hapus dalam jangka waktu 30 hari.
        </p>

        <div style="text-align: center; margin-top: 20px;">
            <p style="font-size: 11px; color: #bbb;">&copy; {{ now()->year }} Ano Security System</p>
        </div>
    </div>
</body>
</html>
