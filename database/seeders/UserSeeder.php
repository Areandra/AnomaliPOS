<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $restaurant = Restaurant::first();

        $users = [
            [
                'name'          => 'Admin Utama',
                'email'         => 'admin@anopos.com',
                'password'      => 'password',
                'role'          => 'admin',
                'status'        => 'active',
                'restaurant_id' => $restaurant->id,
            ],
            [
                'name'          => 'Budi Kasir',
                'email'         => 'kasir@anopos.com',
                'password'      => 'password',
                'role'          => 'cashier',
                'status'        => 'active',
                'restaurant_id' => $restaurant->id,
            ],
            [
                'name'          => 'Siti Dapur',
                'email'         => 'kitchen@anopos.com',
                'password'      => 'password',
                'role'          => 'kitchen',
                'status'        => 'active',
                'restaurant_id' => $restaurant->id,
            ],
            [
                'name'          => 'Andi Manager',
                'email'         => 'manager@anopos.com',
                'password'      => 'password',
                'role'          => 'manager',
                'status'        => 'active',
                'restaurant_id' => $restaurant->id,
            ],
            [
                'name'          => 'Rini Waiter',
                'email'         => 'waiter@anopos.com',
                'password'      => 'password',
                'role'          => 'waiter',
                'status'        => 'active',
                'restaurant_id' => $restaurant->id,
            ],
        ];

        foreach ($users as $data) {
            User::create($data);
        }
    }
}
