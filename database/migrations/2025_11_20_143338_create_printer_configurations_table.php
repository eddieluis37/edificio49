<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('printer_configurations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Nombre descriptivo de la impresora');
            $table->enum('driver', ['escpos', 'browser', 'pdf'])->default('escpos')->comment('Driver de impresión');
            $table->enum('connection_type', ['usb', 'network', 'browser'])->default('usb')->comment('Tipo de conexión');
            $table->string('connection_string')->nullable()->comment('Ruta o IP de la impresora');
            $table->enum('paper_width', ['58', '80'])->default('80')->comment('Ancho de papel en mm');
            $table->boolean('is_default')->default(false)->comment('Impresora predeterminada');
            $table->boolean('is_active')->default(true)->comment('Impresora activa');
            $table->json('settings')->nullable()->comment('Configuraciones adicionales');
            $table->timestamps();

            // indices
            $table->index('is_default');
            $table->index('is_active');
            $table->index(['is_default', 'is_active']);
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('printer_configurations');
    }
};
