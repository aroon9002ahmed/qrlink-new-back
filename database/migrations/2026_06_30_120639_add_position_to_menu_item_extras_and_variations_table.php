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
        Schema::table('restaurant_menu_item_extras', function (Blueprint $table) {
            $table->integer('position')->default(0)->after('is_available');
        });

        Schema::table('restaurant_menu_item_variations', function (Blueprint $table) {
            $table->integer('position')->default(0)->after('is_available');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurant_menu_item_extras', function (Blueprint $table) {
            $table->dropColumn('position');
        });

        Schema::table('restaurant_menu_item_variations', function (Blueprint $table) {
            $table->dropColumn('position');
        });
    }
};

