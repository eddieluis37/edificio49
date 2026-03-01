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
        Schema::create('types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('image')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();

            // Índices para optimizar búsquedas
            $table->index('name', 'idx_types_name');
            $table->index('order', 'idx_types_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('types');
    }
};
