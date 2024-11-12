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
        //
        Schema::dropIfExists('concentrators');
        Schema::create('concentrators', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('device_id', 255)->unique();
            $table->string('concentrator_label', 255)->nullable();
            $table->integer('concentrator_csq')->nullable();
            $table->string('concentrator_hp', 11)->nullable();
            $table->float('battery_voltage')->nullable();
            $table->dateTime('last_communication_packet_sent')->nullable();
            $table->string('last_assigned_ip_address', 255)->nullable();
            $table->string('remarks', 255)->nullable();
            $table->dateTime('created_at')->default(now());
            $table->dateTime('updated_at')->default(now());
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('concentrators');
    }
};