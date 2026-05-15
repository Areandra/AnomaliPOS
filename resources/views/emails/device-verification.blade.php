<div style="font-family: sans-serif; color: #333; max-width: 600px; margin: auto; border: 1px solid #eee; padding: 20px; border-radius: 10px;">
    <h2 style="color: {{ $config['color'] }}; text-align: center;">{{ $config['title'] }}</h2>

    <p>Halo <strong>Admin {{ $user->name }}</strong>,</p>
    <p>{{ $config['description'] }}</p>

    <div style="background: #f8f9fa; border-left: 4px solid {{ $config['color'] }}; padding: 15px; margin: 20px 0;">
        <p style="margin: 0; font-weight: bold; font-size: 14px;">Detail Perangkat:</p>
        <ul style="font-size: 13px; color: #555; list-style: none; padding: 0;">
            <li>📍 <strong>Nama:</strong> {{ $deviceInfo['deviceName'] }}</li>
            <li>💻 <strong>OS:</strong> {{ $deviceInfo['os'] }}</li>
            <li>🌐 <strong>Browser:</strong> {{ $deviceInfo['browser'] }}</li>
            <li>⏰ <strong>Waktu:</strong> {{ $currentTime }}</li>
        </ul>
    </div>

    @if($status === 'attempt')
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $verificationUrl }}"
               style="background-color: #1a73e8; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;">
               Ya, Izinkan Perangkat Ini
            </a>
        </div>
    @endif

    @if($status === 'success')
        <p style="font-size: 14px; background: #f1f8e9; padding: 10px; border-radius: 5px; color: #2e7d32;">
            Sekarang Anda dapat mengakses dashboard menggunakan perangkat ini tanpa verifikasi tambahan.
        </p>
    @endif

    <hr style="border: 0; border-top: 1px solid #eee; margin: 25px 0;" />

    <p style="font-size: 12px; color: #777;">
        {{ $config['footer'] }}
    </p>

    <div style="text-align: center; margin-top: 20px;">
        <p style="font-size: 11px; color: #bbb;">&copy; {{ date('Y') }} SIPOSQIR Security System</p>
    </div>
</div>
