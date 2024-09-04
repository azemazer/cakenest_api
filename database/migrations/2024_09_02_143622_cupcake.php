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
        Schema::create('cupcakes', function (Blueprint $table) {
            $table->id();
            $table->string('imageSource')->nullable();
            $table->string('title');
            $table->integer('price');
            $table->integer('quantity')->default(0);
            $table->boolean('isAvailable')->default(false);
            $table->boolean('isAdvertised')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};