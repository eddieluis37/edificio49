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
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('cashier')->after('password'); // admin, cashier, viewer
            $table->boolean('active')->default(true)->after('role');
            $table->string('profile_photo')->nullable()->after('active');
            
            // Índices para búsquedas optimizadas
            $table->index('role');
            $table->index('active');
            $table->index(['role', 'active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['users_role_index']);
            $table->dropIndex(['users_active_index']);
            $table->dropIndex(['users_role_active_index']);
            
            $table->dropColumn(['role', 'active', 'profile_photo']);
        });
    }
};
