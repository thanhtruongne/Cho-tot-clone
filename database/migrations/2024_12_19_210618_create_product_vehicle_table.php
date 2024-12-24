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
        Schema::create('product_vehicle', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->text('content');
            $table->unsignedBigInteger('user_id')->index();
            $table->string('code', 150)->unique()->index();
            $table->longText('images');
            $table->unsignedBigInteger('category_id')->index();
            $table->tinyInteger('type_product')->comment('1 Nhà ở , 2 eletric 3 xe'); 
            $table->integer('type_posting_id')->index()->default(1)->comment('loại tin đăng');
            $table->tinyInteger('approved')->default(2)->comment('0 là từ chối , 1 đã duyệt , 2 chờ duyệt');
            $table->tinyInteger('status')->default(1);
            $table->string('address')->nullable();
            $table->string('province_code')->nullable();
            $table->string('district_code')->nullable();
            $table->string('ward_code')->nullable();
            $table->tinyInteger('condition_used')->comment('1 là new , 2 đã sử dụng (chưa sữa chữa),3 đã sử dụng(qua sữa chữa)');
            $table->float('cost');
            $table->integer('load_btn_post')->nullable()->comment('nút load tin , tin theo dạng nút load tin');
            // dạng set theo null nếu k phải
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->unsignedBigInteger('company')->nullable()->comment('Hãng');
            $table->unsignedBigInteger('color_id')->nullable();
            $table->unsignedBigInteger('origin_from_id')->nullable()->comment('Xuất xứ');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_vehicle');
    }
};
