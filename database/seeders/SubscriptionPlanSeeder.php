<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => [
                    'en' => 'Free',
                    'ar' => 'مجاني',
                ],
                'slug' => 'free',
                'description' => [
                    'en' => 'Basic plan with limited features',
                    'ar' => 'الخطة الأساسية مع ميزات محدودة',
                ],
                'price_monthly' => 0,
                'price_yearly' => 0,
                'max_links' => 10,
                'max_qrcodes' => 3,
                'max_pages' => 1,
                'max_items' => 20,
                'customization_templates' => true,
                'restaurant_table' => false,
                'delivery' => false,
                'takeaway' => false,
                'banners' => false,
                'qr_code' => true,
                'turn_off_Branding' => false,
                'analytics' => false,
                'priority_support' => false,
                'sort_order' => 1,
            ],
            [
                'name' => [
                    'en' => 'Pro',
                    'ar' => 'احترافي',
                ],
                'slug' => 'pro',
                'description' => [
                    'en' => 'For professionals and small teams',
                    'ar' => 'للمهنيين والفرق الصغيرة',
                ],
                'price_monthly' => 300.00,
                'price_yearly' => 3000.00,
                'max_links' => 20,
                'max_qrcodes' => 10,
                'max_pages' => 10,
                'max_items' => 100,
                'customization_templates' => true,
                'restaurant_table' => false,
                'delivery' => true,
                'takeaway' => true,
                'banners' => true,
                'qr_code' => true,
                'turn_off_Branding' => true,
                'analytics' => true,
                'priority_support' => true,
                'sort_order' => 2,
            ],
            [
                'name' => [
                    'en' => 'Enterprise',
                    'ar' => 'المؤسسات',
                ],
                'slug' => 'enterprise',
                'description' => [
                    'en' => 'Advanced features for businesses',
                    'ar' => 'ميزات متقدمة للشركات',
                ],
                'price_monthly' => 500.00,
                'price_yearly' => 5000.00,
                'max_links' => 50,
                'max_qrcodes' => 20,
                'max_pages' => 10,
                'max_items' => 200,
                'customization_templates' => true,
                'restaurant_table' => true,
                'delivery' => true,
                'takeaway' => true,
                'banners' => true,
                'qr_code' => true,
                'turn_off_Branding' => true,
                'analytics' => true,
                'priority_support' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::create($plan);
        }
    }
}
