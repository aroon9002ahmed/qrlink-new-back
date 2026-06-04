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
        Schema::create('page_type_block', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_type_id')->constrained('page_types')->cascadeOnDelete();
            $table->foreignId('block_type_id')->constrained('block_types')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['page_type_id', 'block_type_id']); // منع التكرار
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_type_block');
    }
};
