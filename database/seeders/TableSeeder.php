<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use App\Models\Table;
use Illuminate\Database\Seeder;

class TableSeeder extends Seeder
{
    public function run(): void
    {
        $restaurant = Restaurant::first();

        $tables = [
            ['table_number' => 'A1', 'capacity' => 4,  'position_x' => 50,  'position_y' => 50],
            ['table_number' => 'A2', 'capacity' => 4,  'position_x' => 200, 'position_y' => 50],
            ['table_number' => 'A3', 'capacity' => 6,  'position_x' => 350, 'position_y' => 50],
            ['table_number' => 'B1', 'capacity' => 2,  'position_x' => 50,  'position_y' => 200],
            ['table_number' => 'B2', 'capacity' => 4,  'position_x' => 200, 'position_y' => 200],
            ['table_number' => 'B3', 'capacity' => 4,  'position_x' => 350, 'position_y' => 200],
            ['table_number' => 'C1', 'capacity' => 8,  'position_x' => 50,  'position_y' => 350],
            ['table_number' => 'C2', 'capacity' => 6,  'position_x' => 250, 'position_y' => 350],
            ['table_number' => 'VIP1', 'capacity' => 10, 'position_x' => 500, 'position_y' => 100],
            ['table_number' => 'VIP2', 'capacity' => 12, 'position_x' => 500, 'position_y' => 300],
        ];

        foreach ($tables as $table) {
            Table::create([
                'restaurant_id' => $restaurant->id,
                'table_number'  => $table['table_number'],
                'capacity'      => $table['capacity'],
                'position_x'    => $table['position_x'],
                'position_y'    => $table['position_y'],
                'status'        => 'available',
            ]);
        }
    }
}
