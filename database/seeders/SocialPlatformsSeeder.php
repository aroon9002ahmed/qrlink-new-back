<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SocialPlatformsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('social_platforms')->insert([
            [
                'name' => 'Facebook',
                'icon' => 'fa-brands fa-facebook',
                'type' => 'url',
                'base_url' => 'https://facebook.com/',
                'color' => '#3b5998',
            ],
            [
                'name' => 'Instagram',
                'icon' => 'fa-brands fa-instagram',
                'type' => 'url',
                'base_url' => 'https://instagram.com/',
                'color' => '#E1306C',
            ],
            [
                'name' => 'TikTok',
                'icon' => 'fa-brands fa-tiktok',
                'type' => 'url',
                'base_url' => 'https://tiktok.com/@',
                'color' => '#000000',
            ],
            [
                'name' => 'LinkedIn',
                'icon' => 'fa-brands fa-linkedin',
                'type' => 'url',
                'base_url' => 'https://linkedin.com/in/',
                'color' => '#0A66C2',
            ],
            [
                'name' => 'WhatsApp',
                'icon' => 'fa-brands fa-whatsapp',
                'type' => 'phone',
                'base_url' => 'https://wa.me/',
                'color' => '#25D366',
            ],
            [
                'name' => 'Phone',
                'icon' => 'fa-solid fa-phone',
                'type' => 'phone',
                'base_url' => 'tel:',
                'color' => null,
            ],
            [
                'name' => 'Email',
                'icon' => 'fa-solid fa-envelope',
                'type' => 'email',
                'base_url' => 'mailto:',
                'color' => null,
            ],
            [
                'name' => 'YouTube',
                'icon' => 'fa-brands fa-youtube',
                'type' => 'url',
                'base_url' => 'https://youtube.com/@',
                'color' => '#FF0000',
            ],
            [
                'name' => 'Twitter (X)',
                'icon' => 'fa-brands fa-twitter',
                'type' => 'url',
                'base_url' => 'https://x.com/',
                'color' => '#1DA1F2',
            ],
        ]);
    }
}
