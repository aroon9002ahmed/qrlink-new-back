<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Menu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MenuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $now = Carbon::now()->format('Y-m-d H:i:s');
        $defaultValues = [
            'type' => 'Static',
            'target' => '_self',
            'status' => 1,
            'created_by' => '1',
            'created_at' => $now,
            'updated_at' => $now
        ];

        // Create Main Categories
        $mainCategories = [
            [
                'title' => [
                    'en' => 'Home',
                    'ar' => 'الرئيسية'
                ],
                'link' => '/',
                'order_view' => 1,
                'location' => 1,
            ],
            [
                'title' => [
                    'en' => 'Templates',
                    'ar' => 'القوالب'
                ],
                'link' => '/templates',
                'order_view' => 2,
                'location' => 1,
                'subcategories' => [
                    [
                        'title' => [
                            'en' => 'Blue Sky',
                            'ar' => 'السماء الزرقاء'
                        ],
                        'link' => '/templates/blue-sky',
                        'order_view' => 1,
                        'location' => 1,
                    ],
                    [
                        'title' => [
                            'en' => 'gradient',
                            'ar' => 'التدرج',
                        ],
                        'link' => '/templates/gradient',
                        'order_view' => 2,
                        'location' => 1,
                    ],
                    [
                        'title' => [
                            'en' => 'Blue restaurant',
                            'ar' => 'المطعم الأزرق'
                        ],
                        'link' => '/templates/blue-restaurant',
                        'order_view' => 3,
                        'location' => 1,
                    ]
                ],
            ],
            [
                'title' => [
                    'en' => 'About',
                    'ar' => 'من نحن'
                ],
                'link' => '/about',
                'order_view' => 3,
                'location' => 1,
            ],
            [
                'title' => [
                    'en' => 'Contact',
                    'ar' => 'اتصل بنا'
                ],
                'link' => '/contact',
                'order_view' => 4,
                'location' => 1,
            ]


        ];

        foreach ($mainCategories as $mainCategory) {
            $subcategories = $mainCategory['subcategories'] ?? [];
            unset($mainCategory['subcategories']);

            // Create main category
            $category = Menu::create(array_merge($mainCategory, $defaultValues));

            // Create subcategories
            foreach ($subcategories as $subcategory) {
                $children = $subcategory['children'] ?? [];
                unset($subcategory['children']);

                $sub = Menu::create(array_merge($subcategory, [
                    'parent_id' => $category->id
                ], $defaultValues));

                // Create children
                foreach ($children as $child) {
                    Menu::create(array_merge($child, [
                        'parent_id' => $sub->id
                    ], $defaultValues));
                }
            }
        }



        // Footer Menu Categories
        $footerCategories = [
            [

                'title' => [
                    'en' => 'New In',
                    'ar' => 'جديد'
                ],
                'link' => 'shop/collections//new-in',
                'parent_id' => null,
                'order_view' => 1,
                'location' => 2
            ],
            [
                'title' => [
                    'en' => 'Woman',
                    'ar' => 'نساء'
                ],
                'link' => 'shop/collections//woman',
                'parent_id' => null,
                'order_view' => 2,
                'location' => 2
            ],
            [
                'title' => [
                    'en' => 'Men',
                    'ar' => 'رجال'
                ],
                'link' => 'shop/collections//men',
                'parent_id' => null,
                'order_view' => 3,
                'location' => 2
            ],
            [
                'title' => [
                    'en' => 'Shoes',
                    'ar' => 'أحذية'
                ],
                'link' => 'shop/collections//shoes',
                'parent_id' => null,
                'order_view' => 4,
                'location' => 2
            ],
            [
                'title' => [
                    'en' => 'Bags & Accessories',
                    'ar' => 'أكسسوارات'
                ],
                'link' => 'shop/collections//bags-accessories',
                'parent_id' => null,
                'order_view' => 5,
                'location' => 2
            ],
            [
                'title' => [
                    'en' => 'Top Brands',
                    'ar' => 'أفضل الماركات'
                ],
                'link' => 'shop/collections//top-brands',
                'parent_id' => null,
                'order_view' => 6,
                'location' => 2
            ]
        ];

        // Insert footer categories
        foreach ($footerCategories as $category) {
            // DB::table('menu')->insert(array_merge($category, $defaultValues));
            $category = Menu::create(array_merge($category, $defaultValues));
        }

        // Additional Footer Menu Categories
        $secondFooterCategories = [
            [
                'title' => [
                    'en' => 'About',
                    'ar' => 'من نحن'
                ],
                'link' => 'shop/collections//about',
                'parent_id' => null,
                'order_view' => 1,
                'location' => 3
            ],
            [
                'title' => [
                    'en' => 'Customer Service',
                    'ar' => 'خدمة العملاء'
                ],
                'link' => 'shop/collections//customer-service',
                'parent_id' => null,
                'order_view' => 2,
                'location' => 3
            ],
            [
                'title' => [
                    'en' => 'Reward Program',
                    'ar' => 'برنامج المكافآت'
                ],
                'link' => 'shop/collections//reward-program',
                'parent_id' => null,
                'order_view' => 3,
                'location' => 3
            ],
            [
                'title' => [
                    'en' => 'Shipping & Returns',
                    'ar' => 'الشحن والإرجاع'
                ],
                'link' => 'shop/collections//shipping-returns',
                'parent_id' => null,
                'order_view' => 4,
                'location' => 3
            ],
            [
                'title' => [
                    'en' => 'Privacy Policy',
                    'ar' => 'سياسة الخصوصية'
                ],
                'link' => 'shop/collections//privacy-policy',
                'parent_id' => null,
                'order_view' => 5,
                'location' => 3
            ],
            [
                'title' => [
                    'en' => 'Terms & Conditions',
                    'ar' => 'الشروط والأحكام'
                ],
                'link' => 'shop/collections//terms-conditions',
                'parent_id' => null,
                'order_view' => 6,
                'location' => 3
            ],

        ];

        // Insert second footer categories
        foreach ($secondFooterCategories as $category) {
            // DB::table('menu')->insert(array_merge($category, $defaultValues));
            $category = Menu::create(array_merge($category, $defaultValues));
        }


        // Third Footer Menu Categories
        $thirdFooterCategories = [
            [
                'title' => [
                    'en' => 'Search Terms',
                    'ar' => 'مصطلحات البحث'
                ],
                'link' => 'shop/collections//search-terms',
                'parent_id' => null,
                'order_view' => 1,
                'location' => 4
            ],
            [
                'title' => [
                    'en' => 'Advanced Search',
                    'ar' => 'البحث المتقدم'
                ],
                'link' => 'shop/collections//advanced-search',
                'parent_id' => null,
                'order_view' => 2,
                'location' => 4
            ],
            [
                'title' => [
                    'en' => 'Orders & Returns',
                    'ar' => 'طلبات وإرجاع'
                ],
                'link' => 'shop/collections//orders-returns',
                'parent_id' => null,
                'order_view' => 3,
                'location' => 4
            ],
            [
                'title' => [
                    'en' => 'Contact Us',
                    'ar' => 'اتصل بنا'
                ],
                'link' => 'shop/collections//contact-us',
                'parent_id' => null,
                'order_view' => 4,
                'location' => 4
            ],

        ];

        // Insert third footer categories
        foreach ($thirdFooterCategories as $category) {
            // DB::table('menu')->insert(array_merge($category, $defaultValues));
            $category = Menu::create(array_merge($category, $defaultValues));
        }
    }
}
