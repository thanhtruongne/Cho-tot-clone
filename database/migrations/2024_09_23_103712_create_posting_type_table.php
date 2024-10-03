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
            $table->tinyInteger('status')->default(1);
            $table->float('cost');
            $table->integer('number_day')->nullable()->comment('số ngày áp dụng');
            $table->integer('rule_make_by_order')->default(0)->comment('mặc định user khi đăng tin tầm 5 6 lần thỏa dk thì set theo điều kiện');
            // $table->integer('rule_make_by_order')->default(0)->comment('mặc định user khi đăng tin tầm 5 6 lần thỏa dk thì set theo điều kiện');
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
