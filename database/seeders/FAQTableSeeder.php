<?php

namespace Database\Seeders;

use App\Models\Faqs;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FAQTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [
            [
                'question' => [
                    'en' => 'How can I create a QR code?',
                    'ar' => 'كيف يمكنني إنشاء رمز استجابة سريعة؟'
                ],
                'answer' => [
                    'en' => 'To create a QR code, register on our platform, select the type of activity, add your logo, project, and customize your digital page.',
                    'ar' => 'لإنشاء رمز استجابة سريعة، قم بالتسجيل على منصتنا، حدد نوع النشاط، اضف شعارك مشروعك وقم بتخصيص صفحتك الرقمية.'
                ],
                'status' => 1,
                'order' => 1,
                'created_by' => 1
            ],
            [
                'question' => [
                    'en' => 'Can I use QR codes for my restaurant menu?',
                    'ar' => 'هل يمكنني استخدام رموز الاستجابة السريعة لقائمة مطعمي؟'
                ],
                'answer' => [
                    'en' => 'Yes, our platform is perfect for restaurants looking to create digital menus accessible via QR codes.',
                    'ar' => 'نعم، منصتنا مثالية للمطاعم التي تتطلع إلى إنشاء قوائم رقمية يمكن الوصول إليها عبر رموز الاستجابة السريعة.'
                ],
                'status' => 1,
                'order' => 2,
                'created_by' => 1
            ],
            [
                'question' => [
                    'en' => 'Is there a free plan available?',
                    'ar' => 'هل هناك خطة مجانية متاحة؟'
                ],
                'answer' => [
                    'en' => 'Yes, we offer a free plan with basic features to get you started, but it does not support some features such as receiving delivery orders and ordering from the branch.',
                    'ar' => 'نعم، نقدم خطة مجانية مع ميزات أساسية لتبدأ بها ولكن لا تدعم بعض المميزات مثل استلام طلبات التوصيل و الطلب من الفرع .'
                ],
                'status' => 1,
                'order' => 3,
                'created_by' => 1
            ],
            [
                'question' => [
                    'en' => 'How do I upgrade my subscription plan?',
                    'ar' => 'كيف يمكنني ترقية خطة الاشتراك الخاصة بي؟'
                ],
                'answer' => [
                    'en' => 'You can upgrade your subscription plan from your account settings under the Subscription section to get all the features mentioned according to the chosen plan.',
                    'ar' => 'يمكنك ترقية خطة الاشتراك الخاصة بك من إعدادات حسابك ضمن قسم الاشتراك حتي تحصل علي جميع المميزات المذكورة حسب الخطة المختارة.'
                ],
                'status' => 1,
                'order' => 4,
                'created_by' => 1
            ],
            [
                'question' => [
                    'en' => "Is it expected that my page's URL or QR code will change in the future?",
                    'ar' => 'هل من المتوقع تغيير رابط الصفحة او QR الخاص بصفحتي في المستقبل؟'
                ],
                'answer' => [
                    'en' => 'Your link or QR code will never change; it is permanent and reserved for your account.',
                    'ar' => 'لم يتم تغير الرابط الخاص بك او كيور ابدا فهو ثابت ومحجوز بحسابك'
                ],
                'status' => 1,
                'order' => 5,
                'created_by' => 1
            ]

        ];

        foreach ($faqs as $faq) {
            Faqs::create($faq);
        }
    }
}
