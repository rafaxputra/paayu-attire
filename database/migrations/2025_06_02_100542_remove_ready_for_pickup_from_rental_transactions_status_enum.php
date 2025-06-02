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
        Schema::table('rental_transactions', function (Blueprint $table) {
            // Step 1: Temporarily change column to string
            $table->string('status', 50)->change();
        });

        // Step 2: Update existing 'ready_for_pickup' statuses to 'payment_validated'
        DB::statement("UPDATE rental_transactions SET status = 'payment_validated' WHERE status = 'ready_for_pickup'");

        Schema::table('rental_transactions', function (Blueprint $table) {
            // Define the new set of enum values
            $new_enum_values = [
                'pending_payment_verification',
                'payment_validated',
                'payment_failed',
                'in_rental',
                'completed',
                'rejected',
                'cancelled'
            ];

            // Step 3: Change the column back to enum with the new values
            $table->enum('status', $new_enum_values)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rental_transactions', function (Blueprint $table) {
            // Temporarily change column to string for rollback
            $table->string('status', 50)->change();
        });

        // Revert 'payment_validated' statuses that were 'ready_for_pickup' back to 'ready_for_pickup'
        DB::statement("UPDATE rental_transactions SET status = 'ready_for_pickup' WHERE status = 'payment_validated'");


        Schema::table('rental_transactions', function (Blueprint $table) {
            // Define the old set of enum values for rollback
            $old_enum_values = [
                'pending_payment_verification',
                'payment_validated',
                'payment_failed',
                'ready_for_pickup', // Re-add for rollback
                'in_rental',
                'completed',
                'rejected',
                'cancelled'
            ];

            // Revert the column to the old enum type
            $table->enum('status', $old_enum_values)->change();
        });
    }
};
