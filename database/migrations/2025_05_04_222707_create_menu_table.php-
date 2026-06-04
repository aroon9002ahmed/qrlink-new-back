<?php

use Kalnoy\Nestedset\NestedSet;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('menu', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('type',['Static','Dynamic Route'])->default('Static');
            $table->string('link');
            $table->string('routeParameters')->nullable();
            $table->string('target')->default('_self');
            $table->integer('order_view')->default(0);
            // $table->unsignedBigInteger('parent_id')->nullable();
            NestedSet::columns($table);
            $table->integer('location')->default(1);
            $table->boolean('status')->default(1);
            $table->integer('created_by')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu');
    }
};
