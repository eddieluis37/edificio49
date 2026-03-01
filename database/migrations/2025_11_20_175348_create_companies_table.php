<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();

            // Información básica
            $table->string('name')->comment('Nombre de la empresa');
            $table->string('business_name')->nullable()->comment('Razón social');
            $table->string('rfc', 13)->nullable()->comment('RFC de la empresa');

            // Contacto
            $table->string('email')->nullable()->comment('Email de contacto');
            $table->string('phone', 20)->nullable()->comment('Teléfono principal');
            $table->string('mobile', 20)->nullable()->comment('Teléfono móvil');
            $table->string('website')->nullable()->comment('Sitio web');

            // Dirección
            $table->string('address')->nullable()->comment('Dirección completa');
            $table->string('city', 100)->nullable()->comment('Ciudad');
            $table->string('state', 100)->nullable()->comment('Estado');
            $table->string('country', 100)->default('México')->comment('País');
            $table->string('postal_code', 10)->nullable()->comment('Código postal');

            // Logo e imagen
            $table->string('logo_path')->nullable()->comment('Ruta del logo');
            $table->string('logo_url')->nullable()->comment('URL pública del logo');

            // Configuración de recibos
            $table->text('receipt_footer')->nullable()->comment('Texto pie de página en recibos');
            $table->text('receipt_terms')->nullable()->comment('Términos y condiciones');
            $table->boolean('show_logo_on_receipt')->default(true)->comment('Mostrar logo en recibos');

            // Configuración de negocio
            $table->string('timezone')->default('America/Mexico_City')->comment('Zona horaria');
            $table->string('currency')->default('MXN')->comment('Moneda');
            $table->string('currency_symbol')->default('$')->comment('Símbolo de moneda');

            // Control
            $table->boolean('is_active')->default(true)->comment('Empresa activa');

            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index('is_active');
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
