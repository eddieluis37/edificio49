<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('apartments', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // ej. Apt-101
            $table->unsignedInteger('floor')->nullable();
            $table->unsignedInteger('number')->nullable();
            $table->decimal('area', 8,2)->nullable();
            $table->decimal('share_fraction', 8,6)->default(0.1); // coeficiente
            $table->string('status')->default('occupied');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('apartments');
    }
};