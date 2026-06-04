<?php

namespace Database\Seeders;

use App\Models\SocialLink;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SocialLinksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $links = [
            [
                'page_id'    => 1,
                'platform_id'   => 1,
                'value'      => 'https://facebook.com/example',
                'sort_order' => 1, // ترتيب الظهور

            ],
            [
                'page_id'    => 1,
                'platform_id'   => 2,
                'value'      => 'https://instagram.com/example',
                'sort_order' => 2, // ترتيب الظهور

            ],
            [
                'page_id'    => 1,
                'platform_id'   => 5,
                'value'      => '201234567890', // رقم واتساب
                'sort_order' => 3, // ترتيب الظهور

            ],
            [
                'page_id'    => 1,
                'platform_id'   => 3,
                'value'      => 'https://tiktok.com/@example',
                'sort_order' => 4, // ترتيب الظهور
            ],
        ];

        foreach ($links as $link) {
            SocialLink::create($link);
        }
    }
}
