<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Change the column type to VARCHAR temporarily to drop and re-add the enum
        DB::statement('ALTER TABLE custom_transactions CHANGE status status VARCHAR(255)');

        Schema::table('custom_transactions', function (Blueprint $table) {
            // Drop the existing enum column
            $table->dropColumn('status');
        });

        Schema::table('custom_transactions', function (Blueprint $table) {
            // Add the column back with the new enum values
            $table->enum('status', ['pending', 'rejected', 'pending_payment_verification', 'payment_failed', 'payment_validated', 'in_progress', 'completed', 'cancelled'])->default('pending')->after('admin_estimated_completion_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         // Change the column type to VARCHAR temporarily to drop and re-add the enum
        DB::statement('ALTER TABLE custom_transactions CHANGE status status VARCHAR(255)');

        Schema::table('custom_transactions', function (Blueprint $table) {
            // Drop the existing enum column
            $table->dropColumn('status');
        });

        Schema::table('custom_transactions', function (Blueprint $table) {
            // Add the column back with the original enum values
            $table->enum('status', ['pending', 'accepted', 'rejected', 'in_progress', 'completed', 'cancelled'])->default('pending')->after('admin_estimated_completion_date');
        });
    }
};
