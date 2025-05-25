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
        Schema::table('custom_transactions', function (Blueprint $table) {
            $table->string('image_reference_2')->nullable()->after('image_reference');
            $table->string('image_reference_3')->nullable()->after('image_reference_2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('custom_transactions', function (Blueprint $table) {
            $table->dropColumn(['image_reference_2', 'image_reference_3']);
        });
    }
};
