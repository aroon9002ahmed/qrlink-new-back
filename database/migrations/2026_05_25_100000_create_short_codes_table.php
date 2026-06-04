<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('short_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->comment('The unique short code');
            $table->morphs('codeable'); // codeable_id + codeable_type + index
            $table->unsignedBigInteger('clicks')->default(0)->comment('Total clicks/scans');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('short_codes');
    }
};
