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
        Schema::table('restaurant_settings', function (Blueprint $table) {
            $table->boolean('enable_delivery')->default(false)->change();
            $table->boolean('enable_tables')->default(false)->change();
            $table->boolean('enable_takeaway')->default(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurant_settings', function (Blueprint $table) {
            $table->boolean('enable_delivery')->default(true)->change();
            $table->boolean('enable_tables')->default(true)->change();
            $table->boolean('enable_takeaway')->default(true)->change();
        });
    }
};
