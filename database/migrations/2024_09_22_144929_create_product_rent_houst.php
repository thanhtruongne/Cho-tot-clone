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
        Schema::create('product_rent_house', function (Blueprint $table) {        
            $table->bigIncrements('id');
            $table->string('title');
            $table->text('content');
            $table->unsignedBigInteger('user_id')->index();
            $table->string('code', 150)->unique();
            $table->tinyInteger('type_product')->comment('1 là nhà ở ,  2  là phòng trọ');
            $table->longText('images');
            $table->string('video');
            //mặc định là thángphp
            $table->integer('type_posting_id')->index()->default(1)->comment('loại tin đăng');
            $table->tinyInteger('approved')->index()->default(2)->comment('0 là từ chối , 1 đã duyệt , 2 chờ duyệt');
            $table->tinyInteger('type_rental')->index()->default(3)->comment('1 thuê theo ngày , 2 thuê theo tuàn , 3 theo tháng , 4 theo năm');
            $table->string('province_code')->index();
            $table->string('district_code')->index();
            $table->string('ward_code')->index();
            // $table->tinyInteger('type_user')->default(1)->comment('1 là cá nhân , 2 là môi giới');
            $table->unsignedBigInteger('category_id')->index();
            $table->string('subdivision_code');
            // $table->tinyInteger('show_code')->default(0)->comment('show mã rao tin code');
            $table->integer('floor')->default(0)->comment('số tầng');
            $table->unsignedBigInteger('bedroom_id')->index()->comment('phòng ngủ');
            $table->unsignedBigInteger('bathroom_id')->index()->comment('phòng tắm');
            $table->unsignedBigInteger('main_door_id')->index()->comment('cửa chính');
            $table->unsignedBigInteger('legal_id')->index()->comment('Giấy tờ pháp lý');
            $table->unsignedBigInteger('condition_interior')->index()->comment('Tình trạng nội thất-- 1 nội thất cao cấp, 2 đầy dủ , 3 nha trống');     
            $table->tinyInteger('car_alley')->default(0)->comment('Hẻm xe hơi');
            $table->tinyInteger('back_house')->default(0)->comment('Nhà tóp hậu --> mảnh đất đầu nhỏ đuôi to, phía trước rộng, sau hẹp');
            $table->tinyInteger('blooming_house')->default(0)->comment('Nhà tóp hậu --> đất nở hậu là phía trước hẹp, sau rộng');
            $table->tinyInteger('not_completed_yet')->default(0)->comment('Nhà chưa hoàn công');
            $table->tinyInteger('land_not_changed_yet')->default(0)->comment('Đất chưa chuyển thổ');
            $table->tinyInteger('planning_or_road')->default(0)->comment('Nhà dính quy hoạch / lộ giới');
            $table->tinyInteger('diff_situation')->default(0)->comment('Hiện trạng khác');
           
            //Diện tích & giá
            $table->float('land_area')->comment('diện tích đất');
            $table->float( 'usable_area')->nullable()->comment('diện tích sử dụng');
            $table->float('horizontal')->nullable()->comment('chiều ngang');
            $table->float('length')->nullable()->comment('chiều dài');
            $table->float('cost')->comment('giá thuê');
            $table->float('cost_deposit')->comment('Số tiền cọc');
            // $table->integer('rule_deposit')->default(3)->comment('theo type_rental');
            // nếu khách hàng hủy trong thời gian thuê  --> khách sẽ chịu bồi thường = số tiền thuê * số lần quy định
            $table->integer('rule_compensation')->default(3)->comment('quy dịnh bồi thường');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_rent_house');
    }
};
