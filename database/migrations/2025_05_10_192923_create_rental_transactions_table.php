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
            $table->string('trx_id')->unique();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('phone_number');
            $table->date('started_at');
            $table->date('ended_at');
            $table->string('delivery_type'); // e.g., 'pickup', 'delivery'
            $table->text('address')->nullable();
            $table->decimal('total_amount', 10, 2);
            $table->boolean('is_paid')->default(false);
            $table->string('payment_proof')->nullable();
            $table->string('payment_method')->nullable(); // e.g., 'BCA', 'BRI'
            $table->string('status')->default('pending'); // e.g., 'pending', 'paid', 'cancelled'
            $table->timestamps();
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
