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
        Schema::dropIfExists('projects');
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('job_number', 255)->unique();
            $table->string('client_name', 255);
            $table->string('end_user_name', 255)->nullable();
            $table->string('project_description', 255)->nullable();
            $table->string('project_type', 255);
            $table->string('jobsite_location', 255);
            $table->string('bca_reference_number', 255)->nullable();
            $table->string('status', 255)->nullable()->default('Draft');
            $table->integer('sms_count')->nullable()->default(0);
            $table->timestamps();
            $table->dateTime('completed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
