<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RestaurantSeeder extends Seeder
{
    public function run(): void
    {
        Restaurant::create([
            'restaurant_uid' => Str::uuid(),
            'name'           => 'Warung AnoPos',
            'plan'           => 'pro',
            'status'         => 'active',
            'pin'            => '1234',
        ]);
    }
}
