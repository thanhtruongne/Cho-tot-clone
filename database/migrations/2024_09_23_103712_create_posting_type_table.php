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
            $table->string('code',150)->unique()->nullable();
            $table->string('name',150);
            $table->string('content')->nullable();
            $table->string('image')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->decimal('cost',10);
            $table->string('benefits')->nullable()->comment('quyền lợi');
            $table->string('rule_day')->nullable()->comment('Dạng số ngày cho phép vd :{1,3,7}');
            $table->integer('type')->default(1)->comment('1 tin thường , 2 là đẩy tin hẹn giờ;3 là tin ưu tiên');
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
