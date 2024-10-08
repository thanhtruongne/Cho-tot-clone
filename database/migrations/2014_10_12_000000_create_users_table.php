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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->nullable()->unique();
            $table->text('avatar')->nullable();
            $table->string('firstname',150)->nullable();
            $table->string('lastname',150)->nullable(); 
            $table->dateTime('last_login')->nullable();
            $table->string('email')->unique();
            $table->boolean('re_login')->default(0);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->dateTime('dob')->nullable();
            $table->string( 'address')->nullable();
            $table->string('province_code')->index()->nullable();
            $table->string('district_code')->index()->nullable();
            $table->string('ward_code')->index()->nullable();
            $table->string('identity_card')->nullable()->comment('Số CMND');
            $table->dateTime('date_range')->nullable()->comment('Ngày cấp');
            $table->string('issued_by')->nullable()->comment('Nơi cấp');
            $table->integer('gender')->default(1)->comment('1:Nam, 0:Nữ');
            $table->string('phone', 50)->nullable();
            $table->dateTime('signing_create_account')->nullable()->comment('Ngày tạo tài khoản');
            $table->integer('status')->default(1)->comment('0: Block, 1: Active');
            $table->bigInteger('created_by')->nullable()->index();
            $table->bigInteger('updated_by')->nullable()->index();
            $table->integer('type_user')->default(1)->nullable()->comment("1: người dùng chưa đăng bán , 2 người dùng đã từng đăng bán sp");
            // $table->bigInteger('user_follow')->default(0)->comment('Số người theo dõi');
            $table->float('star')->default(0)->comment('Số sao đánh giá');
            $table->string('category_like')->nullable()->comment('Lấy các danh mục ửa thích dựa theo child-category-1 lưu theo dạng chuỗi array');
            $table->longText('content')->nullable();
            $table->longText('refresh_token')->nullable();
            $table->longText('signature_key')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
