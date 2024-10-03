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
        Schema::create('user_activity_durations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->index();
            $table->string('session_id');
            $table->string('start_time')->comment('time stamp');
            $table->string('end_time')->nullable()->comment('time stamp');
            $table->string('last_acti_time')->nullable()->comment('time stamp');
            $table->unique(['user_id', 'session_id'], 'user_acti_duration_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_activity_durations');
    }
};
