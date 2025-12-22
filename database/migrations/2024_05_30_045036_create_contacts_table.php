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
            $table->timestamp('created_at')->useCurrent()->timezone('Asia/Singapore');
            $table->timestamp('updated_at')->useCurrent()->timezone('Asia/Singapore');
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
