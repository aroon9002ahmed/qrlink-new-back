<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RestaurantBranch;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RestaurantBranchesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RestaurantBranch::create([
            "page_id" => 1,
            "name" => "الفرع الرئيسي",
            "address" => "123 Main St, City",
            "image" => "",
            "latitude" => "30.041398894775345",
            "longitude" => "31.265609185528067",
            "status" => true,
            "main" => true,
        ]);

        RestaurantBranch::create([
            "page_id" => 1,
            "name" => "الفرع الثاني",
            "address" => "123 Main St, City",
            "image" => "",
            "latitude" => "29.97727748849764",
            "longitude" => "31.132298740564806",
            "status" => true,
            "main" => false,
        ]);
    }
}
