<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Page;
use Illuminate\Database\Seeder;
use App\Models\RestaurantSettings;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // DB::table('pages')->insert([
        //     'user_id' => 1,
        //     'title' => 'QIT Egypt',
        //     'slug' => 'qit-egypt',
        //     'description' => 'Welcome to QIT Egypt, your gateway to innovative tech solutions and services.',
        //     'template_id' => 1,
        //     'type' => 1, // Assuming 'profile' has ID 1 in page_types table
        //     'status' => 1,
        //     'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        //     'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        // ]);

        Page::create([
            'user_id' => 1,
            'title' => 'مطعم الحبايب',
            'slug' => 'demo-restaurant',
            'description' => 'اكل بيتي يستاهل بؤك',
            'template_id' => 3,
            'type' => 2, // Assuming 'restaurant' has ID 2 in page_types table
            'language' => 'ar',
            'status' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        Page::create([
            'user_id' => 2,
            'title' => 'Demo restaurant',
            'slug' => 'demo-restaurant-2',
            'description' => 'Demo restaurant description',
            'template_id' => 3,
            'type' => 2, // Assuming 'restaurant' has ID 2 in page_types table
            'language' => 'en',
            'status' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }
}
