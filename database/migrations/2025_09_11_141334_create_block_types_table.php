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
        Schema::create('block_types', function (Blueprint $table) {
            $table->id();
            $table->json('name'); // اسم البلوك (Menu, Products, Social Links...)
            $table->json('description')->nullable(); // وصف اختياري
            $table->json('schema'); // مخطط الإعدادات كـ JSON
            $table->integer('status')->default(1); // حالة البلوك (1 = نشط، 0 = غير نشط)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('block_types');
    }
};
