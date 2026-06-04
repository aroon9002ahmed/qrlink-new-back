<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSubscriptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data=[
            [
                'user_id' => 1,
                'subscription_plan_id' => 1, // Assuming plan with ID 1 exists [free plan]
                'billing_cycle' => 'yearly',
                'status' => 'active',
                'starts_at' => now(),
                'ends_at' => now()->addYear(),
                'amount_paid' => 0.00,
                'payment_method' => 'free',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more user subscriptions as needed
        ];

        DB::table('user_subscriptions')->insert($data);
    }
}
