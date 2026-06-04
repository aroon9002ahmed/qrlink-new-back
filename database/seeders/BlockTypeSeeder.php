<?php

namespace Database\Seeders;

use App\Models\BlockType;
use Illuminate\Database\Seeder;

class BlockTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $blockTypes = [
            [
                'name' => [
                    'en' => 'Header',
                    'ar' => 'رأس الصفحة',
                ],
                'description' => [
                    'en' => 'Main heading section with title and subtitle',
                    'ar' => 'قسم العنوان الرئيسي مع العنوان الفرعي',
                ],
                'schema' => json_encode([
                    ['key' => 'title', 'type' => 'string', 'label' => 'Title'],
                    ['key' => 'subtitle', 'type' => 'string', 'label' => 'Subtitle'],
                    ['key' => 'color', 'type' => 'color', 'label' => 'Text Color'],
                    ['key' => 'background_color', 'type' => 'color', 'label' => 'Background Color'],
                ]),
            ],
            [
                'name' => [
                    'en' => 'Text',
                    'ar' => 'نص',
                ],
                'description' => [
                    'en' => 'Rich text content block for paragraphs and articles',
                    'ar' => 'كتلة محتوى نصي غني للفقرات والمقالات',
                ],
                'schema' => json_encode([
                    ['key' => 'title', 'type' => 'string', 'label' => 'Title'],
                    ['key' => 'content', 'type' => 'textarea', 'label' => 'Content'],
                    ['key' => 'alignment', 'type' => 'select', 'label' => 'Alignment', 'options' => ['left', 'center', 'right']],
                    ['key' => 'color', 'type' => 'color', 'label' => 'Text Color'],
                    ['key' => 'background_color', 'type' => 'color', 'label' => 'Background Color'],
                ]),
            ],
            [
                'name' => [
                    'en' => 'Image',
                    'ar' => 'صورة',
                ],
                'description' => [
                    'en' => 'Image display with caption and alt text',
                    'ar' => 'عرض صورة مع شرح نصي ونص بديل',
                ],
                'schema' => json_encode([
                    ['key' => 'title', 'type' => 'string', 'label' => 'Title'],
                    ['key' => 'url', 'type' => 'image', 'label' => 'Image URL'],
                    ['key' => 'alt_text', 'type' => 'string', 'label' => 'Alt Text'],
                    ['key' => 'content', 'type' => 'textarea', 'label' => 'Caption'],
                    ['key' => 'color', 'type' => 'color', 'label' => 'Text Color'],
                    ['key' => 'background_color', 'type' => 'color', 'label' => 'Background Color'],
                ]),
            ],
            [
                'name' => [
                    'en' => 'Contact',
                    'ar' => 'اتصل بنا',
                ],
                'description' => [
                    'en' => 'Contact information with phone, email, and address',
                    'ar' => 'معلومات الاتصال مع الهاتف، البريد الإلكتروني، والعنوان',
                ],
                'schema' => json_encode([
                    ['key' => 'title', 'type' => 'string', 'label' => 'Title'],
                    ['key' => 'phone', 'type' => 'tel', 'label' => 'Phone'],
                    ['key' => 'email', 'type' => 'email', 'label' => 'Email'],
                    ['key' => 'address', 'type' => 'textarea', 'label' => 'Address'],
                    ['key' => 'color', 'type' => 'color', 'label' => 'Text Color'],
                    ['key' => 'background_color', 'type' => 'color', 'label' => 'Background Color'],
                ]),
            ],
            // [REMOVED] Social block type
            // [
            //     'name' => 'Social',
            //     'description' => 'Social media links and profiles',
            //     'schema' => json_encode([
            //         ['key' => 'title', 'type' => 'string', 'label' => 'Title'],
            //         ['key' => 'color', 'type' => 'color', 'label' => 'Text Color'],
            //         ['key' => 'background_color', 'type' => 'color', 'label' => 'Background Color'],
            //     ]),
            // ],
            // [
            //     'name' => 'Menu',
            //     'description' => 'Restaurant menu with categories and items',
            //     'schema' => json_encode([
            //         ['key' => 'title', 'type' => 'string', 'label' => 'Title'],
            //         ['key' => 'menu_id', 'type' => 'relation', 'label' => 'Select Menu'],
            //         ['key' => 'color', 'type' => 'color', 'label' => 'Text Color'],
            //         ['key' => 'background_color', 'type' => 'color', 'label' => 'Background Color']
            //     ])
            // ],
            [
                'name' => [
                    'en' => 'Product',
                    'ar' => 'منتج',
                ],
                'description' =>
                [
                    'en' => 'Product showcase with pricing and price include Link',
                    'ar' => 'عرض المنتج مع التسعير والرابط',
                ],
                'schema' => json_encode([
                    ['key' => 'title', 'type' => 'string', 'label' => 'Title'],
                    ['key' => 'price', 'type' => 'string', 'label' => '10.00'],
                    ['key' => 'color', 'type' => 'color', 'label' => 'Text Color'],
                    ['key' => 'background_color', 'type' => 'color', 'label' => 'Background Color'],
                ]),
            ],
            // [REMOVED] Gallery block type
            // [
            //     'name' => 'Gallery',
            //     'description' => 'Image gallery with lightbox functionality',
            //     'schema' => json_encode([
            //         ['key' => 'title', 'type' => 'string', 'label' => 'Title'],
            //         ['key' => 'color', 'type' => 'color', 'label' => 'Text Color'],
            //         ['key' => 'background_color', 'type' => 'color', 'label' => 'Background Color'],
            //     ]),
            // ],
            [
                'name' =>
                [
                    'en' => 'Video',
                    'ar' => 'فيديو',
                ],
                'description' =>
                [
                    'en' => 'Video embed from YouTube, Vimeo, or direct upload',
                    'ar' => 'تضمين فيديو من YouTube، Vimeo، أو التحميل المباشر',
                ],
                'schema' => json_encode([
                    ['key' => 'title', 'type' => 'string', 'label' => 'Title'],
                    ['key' => 'url', 'type' => 'string', 'label' => 'Video URL'],
                    ['key' => 'content', 'type' => 'textarea', 'label' => 'Description'],
                    ['key' => 'color', 'type' => 'color', 'label' => 'Text Color'],
                    ['key' => 'background_color', 'type' => 'color', 'label' => 'Background Color'],
                ]),
            ],
            [
                'name' => [
                    'en' => 'Map',
                    'ar' => 'خريطة',
                ],
                'description' => [
                    'en' => 'Interactive map with location markers',
                    'ar' => 'خريطة تفاعلية مع علامات المواقع',
                ],
                'schema' => json_encode([
                    ['key' => 'title', 'type' => 'string', 'label' => 'Title'],
                    ['key' => 'address', 'type' => 'textarea', 'label' => 'Address', 'default' => 'Cairo Tower, Kasr Al Nile, Zamalek, Cairo, Egypt'],
                    [
                        'key' => 'zoom',
                        'type' => 'number',
                        'label' => 'Zoom Level (1-20)',
                        'required' => false,
                        'min' => 1,
                        'max' => 20,
                    ],
                    ['key' => 'color', 'type' => 'color', 'label' => 'Text Color'],
                    ['key' => 'background_color', 'type' => 'color', 'label' => 'Background Color'],
                ]),
            ],
            [
                'name' => [
                    'en' => 'Button',
                    'ar' => 'زر',
                ],
                'description' => [
                    'en' => 'Call-to-action button with customizable styling',
                    'ar' => 'زر دعوة لاتخاذ إجراء مع تنسيق قابل للتخصيص',
                ],
                'schema' => json_encode([
                    ['key' => 'title', 'type' => 'string', 'label' => 'Button Text'],
                    ['key' => 'url', 'type' => 'string', 'label' => 'Button URL'],
                    ['key' => 'style', 'type' => 'select', 'label' => 'Button Style', 'options' => ['primary', 'secondary', 'outline', 'gradient']],
                    ['key' => 'size', 'type' => 'select', 'label' => 'Button Size', 'options' => ['small', 'medium', 'large']],
                    ['key' => 'icon', 'type' => 'string', 'label' => 'Icon Class (optional)'],
                    ['key' => 'color', 'type' => 'color', 'label' => 'Text Color'],
                    ['key' => 'background_color', 'type' => 'color', 'label' => 'Background Color'],
                    ['key' => 'open_new_tab', 'type' => 'boolean', 'label' => 'Open in New Tab'],
                ]),
            ],
            [
                'name' =>
                [
                    'en' => 'Testimonial',
                    'ar' => 'تقييم',
                ],
                'description' => [
                    'en' => 'Customer testimonial with rating and photo',
                    'ar' => 'شهادة عميل مع التقييم والصورة',
                ],
                'schema' => json_encode([
                    ['key' => 'name', 'type' => 'string', 'label' => 'Customer Name'],
                    ['key' => 'content', 'type' => 'textarea', 'label' => 'Testimonial Text'],
                    ['key' => 'rating', 'type' => 'select', 'label' => 'Rating', 'options' => ['1', '2', '3', '4', '5']],
                    ['key' => 'position', 'type' => 'string', 'label' => 'Customer Position/Title'],
                    ['key' => 'image', 'type' => 'image', 'label' => 'Customer Photo'],
                    ['key' => 'color', 'type' => 'color', 'label' => 'Text Color'],
                    ['key' => 'background_color', 'type' => 'color', 'label' => 'Background Color'],
                ]),
            ],
            [
                'name' => [
                    'en' => 'FAQ',
                    'ar' => 'الأسئلة الشائعة',
                ],
                'description' => [
                    'en' => 'Frequently asked questions with expandable answers',
                    'ar' => 'الأسئلة الشائعة مع إجابات قابلة للتوسيع',
                ],
                'schema' => json_encode([
                    ['key' => 'title', 'type' => 'string', 'label' => 'Section Title'],
                    ['key' => 'questions', 'type' => 'repeater', 'label' => 'FAQ Items', 'fields' => [
                        ['key' => 'question', 'type' => 'string', 'label' => 'Question'],
                        ['key' => 'answer', 'type' => 'textarea', 'label' => 'Answer'],
                    ]],
                    ['key' => 'color', 'type' => 'color', 'label' => 'Text Color'],
                    ['key' => 'background_color', 'type' => 'color', 'label' => 'Background Color'],
                ]),
            ],
            [
                'name' => [
                    'en' => 'Divider',
                    'ar' => 'فاصل',
                ],
                'description' => [
                    'en' => 'Visual separator with customizable styling',
                    'ar' => 'فاصل بصري مع تنسيق قابل للتخصيص',
                ],
                'schema' => json_encode([
                    ['key' => 'style', 'type' => 'select', 'label' => 'Divider Style', 'options' => ['solid', 'dashed', 'dotted', 'gradient']],
                    ['key' => 'thickness', 'type' => 'select', 'label' => 'Thickness', 'options' => ['thin', 'medium', 'thick']],
                    ['key' => 'width', 'type' => 'select', 'label' => 'Width', 'options' => ['25%', '50%', '75%', '100%']],
                    ['key' => 'alignment', 'type' => 'select', 'label' => 'Alignment', 'options' => ['left', 'center', 'right']],
                    ['key' => 'color', 'type' => 'color', 'label' => 'Divider Color'],
                    ['key' => 'margin_top', 'type' => 'select', 'label' => 'Top Spacing', 'options' => ['0', '1', '2', '3', '4', '5']],
                    ['key' => 'margin_bottom', 'type' => 'select', 'label' => 'Bottom Spacing', 'options' => ['0', '1', '2', '3', '4', '5']],
                ]),
            ],
        ];

        foreach ($blockTypes as $blockType) {
            BlockType::firstOrCreate(
                ['name' => $blockType['name']],
                $blockType
            );
        }
    }
}
