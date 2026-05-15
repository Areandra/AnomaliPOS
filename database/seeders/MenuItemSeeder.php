<?php

namespace Database\Seeders;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Restaurant;
use Illuminate\Database\Seeder;

class MenuItemSeeder extends Seeder
{
    public function run(): void
    {
        $restaurant = Restaurant::first();

        $categories = MenuCategory::where('restaurant_id', $restaurant->id)
            ->pluck('id', 'name');

        $items = [
            // Makanan Utama
            ['category' => 'Makanan Utama', 'name' => 'Nasi Goreng Spesial',    'description' => 'Nasi goreng dengan telur, ayam, dan sayuran segar',   'price' => 25000, 'cost' => 10000],
            ['category' => 'Makanan Utama', 'name' => 'Mie Goreng Jumbo',       'description' => 'Mie goreng porsi besar dengan topping lengkap',        'price' => 22000, 'cost' => 9000],
            ['category' => 'Makanan Utama', 'name' => 'Ayam Bakar Madu',        'description' => 'Ayam bakar dengan bumbu madu khas rumahan',            'price' => 35000, 'cost' => 15000],
            ['category' => 'Makanan Utama', 'name' => 'Soto Ayam Kampung',      'description' => 'Soto bening dengan ayam kampung dan pelengkap',        'price' => 20000, 'cost' => 8000],
            ['category' => 'Makanan Utama', 'name' => 'Nasi Uduk Komplit',      'description' => 'Nasi uduk dengan lauk lengkap dan sambal',             'price' => 28000, 'cost' => 11000],
            ['category' => 'Makanan Utama', 'name' => 'Gado-Gado',              'description' => 'Sayuran rebus dengan bumbu kacang spesial',            'price' => 18000, 'cost' => 7000],
            ['category' => 'Makanan Utama', 'name' => 'Rendang Sapi',           'description' => 'Rendang sapi empuk dimasak dengan rempah pilihan',     'price' => 45000, 'cost' => 20000],
            ['category' => 'Makanan Utama', 'name' => 'Ikan Bakar Bumbu Bali',  'description' => 'Ikan segar dibakar dengan bumbu Bali pedas',           'price' => 40000, 'cost' => 18000],

            // Minuman
            ['category' => 'Minuman', 'name' => 'Es Teh Manis',        'description' => 'Teh manis dingin segar',                          'price' => 5000,  'cost' => 1500],
            ['category' => 'Minuman', 'name' => 'Es Jeruk Peras',      'description' => 'Jeruk peras segar dengan es batu',                'price' => 8000,  'cost' => 3000],
            ['category' => 'Minuman', 'name' => 'Kopi Hitam',          'description' => 'Kopi hitam robusta pilihan',                      'price' => 8000,  'cost' => 2500],
            ['category' => 'Minuman', 'name' => 'Kopi Susu',           'description' => 'Kopi susu dengan krimer dan gula aren',           'price' => 12000, 'cost' => 4000],
            ['category' => 'Minuman', 'name' => 'Jus Alpukat',         'description' => 'Jus alpukat segar dengan susu kental manis',      'price' => 15000, 'cost' => 6000],
            ['category' => 'Minuman', 'name' => 'Es Campur',           'description' => 'Es campur dengan berbagai topping buah',          'price' => 12000, 'cost' => 5000],
            ['category' => 'Minuman', 'name' => 'Air Mineral',         'description' => 'Air mineral botol 600ml',                        'price' => 5000,  'cost' => 2000],
            ['category' => 'Minuman', 'name' => 'Teh Tarik',           'description' => 'Teh tarik khas dengan busa susu',                'price' => 10000, 'cost' => 3500],

            // Snack & Gorengan
            ['category' => 'Snack & Gorengan', 'name' => 'Pisang Goreng Crispy', 'description' => 'Pisang goreng tepung renyah dengan madu',       'price' => 12000, 'cost' => 4000],
            ['category' => 'Snack & Gorengan', 'name' => 'Tahu Isi Goreng',      'description' => 'Tahu isi sayuran digoreng garing',              'price' => 8000,  'cost' => 3000],
            ['category' => 'Snack & Gorengan', 'name' => 'Tempe Mendoan',        'description' => 'Tempe mendoan tepung tipis khas Banyumas',       'price' => 8000,  'cost' => 2500],
            ['category' => 'Snack & Gorengan', 'name' => 'Bakwan Sayur',         'description' => 'Bakwan sayuran renyah',                          'price' => 6000,  'cost' => 2000],
            ['category' => 'Snack & Gorengan', 'name' => 'Kentang Goreng',       'description' => 'Kentang goreng crispy dengan saus sambal',       'price' => 15000, 'cost' => 5000],

            // Dessert
            ['category' => 'Dessert', 'name' => 'Puding Coklat',       'description' => 'Puding coklat lembut dengan saus vanilla',        'price' => 10000, 'cost' => 3500],
            ['category' => 'Dessert', 'name' => 'Es Krim 2 Scoop',     'description' => 'Es krim pilihan rasa dengan cone atau cup',       'price' => 15000, 'cost' => 6000],
            ['category' => 'Dessert', 'name' => 'Klepon',               'description' => 'Klepon isi gula merah dengan taburan kelapa',     'price' => 8000,  'cost' => 3000],
            ['category' => 'Dessert', 'name' => 'Bubur Sumsum',         'description' => 'Bubur sumsum dengan kuah gula merah',             'price' => 10000, 'cost' => 3500],

            // Paket Hemat
            ['category' => 'Paket Hemat', 'name' => 'Paket Nasi + Minum',    'description' => 'Nasi goreng / mie goreng + es teh manis',      'price' => 28000, 'cost' => 11000],
            ['category' => 'Paket Hemat', 'name' => 'Paket Ayam Komplit',    'description' => 'Ayam bakar + nasi + es teh + gorengan 2pcs',   'price' => 45000, 'cost' => 18000],
            ['category' => 'Paket Hemat', 'name' => 'Paket Keluarga',        'description' => 'Nasi 4 porsi + lauk 4 + minuman 4',            'price' => 120000, 'cost' => 50000],
        ];

        foreach ($items as $item) {
            MenuItem::create([
                'restaurant_id' => $restaurant->id,
                'category_id'   => $categories[$item['category']],
                'name'          => $item['name'],
                'description'   => $item['description'],
                'price'         => $item['price'],
                'cost_of_goods' => $item['cost'],
                'is_available'  => true,
            ]);
        }
    }
}
