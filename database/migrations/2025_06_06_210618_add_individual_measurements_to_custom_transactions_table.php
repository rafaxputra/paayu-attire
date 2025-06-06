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
            $table->double('lebar_bahu_belakang')->after('custom_measurements')->nullable();
            $table->double('lingkar_panggul')->after('lebar_bahu_belakang')->nullable();
            $table->double('lingkar_pinggul')->after('lingkar_panggul')->nullable();
            $table->double('lingkar_dada')->after('lingkar_pinggul')->nullable();
            $table->double('kerung_lengan')->after('lingkar_dada')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('custom_transactions', function (Blueprint $table) {
            $table->dropColumn('lebar_bahu_belakang');
            $table->dropColumn('lingkar_panggul');
            $table->dropColumn('lingkar_pinggul');
            $table->dropColumn('lingkar_dada');
            $table->dropColumn('kerung_lengan');
        });
    }
};