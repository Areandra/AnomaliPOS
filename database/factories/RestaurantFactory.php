<?php

namespace Database\Factories;

use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Restaurant>
 */
class RestaurantFactory extends Factory
{
    protected $model = Restaurant::class;

    public function definition(): array
    {
        return [
            'restaurant_uid' => Str::uuid(),
            'name' => fake()->company(),
            'plan' => 'starter',
            'avatar_url' => null,
            'status' => 'active',
            'pin' => '123456',
        ];
    }
}
