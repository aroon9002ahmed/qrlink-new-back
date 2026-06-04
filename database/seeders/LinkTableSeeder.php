<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Link;
use Illuminate\Database\Seeder;

class LinkTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Note: short_code is generated automatically via HasShortCode trait boot.
     */
    public function run(): void
    {
        Link::create([
            'user_id'      => 1,
            'original_url' => 'https://mob4me.com',
            'title'        => 'mob4me',
            'url_hash'     => hash('sha256', 'https://mob4me.com'),
            'is_active'    => true,
            'expires_at'   => date('Y-m-d', strtotime('+1 month')),
        ]);

        Link::create([
            'user_id'      => 1,
            'original_url' => 'https://qit-eg.com',
            'url_hash'     => hash('sha256', 'https://qit-eg.com'),
            'is_active'    => true,
            'expires_at'   => null,
        ]);

        Link::create([
            'user_id'      => 2,
            'original_url' => 'https://google.com',
            'title'        => 'google',
            'url_hash'     => hash('sha256', 'https://google.com'),
            'is_active'    => true,
            'expires_at'   => null,
        ]);

        for ($i = 1; $i <= 20; $i++) {
            $url = "https://example-link-{$i}.com";
            Link::create([
                'user_id'      => 1,
                'original_url' => $url,
                'title'        => "Example Link {$i}",
                'url_hash'     => hash('sha256', $url),
                'is_active'    => true,
                'expires_at'   => null,
            ]);
        }
    }
}
