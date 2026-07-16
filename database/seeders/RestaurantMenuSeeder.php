<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RestaurantMenuItem;
use App\Models\RestaurantMenuCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RestaurantMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //create menu categories
        RestaurantMenuCategory::create([
            'page_id' => 1,
            'title' => 'الوجبات الرئيسية',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        //create menu items
        RestaurantMenuItem::create([
            'category_id' => 1,
            'page_id' => 1,
            'name' => 'فول بالزيت',
            'price' => 20,
            'description' => 'فول بالزيت',
            'image' => '',
            'position' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        RestaurantMenuItem::create([
            'category_id' => 1,
            'page_id' => 1,
            'name' => 'فول بالبيض',
            'price' => 25,
            'description' => 'فول بالبيض',
            'image' => '',
            'position' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        RestaurantMenuItem::create([
            'category_id' => 1,
            'page_id' => 1,
            'name' => 'فول بالبصل',
            'price' => 25,
            'description' => 'فول بالبصل',
            'image' => '',
            'position' => 3,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        RestaurantMenuItem::create([
            'category_id' => 1,
            'page_id' => 1,
            'name' => 'طعمية سادة',
            'price' => 10,
            'description' => '2 قرص طعمية سادة',
            'image' => '',
            'position' => 3,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        RestaurantMenuItem::create([
            'category_id' => 1,
            'page_id' => 1,
            'name' => 'طعمية محشية',
            'price' => 15,
            'description' => '2 قرص طعمية محشية بالشطة',
            'image' => '',
            'position' => 4,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $potatoItem = RestaurantMenuItem::create([
            'category_id' => 1,
            'page_id' => 1,
            'name' => 'طبق بطاطس',
            'price' => 10,
            'description' => 'طبق بطاطس مقلية مقرمشة ولذيذة',
            'image' => '',
            'position' => 5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $potatoItem->variations()->createMany([
            [
                'name' => 'صغير',
                'price' => 10,
                'is_available' => true,
                'position' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'كبير',
                'price' => 15,
                'is_available' => true,
                'position' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        $potatoItem->extras()->createMany([
            [
                'name' => 'جبنة شيدر',
                'price' => 5,
                'is_available' => true,
                'position' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'صوص كاتشب',
                'price' => 2,
                'is_available' => true,
                'position' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'صوص مايونيز',
                'price' => 2,
                'is_available' => true,
                'position' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);


        //create menu categories 2
        RestaurantMenuCategory::create([
            'page_id' => 1,
            'title' => 'مقبلات وسلطات',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        RestaurantMenuItem::create([
            'category_id' => 2,
            'page_id' => 1,
            'name' => 'سلطة خضراء',
            'price' => 15,
            'description' => 'سلطة خضراء طازجة',
            'image' => '',
            'position' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        RestaurantMenuItem::create([
            'category_id' => 2,
            'page_id' => 1,
            'name' => 'سلطة المايونيز بالمكرونة والدجاج',
            'price' => 35,
            'description' => 'سلطة المايونيز بالمكرونة والدجاج',
            'image' => '',
            'position' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        RestaurantMenuItem::create([
            'category_id' => 2,
            'page_id' => 1,
            'name' => 'متبل الزوكيني',
            'price' => 20,
            'description' => 'متبل الزوكيني',
            'image' => '',
            'position' => 3,
            'created_at' => now(),
            'updated_at' => now(),
        ]);


        //create menu categories 3
        RestaurantMenuCategory::create([
            'page_id' => 1,
            'title' => 'البرجر',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        RestaurantMenuItem::create([
            'category_id' => 3,
            'page_id' => 1,
            'name' => 'برجر اللحم',
            'price' => 20,
            'description' => 'برجر اللحم المشوي على الفحم',
            'image' => '',
            'position' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        RestaurantMenuItem::create([
            'category_id' => 3,
            'page_id' => 1,
            'name' => 'سوبر تشيكن فنجر',
            'price' => 20,
            'description' => 'برجر الدجاج باصابع الجبن المقلي وبنكهة سوبر تشيكن المميزة',
            'image' => '',
            'position' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        //create menu categories 4
        RestaurantMenuCategory::create([
            'page_id' => 1,
            'title' => 'الحلويات',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        RestaurantMenuItem::create([
            'category_id' => 4,
            'page_id' => 1,
            'name' => 'رز بلبن',
            'price' => 20,
            'description' => 'رز بلبن',
            'image' => '',
            'position' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        //create menu categories 5
        RestaurantMenuCategory::create([
            'page_id' => 1,
            'title' => 'شاورما',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        RestaurantMenuItem::create([
            'category_id' => 5,
            'page_id' => 1,
            'name' => 'شاورما لحم',
            'price' => 20,
            'description' => 'شاورما لحم',
            'image' => '',
            'position' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        RestaurantMenuItem::create([
            'category_id' => 5,
            'page_id' => 1,
            'name' => 'شاورما دجاج',
            'price' => 20,
            'description' => 'شاورما دجاج',
            'image' => '',
            'position' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        RestaurantMenuItem::create([
            'category_id' => 5,
            'page_id' => 1,
            'name' => 'شاورما سدق',
            'price' => 20,
            'description' => 'شاورما سدق',
            'image' => '',
            'position' => 3,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        //create menu categories 6
        RestaurantMenuCategory::create([
            'page_id' => 1,
            'title' => 'مشروبات باردة',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        //create menu items 6
        RestaurantMenuItem::create([
            'category_id' => 6,
            'page_id' => 1,
            'name' => 'Coca-Cola',
            'price' => 15,
            'description' => 'Coca-Cola',
            'image' => '',
            'position' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // RestaurantMenuItem::create([
        //     'category_id' => 6,
        //     'page_id' => 1,
        //     'name' => 'Pepsi',
        //     'price' => 14,
        //     // 'description' => 'Pepsi',
        //     'image' => '',
        //     'position' => 2,
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);

        // RestaurantMenuItem::create([
        //     'category_id' => 6,
        //     'page_id' => 1,
        //     'name' => 'Sprite',
        //     'price' => 12,
        //     // 'description' => 'Sprite',
        //     'image' => '',
        //     'position' => 3,
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);

        RestaurantMenuItem::create([
            'category_id' => 6,
            'page_id' => 1,
            'name' => 'ميلك اتشيك',
            'price' => 60,
            'description' => 'ميلك اتشيك فانليا بصوص الكراميل',
            'image' => '',
            'position' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        RestaurantMenuItem::create([
            'category_id' => 6,
            'page_id' => 1,
            'name' => 'ميلك اتشيك فرولا',
            'price' => 65,
            'description' => 'ميلك اتشيك فرولا بصوص الكراميل',
            'image' => '',
            'position' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        //create menu categories 7
        RestaurantMenuCategory::create([
            'page_id' => 1,
            'title' => 'مشروبات ساخنة',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        //create menu items 8
        RestaurantMenuItem::create([
            'category_id' => 7,
            'page_id' => 1,
            'name' => 'شاي',
            'price' => 15,
            'description' => 'شاي',
            'image' => '',
            'position' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // RestaurantMenuItem::create([
        //     'category_id' => 7,
        //     'page_id' => 1,
        //     'name' => 'قهوة تركي',
        //     'price' => 14,
        //     'image' => '',
        //     'position' => 2,
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);
    }
}
