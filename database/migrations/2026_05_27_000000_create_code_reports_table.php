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

        Schema::create('code_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('short_code_id')->constrained('short_codes')->onDelete('cascade');
            $table->string('ip_address', 45);
            $table->text('reason')->nullable();
            $table->timestamps();

            // Unique index to enforce single report per IP per shortCode
            $table->unique(['short_code_id', 'ip_address']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('code_reports');
    }
};
