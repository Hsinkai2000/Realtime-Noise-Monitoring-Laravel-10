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
        Schema::dropIfExists('contacts');
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->integer('project_id')->nullable();
            $table->string('contact_person_name', 255)->nullable();
            $table->string('designation', 255)->nullable();
            $table->string('phone_number', 255)->nullable();
            $table->string('fax_number', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('contact_person_code', 255)->nullable();
            $table->string('office_tel', 255)->nullable();
            $table->integer('alert_status')->default(0);
            $table->text('days_of_alert')->nullable();
            $table->integer('alert_start_hour')->default(420);
            $table->integer('alert_end_hour')->default(1140);
            $table->dateTime('created_at')->default(now());
            $table->dateTime('updated_at')->default(now());
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
