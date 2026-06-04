<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->string('slug')->unique();
            $table->json('description')->nullable();
            $table->decimal('price_monthly', 8, 2)->default(0);
            $table->decimal('price_yearly', 8, 2)->default(0);
            $table->integer('max_pages')->default(1);
            $table->integer('max_items')->default(20);
            $table->boolean('customization_templates')->default(true);
            $table->boolean('restaurant_table')->default(false);
            $table->boolean('delivery')->default(false);
            $table->boolean('takeaway')->default(false);
            $table->boolean('banners')->default(false);
            $table->boolean('qr_code')->default(true);
            $table->boolean('turn_off_Branding')->default(false);
            $table->boolean('analytics')->default(false);
            $table->boolean('priority_support')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};
