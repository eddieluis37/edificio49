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
        Schema::create('cash_closures', function (Blueprint $table) {
            $table->id();

            // Usuario que realizó el corte
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');

            // Usuario cajero (si es corte individual)
            $table->foreignId('cashier_id')->nullable()->constrained('users')->onDelete('set null');

            // Período del corte
            $table->timestamp('period_start');
            $table->timestamp('period_end');

            // Datos calculados
            $table->decimal('expected_cash', 10, 2)->default(0);
            $table->integer('total_rentals')->default(0);
            $table->decimal('average_per_rental', 10, 2)->default(0);

            // Datos ingresados por operador
            $table->decimal('real_cash', 10, 2)->default(0);
            $table->decimal('difference', 10, 2)->default(0);

            // Alertas
            $table->integer('open_tickets')->default(0);
            $table->boolean('had_open_tickets')->default(false);

            // Información adicional
            $table->text('notes')->nullable();
            $table->enum('status', ['open', 'closed'])->default('closed');

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Índices para búsquedas rápidas
            $table->index('user_id');
            $table->index('cashier_id');
            $table->index('period_start');
            $table->index('period_end');
            $table->index('status');
            $table->index(['cashier_id', 'period_start', 'period_end']); // Para prevenir duplicados
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_closures');
    }
};
