<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrustedDivice extends Model
{
    // Override karena nama tabel menggunakan huruf 'i' (trusted_divices)
    protected $table = 'trusted_divices';
    protected $guarded = ['id'];

    // Menggunakan nama relasi printedByUser sesuai kode asli Adonis-mu
    public function printedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }
}
