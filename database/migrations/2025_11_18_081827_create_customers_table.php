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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();

            // Información personal
            $table->string('name', 200);
            $table->string('email', 200)->nullable()->unique();
            $table->string('phone', 20)->nullable();
            $table->string('mobile', 20)->nullable();

            // Dirección
            $table->string('address', 500)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('zip_code', 20)->nullable();
            $table->string('country', 100)->nullable()->default('México');

            // Información adicional
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);

            // Timestamps y soft deletes
            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index('name');
            $table->index('email');
            $table->index('phone');
            $table->index('mobile');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
