<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('apartment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('owner_id')->nullable()->constrained('owners')->nullOnDelete();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->string('number')->nullable();
            $table->date('date');      // fecha de emisión (inicio de periodo)
            $table->date('due_date')->nullable();
            $table->decimal('amount', 12, 2); // valor original

            $table->decimal('admin_base', 12, 2)->nullable();
            $table->decimal('honorarios', 12, 2)->nullable();            

            $table->decimal('discount', 12, 2)->default(0); // descuento aplicado
            $table->decimal('final_amount', 12, 2)->nullable(); // amount - discount
            $table->decimal('balance', 12, 2)->nullable();
            $table->boolean('early_discount_applied')->default(false);
            $table->string('status')->default('issued'); // issued, paid, partially
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
