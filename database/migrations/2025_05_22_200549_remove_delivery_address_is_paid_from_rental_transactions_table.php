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
        Schema::table('rental_transactions', function (Blueprint $table) {
            $table->dropColumn('delivery_type');
            $table->dropColumn('address');
            $table->dropColumn('is_paid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rental_transactions', function (Blueprint $table) {
            // Re-add the columns in the reverse migration
            $table->string('delivery_type')->nullable();
            $table->string('address')->nullable();
            $table->boolean('is_paid')->default(false);
        });
    }
};
