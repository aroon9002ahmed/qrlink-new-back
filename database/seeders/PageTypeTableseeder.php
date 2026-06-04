<?php

namespace Database\Seeders;

use App\Models\PageType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PageTypeTableseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => [
                    'en' => 'profile',
                    'ar' => 'الملف الشخصي'
                ],
                'slug' => 'profile',
                'icon' => 'profile.svg',
                'status' => false
            ],
            [
                'name' => [
                    'en' => 'restaurant',
                    'ar' => 'مطعم'
                ],
                'slug' => 'restaurant',
                'icon' => 'restaurant.svg',
                'status' => true
            ],
            [
                'name' => [
                    'en' => 'store',
                    'ar' => 'متجر'
                ],
                'slug' => 'store',
                'icon' => 'store.svg',
                'status' => true
            ],
            [
                'name' => [
                    'en' => 'event',
                    'ar' => 'حدث'
                ],
                'slug' => 'event',
                'icon' => 'event.svg',
                'status' => false
            ]
        ];

        foreach ($types as $type) {
            PageType::create($type);
        }
    }
}
