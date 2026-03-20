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
        if (!Schema::hasTable('treasury_entries')) {
            Schema::create('treasury_entries', function (Blueprint $table) {
                $table->id();
                $table->date('date');
                $table->string('type'); // 'income', 'expense', 'petty_cash_in', 'petty_cash_out'
                $table->string('number')->nullable();
                
                $table->foreignId('account_id')->constrained('accounts'); // Asset (Caja/Banco)
                $table->foreignId('counterpart_account_id')->constrained('accounts'); // Contrapartida (Ingreso/Gasto)
                
                $table->decimal('amount', 15, 2);
                $table->text('description')->nullable();
                
                $table->foreignId('supplier_id')->nullable()->constrained();
                $table->foreignId('owner_id')->nullable()->constrained();
                
                $table->foreignId('journal_entry_id')->nullable()->constrained();
                
                $table->string('status')->default('posted'); 
                $table->string('payment_method')->nullable();
                $table->string('reference_doc')->nullable();
                
                $table->timestamps();
            });
        }

        if (Schema::hasTable('expenses') && !Schema::hasColumn('expenses', 'journal_entry_id')) {
            Schema::table('expenses', function (Blueprint $table) {
                $table->unsignedBigInteger('journal_entry_id')->nullable();
                $table->foreign('journal_entry_id')->references('id')->on('journal_entries');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('expenses') && Schema::hasColumn('expenses', 'journal_entry_id')) {
            Schema::table('expenses', function (Blueprint $table) {
                $table->dropForeign(['journal_entry_id']);
                $table->dropColumn('journal_entry_id');
            });
        }
        Schema::dropIfExists('treasury_entries');
    }
};
