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
        // để sau
        Schema::create('broker', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('product_rent_id')->index();
            $table->tinyInteger('status')->default(1)->comment('1 là chờ , 2 thành công');
            //mặc định phần trăm hoa hồng là 10%
            $table->bigInteger('percent_commission')->default(10)->comment('Phần trăm hoa hồng');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('broker');
    }
};
