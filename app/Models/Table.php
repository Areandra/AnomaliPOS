<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Table extends Model
{
    protected $guarded = ['id'];

    protected $fillable = [
        'restaurant_id',
        'table_number',
        'capacity',
        'position_x',
        'position_y',
        'facing',
        'vertical',
        'status',
        'current_session_id',
    ];

    protected $casts = [
        'position_x' => 'decimal:2',
        'position_y' => 'decimal:2',
        'facing' => 'boolean',
        'vertical' => 'boolean',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function tableSessions(): HasMany
    {
        return $this->hasMany(TableSession::class, 'current_table_session_id');
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }
}
