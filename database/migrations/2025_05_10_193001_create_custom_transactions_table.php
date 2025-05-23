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
        Schema::create('custom_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('trx_id')->unique();
            $table->string('name');
            $table->string('phone_number');
            $table->text('image_reference')->nullable(); // Path to stored image
            $table->text('kebaya_preference');
            $table->integer('amount_to_buy'); // Changed to integer for quantity
            $table->date('date_needed');
            $table->decimal('admin_price', 10, 2)->nullable();
            $table->date('admin_estimated_completion_date')->nullable();
            $table->enum('status', ['pending', 'accepted', 'rejected', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->boolean('is_paid')->default(false);
            $table->string('payment_proof')->nullable();
            $table->string('payment_method')->nullable(); // e.g., 'BCA', 'BRI'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_transactions');
    }
};
