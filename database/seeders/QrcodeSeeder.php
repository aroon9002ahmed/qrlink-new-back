<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Qrcode;

class QrcodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Note: short_code is generated automatically via HasShortCode trait boot.
     */
    public function run(): void
    {
        Qrcode::create([
            'user_id'   => 1,
            'title'     => 'qit-eg',
            'original_url' => 'https://qit-eg.com',
            'is_active' => true,
            'expires_at' => null,
        ]);

        Qrcode::create([
            'user_id'   => 1,
            'title'     => 'mob4me',
            'original_url' => 'https://mob4me.com',
            'is_active' => true,
            'expires_at' => null,
        ]);

        Qrcode::create([
            'user_id'   => 2,
            'title'     => 'smart-systems-tech',
            'original_url' => 'https://smart-systems-tech.com',
            'is_active' => true,
            'expires_at' => null,
        ]);

        for ($i = 1; $i <= 15; $i++) {
            Qrcode::create([
                'user_id'   => 1,
                'title'     => "Example QR Code {$i}",
                'original_url' => "https://example-qr-{$i}.com",
                'is_active' => true,
                'expires_at' => null,
            ]);
        }
    }
}
