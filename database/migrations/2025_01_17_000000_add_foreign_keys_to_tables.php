<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToTables extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('project_id')->change();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        });

        Schema::table('sound_limits', function (Blueprint $table) {
            $table->unsignedBigInteger('measurement_point_id')->change();
            $table->foreign('measurement_point_id')->references('id')->on('measurement_points')->onDelete('cascade');
        });

        Schema::table('measurement_points', function (Blueprint $table) {
            $table->unsignedBigInteger('project_id')->change();
            $table->unsignedBigInteger('noise_meter_id')->nullable()->change();
            $table->unsignedBigInteger('concentrator_id')->nullable()->change();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('noise_meter_id')->references('id')->on('noise_meters')->onDelete('set null');
            $table->foreign('concentrator_id')->references('id')->on('concentrators')->onDelete('set null');
        });

        Schema::table('contacts', function (Blueprint $table) {
            $table->unsignedBigInteger('project_id')->change();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['project_id']);
        });

        Schema::table('sound_limits', function (Blueprint $table) {
            $table->dropForeign(['measurement_point_id']);
        });

        Schema::table('measurement_points', function (Blueprint $table) {
            $table->dropForeign(['project_id']);
            $table->dropForeign(['noise_meter_id']);
            $table->dropForeign(['concentrator_id']);
        });

        Schema::table('contacts', function (Blueprint $table) {
            $table->dropForeign(['project_id']);
        });
    }
}