<?php

namespace Database\Seeders;

use App\Models\MenuCategory;
use App\Models\Restaurant;
use Illuminate\Database\Seeder;

class MenuCategorySeeder extends Seeder
{
    public function run(): void
    {
        $restaurant = Restaurant::first();

        $categories = [
            ['name' => 'Makanan Utama',  'description' => 'Hidangan utama pilihan',      'sort_order' => 1],
            ['name' => 'Minuman',        'description' => 'Minuman segar dan hangat',     'sort_order' => 2],
            ['name' => 'Snack & Gorengan', 'description' => 'Camilan dan gorengan',       'sort_order' => 3],
            ['name' => 'Dessert',        'description' => 'Hidangan penutup',             'sort_order' => 4],
            ['name' => 'Paket Hemat',    'description' => 'Paket bundling harga spesial', 'sort_order' => 5],
        ];

        foreach ($categories as $cat) {
            MenuCategory::create([
                'restaurant_id' => $restaurant->id,
                'name'          => $cat['name'],
                'description'   => $cat['description'],
                'sort_order'    => $cat['sort_order'],
            ]);
        }
    }
}
