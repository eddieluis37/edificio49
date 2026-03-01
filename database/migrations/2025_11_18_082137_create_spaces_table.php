<?php

declare(strict_types=1);

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
        Schema::create('parking_spaces', function (Blueprint $table) {
            // Primary Key
            $table->id();

            // Código único del espacio (ej: "A-101", "B-205")
            $table->string('code', 20)
                ->unique();

            // Descripción del espacio
            $table->string('description', 200);

            // Foreign Key al tipo de vehículo
            $table->foreignId('type_id')
                ->constrained('types')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            // Estado del espacio
            $table->enum('status', ['available', 'occupied', 'maintenance', 'reserved'])
                ->default('available');

            // Notas adicionales (opcional)
            $table->text('notes')
                ->nullable();

            // Timestamps
            $table->timestamps();

            // Soft Deletes
            $table->softDeletes();

            // Índices para optimización de consultas
            $table->index('code', 'idx_parking_spaces_code');
            $table->index('type_id', 'idx_parking_spaces_type_id');
            $table->index('status', 'idx_parking_spaces_status');
            $table->index(['type_id', 'status'], 'idx_parking_spaces_type_status');
            $table->index('created_at', 'idx_parking_spaces_created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parking_spaces');
    }
};
