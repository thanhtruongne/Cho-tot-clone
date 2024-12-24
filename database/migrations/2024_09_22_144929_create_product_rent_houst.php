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
            $table->tinyInteger('type_product')->nullable()->comment('1 là mua bán, 2  là cho thuê');
            $table->longText('images');
            $table->string('video');

            //mặc định là thángphp
            $table->integer('type_posting_id')->index()->default(1)->comment('loại tin đăng');
            $table->integer('payment')->index()->default(1)->comment('1 chưa thanh toán , 2 đã thanh toán');
            $table->dateTime('time_exipred')->nullable()->comment('thời gian tin đăng');
            $table->integer('number_pick_post')->nullable()->comment('số lần đăng tin theo dạng post ưu tiên');
            //TYPE posting 2
            $table->integer('day_posting_type')->nullable()->comment('Số ngày');

            // nút load tin lưu số lần
            $table->integer('load_btn_post')->nullable()->comment('nút load tin , tin theo dạng nút load tin');

            $table->tinyInteger('approved')->index()->default(2)->comment('0 là từ chối , 1 đã duyệt , 2 chờ duyệt');
            $table->tinyInteger('type_rental')->index()->default(3)->comment('1 thuê theo ngày , 2 thuê theo tuàn , 3 theo tháng , 4 theo năm');

            $table->string('province_code');
            $table->string('district_code');
            $table->string('ward_code');

            $table->tinyInteger('type_user')->default(1)->comment('1 là cá nhân , 2 là môi giới'); 
            $table->unsignedBigInteger('category_id');
            // $table->string('subdivision_code');
            // $table->integer('floor')->default(0)->comment('số tầng');
            $table->unsignedBigInteger('bedroom_id')->index()->comment('phòng ngủ');
            $table->unsignedBigInteger('bathroom_id')->index()->comment('phòng tắm');
            $table->unsignedBigInteger('main_door_id')->index()->comment('cửa chính');
            // $table->unsignedBigInteger('legal_id')->index()->comment('Giấy tờ pháp lý');
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
            $table->decimal('cost',10)->comment('giá thuê');
            $table->float('cost_deposit')->comment('Số tiền cọc');

            $table->integer('status')->default(1);
            $table->integer('sort')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_rent_house');
    }
};
