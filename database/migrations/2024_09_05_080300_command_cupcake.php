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
        Schema::create('command_cupcake', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('command_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cupcake_id')->constrained()->cascadeOnDelete();
            $table->integer('quantity');
            $table->integer('price_at_time');
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
