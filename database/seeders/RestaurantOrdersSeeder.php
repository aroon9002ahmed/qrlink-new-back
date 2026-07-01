<?php

namespace Database\Seeders;

use App\Models\RestaurantOrder;
use Illuminate\Database\Seeder;
use App\Models\RestaurantOrderItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RestaurantOrdersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RestaurantOrder::create([
            'customer_name' => 'John Doe [Delivery]',
            'customer_phone' => '123456789',
            'customer_address' => '123 Main St',
            'page_id' => 1,
            'type' => 'delivery',
            'status' => 'pending',
            'total_price' => 125,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        //add items to order
        RestaurantOrderItem::create([
            'order_id' => 1,
            'menu_item_id' => 1,
            'quantity' => 1,
            'price' => 60,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        RestaurantOrderItem::create([
            'order_id' => 1,
            'menu_item_id' => 2,
            'quantity' => 2,
            'price' => 65,
            'created_at' => now(),
            'updated_at' => now(),
        ]);


        // Order Number 2
        RestaurantOrder::create([
            'customer_name' => 'Ahmed Saad',
            'customer_phone' => '01005222130',
            'page_id' => 1,
            'type' => 'takeaway',
            'branch_id' => 1,
            'status' => 'pending',
            'total_price' => 310,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        //add items to order
        RestaurantOrderItem::create([
            'order_id' => 2,
            'menu_item_id' => 1,
            'quantity' => 3,
            'price' => 60,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        RestaurantOrderItem::create([
            'order_id' => 2,
            'menu_item_id' => 2,
            'quantity' => 2,
            'price' => 65,
            'created_at' => now(),
            'updated_at' => now(),
        ]);


        // Order Number 3
        RestaurantOrder::create([
            'customer_name' => 'John Doe 3',
            'customer_phone' => '123456789',
            'page_id' => 1,
            'table_id' => 3,
            'status' => 'pending',
            'total_price' => 125,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        //add items to order
        RestaurantOrderItem::create([
            'order_id' => 3,
            'menu_item_id' => 1,
            'quantity' => 1,
            'price' => 60,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        RestaurantOrderItem::create([
            'order_id' => 3,
            'menu_item_id' => 2,
            'quantity' => 2,
            'price' => 65,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Order Number 4
        RestaurantOrder::create([
            'customer_name' => 'John Doe 4',
            'customer_phone' => '123456789',
            'page_id' => 1,
            'table_id' => 4,
            'status' => 'pending',
            'total_price' => 125,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        //add items to order
        RestaurantOrderItem::create([
            'order_id' => 4,
            'menu_item_id' => 1,
            'quantity' => 1,
            'price' => 60,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        RestaurantOrderItem::create([
            'order_id' => 4,
            'menu_item_id' => 2,
            'quantity' => 2,
            'price' => 65,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Order Number 5
        RestaurantOrder::create([
            'customer_name' => 'John Doe 5',
            'customer_phone' => '123456789',
            'page_id' => 1,
            'table_id' => 5,
            'status' => 'pending',
            'total_price' => 125,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        //add items to order
        RestaurantOrderItem::create([
            'order_id' => 5,
            'menu_item_id' => 1,
            'quantity' => 1,
            'price' => 60,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        RestaurantOrderItem::create([
            'order_id' => 5,
            'menu_item_id' => 2,
            'quantity' => 2,
            'price' => 65,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Order Number 6
        RestaurantOrder::create([
            'customer_name' => 'John Doe 6',
            'customer_phone' => '123456789',
            'page_id' => 1,
            'table_id' => 6,
            'status' => 'pending',
            'total_price' => 125,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        //add items to order
        RestaurantOrderItem::create([
            'order_id' => 6,
            'menu_item_id' => 1,
            'quantity' => 1,
            'price' => 60,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        RestaurantOrderItem::create([
            'order_id' => 6,
            'menu_item_id' => 2,
            'quantity' => 2,
            'price' => 65,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
