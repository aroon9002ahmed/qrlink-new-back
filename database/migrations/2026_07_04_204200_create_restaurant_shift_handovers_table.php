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
        Schema::create('restaurant_shift_handovers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained('pages')->cascadeOnDelete();
            $table->string('cashier_name');
            $table->decimal('opening_cash', 10, 2)->default(0.00);
            $table->decimal('system_sales', 10, 2)->default(0.00);
            $table->decimal('total_cash', 10, 2)->default(0.00);
            $table->decimal('next_opening_cash', 10, 2)->default(0.00);
            $table->decimal('cash_difference', 10, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant_shift_handovers');
    }
};
