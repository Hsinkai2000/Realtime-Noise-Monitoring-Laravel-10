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
        Schema::table('noise_data', function (Blueprint $table) {
            $table->string('noise_meter_serial')->nullable();
            $table->index('noise_meter_serial');

            $table->dropUnique('noise_data_measurement_point_id_received_at_unique');
            $table->unsignedBigInteger('measurement_point_id')->nullable()->change();
            $table->foreign('measurement_point_id')->references('id')->on('measurement_points')->onDelete('set null');
            $table->unique(['measurement_point_id', 'received_at'])->whereNotNull('measurement_point_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('noise_data', function (Blueprint $table) {
            $table->dropIndex(['noise_meter_serial']);
            $table->dropColumn('noise_meter_serial');

            $table->dropForeign('noise_data_measurement_point_id_foreign');
        });
    }
};
