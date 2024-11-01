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
        Schema::create('cron', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code')->comment('Mã');
            $table->string('command')->comment('Tác vụ');
            $table->string('description', 256)->nullable()->comment('Miêu tả');
            $table->string('minute')->nullable()->comment('phút');
            $table->string('hour')->nullable()->comment('giờ');
            $table->string('day')->nullable()->comment('ngày');
            $table->string('month')->nullable()->comment('tháng');
            $table->string('day_of_week')->nullable()->comment('ngày của tuần');
            $table->string('expression')->nullable()->comment('biểu thức');
            $table->dateTime('last_run')->nullable();
            $table->integer('enabled')->default(1)->nullable()->comment('1 enabled 0 disabled');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cron');
    }
};
