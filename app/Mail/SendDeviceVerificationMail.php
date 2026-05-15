<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class SendDeviceVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $status, // 'success' | 'attempt'
        public array $deviceInfo,
        public ?string $verificationUrl = null
    ) {}

    public function envelope(): Envelope
    {
        $subject = match ($this->status) {
            'attempt' => '🚨 Otorisasi Perangkat Baru - SIPOSQIR',
            'success' => '✅ Perangkat Berhasil Diverifikasi - SIPOSQIR',
            default => 'SIPOSQIR Notification'
        };

        return new Envelope(
            from: new Address('anopos@no-reply.com', 'ANOPOS Security'),
            subject: $subject,
        );
    }

    public function content(): Content
    {
        // Pindahkan logika konfigurasi ke sini agar View tetap bersih
        $config = match ($this->status) {
            'attempt' => [
                'title' => 'Upaya Masuk Terdeteksi',
                'color' => '#d32f2f',
                'description' => 'Seseorang mencoba mengakses akun Restaurant Anda dari perangkat baru.',
                'footer' => 'Jika ini bukan Anda, abaikan email ini dan segera ganti kata sandi Anda.'
            ],
            'success' => [
                'title' => 'Perangkat Terverifikasi',
                'color' => '#2e7d32',
                'description' => 'Perangkat baru telah berhasil ditambahkan ke daftar perangkat terpercaya Anda.',
                'footer' => 'Email ini dikirim otomatis sebagai bagian dari sistem keamanan akun SIPOSQIR.'
            ],
        };

        return new Content(
            view: 'emails.device-verification',
            with: [
                'config' => $config,
                'currentTime' => Carbon::now()->locale('id')->isoFormat('D MMM YYYY, HH:mm'),
            ],
        );
    }
}
