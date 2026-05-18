<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

namespace App\Models;

use App\Models\Traits\BelongsToRestaurant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shift extends Model
{
    use BelongsToRestaurant;

    protected $guarded = ['id'];

    protected $fillable = [
        'user_id',
        'restaurant_id',
        'status',
        'modal_awal',
        'opened_at',
        'cash_system',
        'cash_physical',
        'cash_variance',
        'qris_system',
        'debit_system',
        'transfer_system',
        'closed_at',
        'notes',
    ];

    protected $casts = [
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
        'modal_awal' => 'decimal:2',
        'cash_system' => 'decimal:2',
        'cash_physical' => 'decimal:2',
        'cash_variance' => 'decimal:2',
        'qris_system' => 'decimal:2',
        'debit_system' => 'decimal:2',
        'transfer_system' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'shift_id');
    }
}
