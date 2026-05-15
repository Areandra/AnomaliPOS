<?php

namespace App\Mail;

use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendNewRestaurantRegistrationRequest extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public Restaurant $restaurant,
        public string $status = 'attempt', // 'attempt' atau 'success'
        public ?string $enableAccountUrl = null
    ) {}

    public function envelope(): Envelope
    {
        $subject = match ($this->status) {
            'attempt' => '🔐 Permintaan Pengaktifan Restaurant - ANOPOS',
            'success' => '✅ Restaurant Berhasil Diaktifkan - ANOPOS',
            default => 'Notifikasi Restaurant ANOPOS'
        };

        return new Envelope(
            from: new Address('anopos@no-reply.com', 'Ano Security'),
            subject: $subject,
        );
    }

    public function content(): Content
    {
        $config = match ($this->status) {
            'attempt' => [
                'title' => 'Pengaktifan Restaurant',
                'color' => '#1a73e8',
                'description' => "Kami menerima permintaan untuk pengaktifan akun restaurant <strong>{$this->restaurant->name}</strong> atas nama <strong>{$this->user->name}</strong>.",
                'footer' => 'Jika Anda tidak merasa mengajukan ini, abaikan email ini. Akun akan dihapus otomatis dalam 30 hari.'
            ],
            'success' => [
                'title' => 'Restaurant Berhasil Aktif',
                'color' => '#2e7d32',
                'description' => "Selamat! Akun restaurant <strong>{$this->restaurant->name}</strong> telah berhasil diaktifkan. Anda sekarang dapat mulai mengelola operasional Anda.",
                'footer' => 'Selamat bergabung di ekosistem ANOPOS. Email ini adalah konfirmasi resmi sistem.'
            ],
        };

        return new Content(
            view: 'emails.activation-account',
            with: [
                'config' => $config,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
