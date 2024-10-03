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
        Schema::create('product_jobs_user_view_cv', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->integer('order')->default(0);
            $table->string('name');
            $table->unsignedBigInteger('year_born_id')->nullable();
            $table->tinyInteger('gender')->nullable()->comment('1 là nam , 2 là nữ , 3 là không tiết lộ');
            $table->string('phone');
            $table->unsignedBigInteger('edu_level_id')->nullable();
            $table->unsignedBigInteger('certificate_id')->nullable();
            $table->string('portrait_photo')->nullable();
            $table->longText('content')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_jobs_user_view_cv');
    }
};
