<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->index(['owner_id', 'date']);
            $table->index(['status']);
            $table->index(['balance']);
            $table->index(['apartment_id']);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->index(['invoice_id']);
            $table->index(['owner_id']);
            $table->index(['date']);
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropIndex(['owner_id', 'date']);
            $table->dropIndex(['status']);
            $table->dropIndex(['balance']);
            $table->dropIndex(['apartment_id']);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex(['invoice_id']);
            $table->dropIndex(['owner_id']);
            $table->dropIndex(['date']);
        });
    }
};