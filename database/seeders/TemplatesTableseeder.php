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
            // Profile Templates (page_type_id: 1)
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
                'page_type_id' => 1,
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
                'page_type_id' => 1,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => [
                    'en' => 'Dark Aqua',
                    'ar' => 'أكوا الداكن'
                ],
                'slug' => 'dark-aqua',
                'description' => [
                    'en' => 'A sleek and elegant dark aqua themed profile page template.',
                    'ar' => 'قالب صفحة ملف شخصي بلون أكوا الداكن الأنيق.'
                ],
                'preview_image' => 'templates/profile2.png',
                'page_type_id' => 1,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Restaurant Templates (page_type_id: 2)
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
                'page_type_id' => 2,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => [
                    'en' => 'Classic Restaurant',
                    'ar' => 'المطعم الكلاسيكي'
                ],
                'slug' => 'classic',
                'description' => [
                    'en' => 'A clean, elegant, and classic restaurant menu layout.',
                    'ar' => 'قالب منيو مطعم كلاسيكي، نظيف وأنيق.'
                ],
                'preview_image' => 'templates/restaurant1.png',
                'page_type_id' => 2,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => [
                    'en' => 'Modern Restaurant',
                    'ar' => 'المطعم الحديث'
                ],
                'slug' => 'modern',
                'description' => [
                    'en' => 'A sleek, contemporary, and modern restaurant layout.',
                    'ar' => 'تصميم منيو مطعم عصري وحديث وأنيق.'
                ],
                'preview_image' => 'templates/restaurant2.png',
                'page_type_id' => 2,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => [
                    'en' => 'Baby Blue Restaurant',
                    'ar' => 'المطعم السماوي'
                ],
                'slug' => 'baby-blue',
                'description' => [
                    'en' => 'A soft, refreshing baby blue restaurant theme.',
                    'ar' => 'قالب مطعم بلون أزرق سماوي ناعم ومريح.'
                ],
                'preview_image' => 'templates/restaurant1.png',
                'page_type_id' => 2,
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

            // Store Templates (page_type_id: 3)
            [
                'name' => [
                    'en' => 'Pharmacy Store',
                    'ar' => 'متجر صيدلية'
                ],
                'slug' => 'pharmacy',
                'description' => [
                    'en' => 'A professional and clean pharmacy and medical store layout.',
                    'ar' => 'قالب صيدلية ومتجر مستلزمات طبية نظيف واحترافي.'
                ],
                'preview_image' => 'templates/store1.png',
                'page_type_id' => 3,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => [
                    'en' => 'Fashion Store',
                    'ar' => 'متجر أزياء'
                ],
                'slug' => 'fashion',
                'description' => [
                    'en' => 'A trendy, stylish, and visual-focused fashion boutique template.',
                    'ar' => 'قالب متجر أزياء وبوتيك عصري وجذاب بصرياً.'
                ],
                'preview_image' => 'templates/store2.png',
                'page_type_id' => 3,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($templates as $template) {
            Template::updateOrCreate(['slug' => $template['slug']], $template);
        }

        // Clean up obsolete templates not in the frontend list
        Template::whereNotIn('slug', array_column($templates, 'slug'))->delete();
    }
}
