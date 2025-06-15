<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rental_transactions', function (Blueprint $table) {
            $table->string('selected_size')->nullable()->after('phone_number');
            $table->integer('late_days')->nullable()->after('ended_at');
            $table->decimal('late_fee', 10, 2)->nullable()->after('late_days');
            $table->enum('status', [
                'pending_payment_verification',
                'payment_validated',
                'payment_failed',
                'ready_for_pickup',
                'in_rental',
                'completed',
                'late_returned',
                'rejected',
                'cancelled'
            ])->default('pending_payment_verification')->change();
        });
    }

    public function down(): void
    {
        Schema::table('rental_transactions', function (Blueprint $table) {
            $table->dropColumn('selected_size');
            $table->dropColumn('late_days');
            $table->dropColumn('late_fee');
            $table->enum('status', [
                'pending_payment_verification',
                'payment_validated',
                'payment_failed',
                'ready_for_pickup',
                'in_rental',
                'completed',
                'rejected',
                'cancelled'
            ])->default('pending_payment_verification')->change();
        });
    }
};
