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
            //
            $table->dateTime('missing_data_last_alert_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('measurement_points', function (Blueprint $table) {
            //
            $table->dropColumn('missing_data_last_alert_at');
        });
    }
};
