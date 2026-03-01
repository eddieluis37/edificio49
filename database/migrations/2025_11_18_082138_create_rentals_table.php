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
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();

            // Identificación
            $table->string('barcode', 20)->unique();

            // Relaciones principales
            $table->foreignId('space_id')->constrained('parking_spaces')->onDelete('restrict');
            $table->foreignId('rate_id')->constrained('rates')->onDelete('restrict');
            $table->foreignId('vehicle_id')->nullable()->constrained('vehicles')->onDelete('set null');
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('set null');
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict'); // Usuario que registró

            // Información temporal
            $table->timestamp('check_in');
            $table->timestamp('check_out')->nullable();
            $table->string('total_time', 20)->nullable(); // Formato HH:MM:SS

            // Información financiera
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('paid_amount', 10, 2)->nullable();
            $table->decimal('change_amount', 10, 2)->nullable();

            // Estado y tipo
            $table->enum('status', ['open', 'closed', 'cancelled'])->default('open');
            $table->enum('rental_type', ['hourly', 'monthly'])->default('hourly');

            // Información adicional
            $table->string('description', 200)->nullable(); // Descripción breve del vehículo
            $table->text('notes')->nullable();

            // Campos legacy para compatibilidad con sistema viejo (opcional)
            $table->string('plate', 20)->nullable();
            $table->string('brand', 100)->nullable();
            $table->string('model', 100)->nullable();
            $table->string('color', 50)->nullable();

            // Timestamps y soft deletes
            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index('barcode');
            $table->index('space_id');
            $table->index('rate_id');
            $table->index('vehicle_id');
            $table->index('customer_id');
            $table->index('user_id');
            $table->index('status');
            $table->index('rental_type');
            $table->index('check_in');
            $table->index('check_out');
            $table->index(['status', 'space_id']); // Índice compuesto para buscar espacios ocupados
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};
