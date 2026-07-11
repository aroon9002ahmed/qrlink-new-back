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
        Schema::table('page_types', function (Blueprint $table) {
            $table->boolean('has_banners')->default(false);
            $table->boolean('has_social_media')->default(true);
            $table->boolean('has_branches')->default(false);
            $table->boolean('has_products')->default(false);
            $table->boolean('has_orders')->default(false);
            $table->boolean('has_tables')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('page_types', function (Blueprint $table) {
            $table->dropColumn([
                'has_banners',
                'has_social_media',
                'has_branches',
                'has_products',
                'has_orders',
                'has_tables'
            ]);
        });
    }
};
