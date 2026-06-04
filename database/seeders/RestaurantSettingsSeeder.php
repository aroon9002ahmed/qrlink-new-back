<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Models\RestaurantSettings;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RestaurantSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //page restaurant settings
        RestaurantSettings::create([
            'page_id' => 1,
            'currency' => 'EGP',
            'currency_symbol' => 'ج.م',
            'currency_position' => 'after',
            'hotline' => '123456789',
            'opening_hours' => '9:00 - 21:00',
            'enable_orders' => true,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }
}
