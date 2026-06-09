<?php

namespace Database\Seeders;

use App\Models\Configurations;
use Illuminate\Database\Seeder;

class ConfigrationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $configurations = [
            [
                'slug' => 'websiteTitle',
                'name' => [
                    'en' => 'Create Smart QR Pages & Digital Profiles Easily',
                    'ar' => 'أنشئ صفحتك الذكية عبر كود QR واحد'
                ],
                'created_by' => 1
            ],
            [
                'slug' => 'websiteDescription',
                'name' => [
                    'en' => "QR Tree lets you create your own smart digital page with a unique QR code. Perfect for restaurants, stores, and individuals who want to share all their links in one place.",
                    'ar' => 'أنشئ صفحتك الرقمية بسهولة عبر موقع QR Tree وشارك كل روابطك في مكان واحد باستخدام كود QR ذكي. مناسب للمطاعم، المتاجر، والأفراد.'
                ],
                'created_by' => 1
            ],
            [
                'slug' => 'websiteKeyword',
                'name' => [
                    'en' => "QR code generator, QR page, digital profile, link in bio, create QR code, QR for restaurant, QR for products, smart QR, digital business card, QR website, QR menu, QR Tree",
                    'ar' => 'مولد رمز الاستجابة السريعة، صفحة رمز الاستجابة السريعة، الملف الرقمي، الرابط في السيرة الذاتية، إنشاء رمز الاستجابة السريعة، رمز الاستجابة السريعة للمطعم، رمز الاستجابة السريعة للمنتجات، رمز الاستجابة السريعة الذكي، البطاقة الرقمية، موقع رمز الاستجابة السريعة، قائمة رمز الاستجابة السريعة، شجرة رمز الاستجابة السريعة'

                ],
                'created_by' => 1
            ],
            [
                'slug' => 'email',
                'name' => 'contact@qrtree.link',
                'created_by' => 1
            ],
            [
                'slug' => 'phone',
                'name' => '01005222130',
                'created_by' => 1
            ],
            [
                'slug' => 'whatsapp',
                'name' => 'https://wa.me/201005222130',
                'note' => 'example: https://wa.me/201005222130',
                'created_by' => 1
            ],
            [
                'slug' => 'address',
                'name' => 'Main Address',
                'created_by' => 1
            ],
            [
                'slug' => 'currency',
                'name' => [
                    'en' => 'USD',
                    'ar' => 'دولار'
                ],
                'created_by' => 1
            ],
            [
                'slug' => 'topMessage',
                'name' => [
                    'en' => 'Free Shipping for orders over 1000 EGP.',
                    'ar' => 'توصيل مجاني للطلبات التي تزيد عن 1000 جنية.'
                ],
                'note' => 'this message apper on top of homepage as offer',
                'created_by' => 1
            ],
            [
                'slug' => 'facebook',
                'name' => 'facebook',
                'note' => 'Facebook page full link',
                'created_by' => 1
            ],
            [
                'slug' => 'instagram',
                'name' => 'instagram',
                'note' => 'instagram page full link',
                'created_by' => 1
            ],
            [
                'slug' => 'linkedin',
                'name' => 'linkedin',
                'note' => 'linkedin page full link',
                'created_by' => 1
            ],
            [
                'slug' => 'tiktok',
                'name' => 'tiktok',
                'note' => 'tiktok page full link',
                'created_by' => 1
            ],
            [
                'slug' => 'freeShipping',
                'name' => [
                    'en' => '1000',
                    'ar' => '1000'
                ],
                'note' => 'Free Shipping for orders over this value',
                'created_by' => 1
            ],
            [
                'slug' => 'maximum-quantity',
                'name' => [
                    'en' => '5',
                    'ar' => '5'
                ],
                'note' => 'Maximum quantity for each product in cart',
                'created_by' => 1
            ],
            [
                'slug' => 'orders-email',
                'name' => [
                    'en' => 'a.saad@qit-eg.com',
                    'ar' => 'a.saad@qit-eg.com'
                ],
                'note' => 'Email to receive new orders notifications',
                'created_by' => 1
            ],
            [
                'slug' => 'products-per-page',
                'name' => [
                    'en' => '20',
                    'ar' => '20'
                ],
                'note' => 'Number of products to show per page in product listing',
                'created_by' => 1
            ],
            [
                'slug' => 'enable-online-payment',
                'name' => [
                    'en' => '1',
                    'ar' => '1'
                ],
                'note' => 'Enable or disable online payment option [1=enable. 0=disable]',
                'created_by' => 1
            ]
        ];

        foreach ($configurations as $config) {
            Configurations::create($config);
        }
    }
}
