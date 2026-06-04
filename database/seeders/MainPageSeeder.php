<?php

namespace Database\Seeders;

use App\Models\MainPage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MainPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mainPage = [
            [
                'name' => [
                    'en' => 'About Us',
                    'ar' => 'من نحن',

                ],
                'slug' => 'about-us',
                'body' => [
                    'en' => 'About Us Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                    'ar' => 'من نحن Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',

                ],
                'meta_title' => [
                    'en' => 'About Us - Your Company',
                    'ar' => 'من نحن - شركتك',
                ],
                'meta_description' => [
                    'en' => 'Learn more about our company.',
                    'ar' => 'تعرف على المزيد حول شركتنا.'
                ],
                'meta_keywords' => [
                    'en' => 'about us, company, info',
                    'ar' => 'من نحن، الشركة، المعلومات'
                ],
                'layout' => '',
                'status' => 1,
                'created_by' => 1,
            ],
            [
                'name' => [
                    'en' => 'Privacy Policy',
                    'ar' => 'سياسة الخصوصية',
                ],
                'slug' => 'privacy-policy',
                'body' => [
                    'en' => 'Privacy Policy Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                    'ar' => 'سياسة الخصوصية Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                ],
                'meta_title' => [
                    'en' => 'Privacy Policy - Your Company',
                    'ar' => 'سياسة الخصوصية - شركتك',
                ],
                'meta_description' => [
                    'en' => 'Read our privacy policy.',
                    'ar' => 'اقرأ سياسة الخصوصية الخاصة بنا.'
                ],
                'meta_keywords' => [
                    'en' => 'privacy, policy, data protection',
                    'ar' => 'الخصوصية، السياسة، حماية البيانات'
                ],
                'layout' => '',
                'status' => 1,
                'created_by' => 1,
            ],
            [
                'name' => [
                    'en' => 'Contact Us',
                    'ar' => 'اتصل بنا',
                ],
                'slug' => 'contact-us',
                'body' => [
                    'en' => 'Contact Us Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                    'ar' => 'اتصل بنا Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                ],
                'meta_title' => [
                    'en' => 'Contact Us - Your Company',
                    'ar' => 'اتصل بنا - شركتك',
                ],
                'meta_description' => [
                    'en' => 'Get in touch with us.',
                    'ar' => 'تواصل معنا.'
                ],
                'meta_keywords' => [
                    'en' => 'contact, support, help',
                    'ar' => 'اتصل، دعم، مساعدة'
                ],
                'layout' => '',
                'status' => 1,
                'created_by' => 1,
            ]
        ];

        foreach ($mainPage as $page) {
            MainPage::create($page);
        }
    }
}
