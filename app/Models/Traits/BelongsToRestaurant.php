<?php

namespace App\Models\Traits;

use App\Services\RestaurantContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait BelongsToRestaurant
 * * @mixin \Illuminate\Database\Eloquent\Model
 */
trait BelongsToRestaurant
{
    /**
     * Boot trait untuk otomatisasi global scope dan model events.
     * Nama method harus diawali kata 'boot' diikuti nama Trait-nya.
     */
    public static function bootBelongsToRestaurant(): void
    {
        // Menambahkan global scope agar otomatis melakukan filter berdasarkan restaurant_id saat query data
        static::addGlobalScope('restaurant_scope', function (Builder $builder) {
            $context = app(RestaurantContext::class);
            $restaurantId = $context->getRestaurantId();

            if ($restaurantId) {
                // Menghindari konflik kolom jika ada query JOIN
                $builder->where($builder->getModel()->getTable() . '.restaurant_id', $restaurantId);
            }
        });

        // Sebelum data di-insert (Sama seperti @beforeCreate di AdonisJS)
        static::creating(function (Model $model) {
            $context = app(RestaurantContext::class);
            $restaurantId = $context->getRestaurantId();

            if ($restaurantId && !$model->restaurant_id) {
                $model->restaurant_id = $restaurantId;
            }
        });
    }
}
