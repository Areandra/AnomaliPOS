<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RestaurantSeeder::class,
            UserSeeder::class,
            TableSeeder::class,
            MenuCategorySeeder::class,
            MenuItemSeeder::class,
        ]);
    }
}
