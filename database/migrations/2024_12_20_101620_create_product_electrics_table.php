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
            $table->unsignedBigInteger('category_id')->index();
            $table->tinyInteger('type_product')->comment('1 Laptop , 2 Phone'); 
            $table->integer('type_posting_id')->index()->default(1)->comment('loại tin đăng');
            $table->tinyInteger('approved')->default(2)->comment('0 là từ chối , 1 đã duyệt , 2 chờ duyệt');
            $table->tinyInteger('status')->default(1);
            $table->string('province_code')->index();
            $table->string('district_code')->index();
            $table->string('ward_code')->index();
            $table->tinyInteger('condition_used')->comment('1 là new , 2 đã sử dụng (chưa sữa chữa),3 đã sử dụng(qua sữa chữa)');
            $table->float('cost');
            $table->integer('load_btn_post')->nullable()->comment('nút load tin , tin theo dạng nút load tin');
            // dạng set theo null nếu k phải
            $table->unsignedBigInteger('brand_id')->index()->nullable();
            $table->unsignedBigInteger('color_id')->index()->nullable();
            $table->unsignedBigInteger('origin_from_id')->index()->nullable()->comment('Xuất xứ');
            $table->unsignedBigInteger('ram_id')->index()->nullable();
        
           
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
