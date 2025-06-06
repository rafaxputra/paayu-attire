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
$table->string('material')->after('kebaya_preference')->nullable();
$table->string('color')->after('material')->nullable();
$table->string('selected_size_chart')->after('color')->nullable();
$table->text('custom_measurements')->after('selected_size_chart')->nullable();
});
}
/**
 * Reverse the migrations.
 */
public function down(): void
{
    Schema::table('custom_transactions', function (Blueprint $table) {
        $table->dropColumn('material');
        $table->dropColumn('color');
        $table->dropColumn('selected_size_chart');
        $table->dropColumn('custom_measurements');
    });
}
};
