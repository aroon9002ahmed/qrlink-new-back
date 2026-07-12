<?php

namespace Database\Seeders;

use App\Models\Template;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TemplatesTableseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'name' => [
                    'en' => 'Blue Sky',
                    'ar' => 'السماء الزرقاء'
                ],
                'slug' => 'blue-sky',
                'description' => [
                    'en' => 'A beautiful and serene profile page template.',
                    'ar' => 'قالب صفحة ملف شخصي جميل وهادئ.'
                ],
                'preview_image' => 'templates/profile1.png',
                'page_type_id' => 1, // Assuming 1 is the ID for 'profile' in page_types table
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => [
                    'en' => 'Gradient',
                    'ar' => 'تدرج لوني'
                ],
                'slug' => 'gradient',
                'description' => [
                    'en' => 'A modern and stylish profile page template.',
                    'ar' => 'قالب صفحة ملف شخصي حديث وأنيق.'
                ],
                'preview_image' => 'templates/profile1.png',
                'page_type_id' => 1, // Assuming 1 is the ID for 'profile' in page_types table
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => [
                    'en' => 'Blue Restaurant',
                    'ar' => 'المطعم الأزرق'
                ],
                'slug' => 'blue-restaurant',
                'description' => [
                    'en' => 'A vibrant and engaging restaurant page template.',
                    'ar' => 'قالب صفحة مطعم نابض بالحياة وجذاب.'
                ],
                'preview_image' => 'templates/restaurant1.png',
                'page_type_id' => 2, // Assuming 2 is the ID for 'restaurant' in page_types table
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => [
                    'en' => 'Blue Profile',
                    'ar' => 'ملف شخصي أزرق'
                ],
                'slug' => 'blue-profile',
                'description' => [
                    'en' => 'A beautiful and serene profile page template.',
                    'ar' => 'قالب صفحة ملف شخصي جميل وهادئ.'
                ],
                'preview_image' => 'templates/profile2.png',
                'page_type_id' => 1, // Assuming 1 is the ID for 'profile' in page_types table
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => [
                    'en' => 'Cozy Cafe',
                    'ar' => 'كافيه دافئ'
                ],
                'slug' => 'cozy-cafe',
                'description' => [
                    'en' => 'A warm and cozy coffee shop template with earthy tones.',
                    'ar' => 'قالب كافيه دافئ ومريح بألوان ترابية هادئة.'
                ],
                'preview_image' => 'templates/cozy_cafe.png',
                'page_type_id' => 2,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ];

        foreach ($templates as $template) {
            Template::updateOrCreate(['slug' => $template['slug']], $template);
        }
    }
}
