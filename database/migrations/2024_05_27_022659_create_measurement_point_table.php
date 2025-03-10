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
        Schema::dropIfExists('measurement_points');
        Schema::create('measurement_points', function (Blueprint $table) {
            $table->id();
            $table->integer('project_id');
            $table->integer('noise_meter_id')->index()->nullable();
            $table->integer('concentrator_id')->index()->nullable();
            $table->string('point_name', 255);
            $table->string('remarks', 255)->nullable();
            $table->float('inst_leq')->nullable()->default(0);
            $table->integer('leq_temp')->nullable()->default(0);
            $table->decimal('dose_flag', 11, 0)->default(0);
            $table->string('device_location', 255)->nullable();
            $table->dateTime('leq_5_mins_last_alert_at')->nullable();
            $table->dateTime('leq_1_hour_last_alert_at')->nullable();
            $table->dateTime('leq_12_hours_last_alert_at')->nullable();
            $table->dateTime('dose_70_last_alert_at')->nullable();
            $table->dateTime('dose_100_last_alert_at')->nullable();
            $table->timestamps();

            $table->unique(['point_name', 'project_id']);
            $table->index('point_name');
            $table->index('project_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('measurement_points');
    }
};
