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
        Schema::create('garages', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // E.g., G-01
            $table->foreignId('apartment_id')->nullable()->constrained('apartments')->nullOnDelete();
            $table->foreignId('owner_id')->nullable()->constrained('owners')->nullOnDelete();
            
            // Decimal format to store percentage or fraction (e.g., 1.99 as 0.019900 or 1.990000)
            // Sticking to decimal(8,6) to match the Apartments table configuration.
            $table->decimal('share_fraction', 8, 6)->default(0.0199); // coefficient limit
            
            $table->string('status')->default('occupied'); // available, occupied
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('garages');
    }
};
