<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Context;

/**
 * Trait BelongsToRestaurant
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait BelongsToRestaurant
{
    /**
     * Boot trait untuk otomatisasi global scope dan model events.
     */
    public static function bootBelongsToRestaurant(): void
    {
        // 1. Tambahkan Global Scope untuk Filter Otomatis
        static::addGlobalScope('restaurant_scope', function (Builder $builder) {
            // Mengambil restaurant_id langsung dari Laravel Context yang di-set oleh Middleware
            $restaurantId = Context::get('restaurant_id');

            if ($restaurantId) {
                // Menghindari konflik nama kolom jika ada query JOIN
                $builder->where($builder->getModel()->getTable() . '.restaurant_id', $restaurantId);
            }
        });

        // 2. Event saat membuat data baru (Otomatis mengisi restaurant_id)
        static::creating(function (Model $model) {
            $restaurantId = Context::get('restaurant_id');

            if ($restaurantId && !$model->restaurant_id) {
                $model->restaurant_id = $restaurantId;
            }
        });
    }

    /**
     * Relationship Helper
     * Mempermudah memanggil data restoran terkait dari model ini ($this->restaurant)
     */
    public function restaurant()
    {
        return $this->belongsTo(\App\Models\Restaurant::class, 'restaurant_id');
    }
}
