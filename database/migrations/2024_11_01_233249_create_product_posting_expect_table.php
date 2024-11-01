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
        //data neu1 chọn theo các khung giờ
        Schema::create('product_posting_expect', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->index()->nullable();
            $table->unsignedBigInteger('posting_data_action_id')->index()->nullable();
            $table->integer('cron_completed')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_posting_expect');
    }
};
