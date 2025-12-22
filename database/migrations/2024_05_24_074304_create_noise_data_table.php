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
        Schema::dropIfExists('noise_data');
        if (!Schema::hasTable('noise_data')) {
            Schema::create('noise_data', function (Blueprint $table) {
                $table->id();
                $table->integer('measurement_point_id');
                $table->float('leq');
                $table->dateTime('received_at');
                $table->timestamp('created_at')->useCurrent()->timezone('Asia/Singapore');
                $table->timestamp('updated_at')->useCurrent()->timezone('Asia/Singapore');

                $table->unique(['measurement_point_id', 'received_at']);
                $table->index('measurement_point_id');
                $table->index('received_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('noise_data');
    }
};
