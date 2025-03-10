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
        Schema::table('measurement_points', function (Blueprint $table) {
            $table->string('alert_start_time')->default('07:00')->nullable();
            $table->string('alert_end_time')->default('22:00')->nullable();
            $table->string('alert_days')->nullable();
            $table->integer('alert_mode')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('measurement_points', function (Blueprint $table) {
            $table->dropColumn('alert_start_time');
            $table->dropColumn('alert_end_time');
            $table->dropColumn('alert_days');
            $table->dropColumn('alert_mode');
        });
    }
};
