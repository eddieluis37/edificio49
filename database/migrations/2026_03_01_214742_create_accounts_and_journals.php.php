<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // ej "1.1"
            $table->string('name');
            $table->tinyInteger('level')->default(1);
            $table->foreignId('parent_id')->nullable()->constrained('accounts')->nullOnDelete();
            $table->string('type')->nullable(); // asset, liability, equity, income, expense
            $table->decimal('balance', 14,2)->default(0);
            $table->timestamps();
        });

        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->string('number')->nullable();
            $table->date('date');
            $table->text('description')->nullable();
            $table->decimal('total_debit', 14,2)->default(0);
            $table->decimal('total_credit', 14,2)->default(0);
            $table->string('status')->default('posted');
            $table->timestamps();
        });

        Schema::create('journal_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journal_entry_id')->constrained()->cascadeOnDelete();
            $table->foreignId('account_id')->constrained()->cascadeOnDelete();
            $table->decimal('debit', 14,2)->default(0);
            $table->decimal('credit', 14,2)->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('journal_items');
        Schema::dropIfExists('journal_entries');
        Schema::dropIfExists('accounts');
    }
};