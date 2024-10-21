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
        Schema::create('posting_type', function (Blueprint $table) {
            $table->id();
            $table->string('code',150)->unique();
            $table->string('name',150);
            $table->string('content');
            $table->string('image')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->float('cost');
            $table->integer('number_day')->nullable()->comment('số ngày áp dụng');
            $table->string('rule_day')->nullable()->comment('Dạng số ngày cho phép vd :{1,3,7}');
            $table->integer('type')->default(1)->comment('1 tin thường , 2 là đẩy tin hẹn giờ;3 là tin ưu tiên');
            // nếu type là 2
            $table->dateTime('start_time')->nullable()->comment('Giờ bắt đầu, khi chọn type là 2 thì set các trường này');
            $table->dateTime('end_time')->nullable()->comment('Giờ kết thúc');
            $table->integer('day_access')->default(0)->comment('Số ngày áp dụng');

            // tin ưu tiên
            $table->integer('day_access_prioritize')->default(0)->comment('Số ngày áp dụng tin ưu tiên');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posting_type');
    }
};
