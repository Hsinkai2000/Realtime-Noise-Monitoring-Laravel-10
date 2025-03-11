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
        Schema::dropIfExists('alert_logs');
        Schema::create('alert_logs', function (Blueprint $table) {
            $table->id();
            $table->dateTime('event_timestamp');
            $table->string('email_messageId', 255)->nullable();
            $table->text('email_debug')->nullable();
            $table->string('sms_messageId', 255)->nullable();
            $table->dateTime('sms_status_updated')->nullable();
            $table->string('sms_status', 255)->nullable();
            $table->dateTime('created_at')->default(now());
            $table->dateTime('updated_at')->default(now());
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alert_logs');
    }
};
