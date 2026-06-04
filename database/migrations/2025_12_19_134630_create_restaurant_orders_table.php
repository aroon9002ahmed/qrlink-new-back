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
        Schema::create('restaurant_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained('pages')->cascadeOnDelete();
            $table->integer('table_id')->nullable();
            $table->enum('type', ['table', 'delivery', 'takeaway'])->default('table');
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->text('customer_address')->nullable();
            $table->text('customer_note')->nullable();
            $table->integer('branch_id')->nullable(); // used with takeaway type only
            $table->text('order_note')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'completed', 'preparing', 'delivered', 'cancelled'])->default('pending');
            $table->decimal('total_price', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant_orders');
    }
};
