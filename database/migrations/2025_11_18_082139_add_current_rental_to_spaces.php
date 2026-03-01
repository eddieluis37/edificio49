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
        // Esta migración se ejecuta DESPUÉS de que rentals ya existe
        Schema::table('parking_spaces', function (Blueprint $table) {
            // Solo agregar si no existe
            if (!Schema::hasColumn('spaces', 'current_rental_id')) {
                $table->foreignId('current_rental_id')
                    ->nullable()
                    ->after('notes')
                    ->constrained('rentals')
                    ->onDelete('set null')
                ;
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parking_spaces', function (Blueprint $table) {
            if (Schema::hasColumn('parking_spaces', 'current_rental_id')) {
                $table->dropForeign(['current_rental_id']);
                $table->dropColumn('current_rental_id');
            }
        });
    }
};
