<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\MenuLocations;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MenuLocationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now()->format('Y-m-d H:i:s');
        $defaultValues = [
            'status' => 1,
            'created_by' => '1',
            'created_at' => $now,
            'updated_at' => $now
        ];

        $locations = [
            [
                'id' => 1,
                'title' => [
                    'en' => 'Header',
                    'ar' => 'الرأس'
                ],
                'slug' => 'header',
            ],
            [
                'id' => 2,
                'title' => [
                    'en' => 'footer',
                    'ar' => 'تذييل'
                ],
                'slug' => 'footer',
            ]
        ];


        foreach ($locations as $category) {
            $category = MenuLocations::create(array_merge($category, $defaultValues));
        }
    }
}
