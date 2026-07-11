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
                'status' => false,
                'has_banners' => false,
                'has_social_media' => true,
                'has_branches' => false,
                'has_products' => false,
                'has_orders' => false,
                'has_tables' => false,
            ],
            [
                'name' => [
                    'en' => 'restaurant',
                    'ar' => 'مطعم'
                ],
                'slug' => 'restaurant',
                'icon' => 'restaurant.svg',
                'status' => true,
                'has_banners' => true,
                'has_social_media' => true,
                'has_branches' => true,
                'has_products' => true,
                'has_orders' => true,
                'has_tables' => true,
            ],
            [
                'name' => [
                    'en' => 'store',
                    'ar' => 'متجر'
                ],
                'slug' => 'store',
                'icon' => 'store.svg',
                'status' => true,
                'has_banners' => true,
                'has_social_media' => true,
                'has_branches' => true,
                'has_products' => true,
                'has_orders' => true,
                'has_tables' => false,
            ],
            [
                'name' => [
                    'en' => 'event',
                    'ar' => 'حدث'
                ],
                'slug' => 'event',
                'icon' => 'event.svg',
                'status' => false,
                'has_banners' => true,
                'has_social_media' => true,
                'has_branches' => false,
                'has_products' => false,
                'has_orders' => false,
                'has_tables' => false,
            ]
        ];

        foreach ($types as $type) {
            PageType::updateOrCreate(['slug' => $type['slug']], $type);
        }
    }
}
