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
            $table->boolean('is_paid')->default(false)->after('total_amount'); // Add is_paid column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rental_transactions', function (Blueprint $table) {
            $table->dropColumn('is_paid'); // Remove is_paid column
        });
    }
};
