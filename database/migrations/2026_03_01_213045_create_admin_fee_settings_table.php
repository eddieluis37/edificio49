<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('admin_fee_settings', function (Blueprint $table) {
            $table->id();
            $table->year('year');
            $table->unsignedTinyInteger('month');
            $table->decimal('base_budget', 14, 2);

            // presupuesto mensual total

            $table->decimal('rate_per_sqm', 10, 2)->nullable();
            $table->decimal('honorarios_default', 12, 2)->nullable();

            $table->boolean('early_discount_enabled')->default(true);
            $table->unsignedInteger('early_discount_days')->default(10);
            $table->enum('early_discount_type', ['fixed', 'percent'])->default('fixed');
            $table->decimal('early_discount_value', 12, 2)->default(6000); // o porcentaje si type=percent
            $table->date('due_date')->nullable();
            $table->timestamps();

            $table->unique(['year', 'month']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_fee_settings');
    }
};
