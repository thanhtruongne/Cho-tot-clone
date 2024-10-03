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
        Schema::create('managerment_user_comment', function (Blueprint $table) {
            $table->bigIncrements('id');
            // $table->bigInteger('product_id');
            $table->bigInteger('user_id');
            $table->longText('content');
            $table->integer('failed')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('managerment_user_comment');
    }
};
