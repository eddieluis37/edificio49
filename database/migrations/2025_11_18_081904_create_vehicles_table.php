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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();

            // Información del vehículo
            $table->string('plate', 20)->unique();
            $table->string('brand', 100);
            $table->string('model', 100);
            $table->year('year')->nullable();
            $table->string('color', 50)->nullable();

            // Relaciones
            $table->foreignId('type_id')->constrained('types')->onDelete('restrict');
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('set null');

            // Información adicional
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);

            // Timestamps y soft deletes
            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index('plate');
            $table->index('type_id');
            $table->index('customer_id');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
