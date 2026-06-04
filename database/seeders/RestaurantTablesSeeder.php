<?php

namespace Database\Seeders;

use App\Models\RestaurantTable;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RestaurantTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RestaurantTable::create([
            'page_id' => 1,
            'table_number' => 'Delivery',
            'type' => 'delivery',
            'seating_capacity' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        RestaurantTable::create([
            'page_id' => 1,
            'table_number' => '1',
            'seating_capacity' => 4,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        RestaurantTable::create([
            'page_id' => 1,
            'table_number' => '2',
            'seating_capacity' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        RestaurantTable::create([
            'page_id' => 1,
            'table_number' => '3',
            'seating_capacity' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        RestaurantTable::create([
            'page_id' => 1,
            'table_number' => '4',
            'seating_capacity' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        RestaurantTable::create([
            'page_id' => 1,
            'table_number' => '5',
            'seating_capacity' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        RestaurantTable::create([
            'page_id' => 1,
            'table_number' => '6',
            'seating_capacity' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
