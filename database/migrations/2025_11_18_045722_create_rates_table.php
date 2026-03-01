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
        Schema::create('rates', function (Blueprint $table) {
            // Primary Key
            $table->id();

            // Foreign Keys
            $table->foreignId('type_id')
                ->constrained('types')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            // Main fields
            $table->string('description', 100);

            $table->decimal('price', 10, 2)
                ->unsigned();

            $table->unsignedInteger('time')
                ->nullable();

            $table->enum('rate_type', ['hourly', 'daily', 'monthly', 'fractional'])
                ->default('hourly');

            $table->boolean('active')
                ->default(true);

            // Timestamps
            $table->timestamps();

            // Soft Deletes
            $table->softDeletes();

            // Indexes for query optimization
            $table->index('type_id', 'idx_rates_type_id');
            $table->index('active', 'idx_rates_active');
            $table->index('rate_type', 'idx_rates_rate_type');
            $table->index('price', 'idx_rates_price');
            $table->index(['type_id', 'active'], 'idx_rates_type_active');
            $table->index('created_at', 'idx_rates_created_at');


            // Composite index for common queries
            $table->index(
                ['type_id', 'rate_type', 'active'],
                'idx_rates_type_rate_active'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rates');
    }
};
