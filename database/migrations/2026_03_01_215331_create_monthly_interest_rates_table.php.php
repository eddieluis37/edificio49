<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('monthly_interest_rates', function (Blueprint $table) {
            $table->id();
            $table->year('year');
            $table->unsignedTinyInteger('month');
            $table->decimal('rate', 8,6); // ej 0.018 = 1.8% mensual
            $table->string('source')->nullable();
            $table->timestamps();

            $table->unique(['year','month']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('monthly_interest_rates');
    }
};