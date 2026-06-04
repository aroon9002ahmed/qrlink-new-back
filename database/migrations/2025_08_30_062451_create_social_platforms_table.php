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
        Schema::create('social_platforms', function (Blueprint $table) {
            $table->id();
            $table->string('name');        // WhatsApp, Facebook, Instagram
            $table->string('icon');        // fa-whatsapp, fa-facebook
            $table->string('type');        // url, phone, email
            $table->string('base_url')->nullable(); // زي https://wa.me/ او https://facebook.com/
            $table->string('color')->nullable(); // لون المنصة
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_platforms');
    }
};
