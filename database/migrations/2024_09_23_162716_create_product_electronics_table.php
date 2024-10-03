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
        Schema::create('product_electronics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->text('content');
            $table->unsignedBigInteger('user_id')->index();
            $table->string('code', 150)->unique();
            $table->longText('images');
            $table->string('video');
            $table->unsignedBigInteger('category_id')->index();
            // $table->tinyInteger('type_product')->comment('1 là nhà ở ,  2  là phòng trọ'); 
            $table->integer('type_posting_id')->index()->default(1)->comment('loại tin đăng');
            $table->tinyInteger('approved')->default(2)->comment('0 là từ chối , 1 đã duyệt , 2 chờ duyệt');
            $table->tinyInteger('status')->default(1);
            $table->string('province_code')->index();
            $table->string('district_code')->index();
            $table->string('ward_code')->index();
            $table->tinyInteger('condition_used')->comment('1 là new , 2 đã sử dụng (chưa sữa chữa),3 đã sử dụng(qua sữa chữa)');
            $table->float('cost');
 
            // dạng set theo null nếu k phải
            $table->unsignedBigInteger('brand_id')->index()->nullable();
            $table->unsignedBigInteger('color_id')->index()->nullable();
            $table->unsignedBigInteger('capacity_id')->index()->nullable();
            $table->unsignedBigInteger('warrancy_policy_id')->index()->nullable()->comment('chính sách bảo hành');
            $table->unsignedBigInteger('origin_from_id')->index()->nullable()->comment('Xuất xứ');
            $table->unsignedBigInteger('screen_size_id')->index()->nullable();
            $table->unsignedBigInteger('microprocessor_id')->index()->nullable()->comment('bộ vi xử lý');
            $table->unsignedBigInteger('ram_id')->index()->nullable();
            $table->unsignedBigInteger('hard_drive_id')->index()->nullable()->comment('ổ cứng');
            $table->tinyInteger('type_hard_drive')->nullable()->comment('1 là HDD , 2 là SSD');
            $table->unsignedBigInteger('card_screen_id')->index()->nullable()->comment('card màn hình');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_electronics');
    }
};
