<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restaurant_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained()->onDelete('cascade');
            $table->string('currency', 3)->default('EGP');
            $table->string('currency_symbol', 10)->default('ج.م');
            $table->enum('currency_position', ['before', 'after'])->default('after');
            $table->string('opening_hours')->nullable();
            $table->string('hotline')->nullable();
            $table->boolean('enable_orders')->default(true);
            $table->boolean('enable_takeaway')->default(true);
            $table->boolean('enable_delivery')->default(true);
            $table->boolean('enable_tables')->default(true);
            $table->timestamps();

            $table->unique('page_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restaurant_settings');
    }
};
