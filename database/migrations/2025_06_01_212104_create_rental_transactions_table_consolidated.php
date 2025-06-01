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
        Schema::create('rental_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('trx_id')->unique();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('phone_number');
            $table->date('started_at');
            $table->date('ended_at');
            $table->decimal('total_amount', 10, 2);
            $table->string('payment_proof')->nullable();
            $table->string('payment_method')->nullable();
            $table->enum('status', [
                'pending_payment_verification',
                'payment_validated',
                'payment_failed',
                'ready_for_pickup',
                'in_rental',
                'completed',
                'rejected',
                'cancelled'
            ])->default('pending_payment_verification');
            $table->timestamps();
            $table->index('trx_id');
            $table->index('phone_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rental_transactions');
    }
};
