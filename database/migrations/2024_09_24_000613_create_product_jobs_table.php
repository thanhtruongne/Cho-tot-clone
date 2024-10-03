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
        Schema::create('product_jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->text('content');
            $table->unsignedBigInteger('user_id')->index();
            $table->string('code', 150)->unique();
            $table->longText('images');
            $table->unsignedBigInteger('category_id')->index();
            $table->string('province_code')->index();
            $table->string('district_code')->index();
            $table->string('ward_code')->index();
            $table->bigInteger('quantity_user')->default(0);
            
            $table->unsignedBigInteger('type_profession_id')->index()->comment('ngành nghề');
            $table->unsignedBigInteger('type_jobs_id')->index()->comment('Loại công việc');
            $table->unsignedBigInteger('form_salary_id')->index()->comment('Hình thức trả lương');
            $table->float('min_salary')->default(0);
            $table->float('max_salary')->nullable() ;
            $table->float('salary')->nullable() ;
            $table->bigInteger('min_age');
            $table->bigInteger('max_age');
            $table->bigInteger('gender')->comment('1 là nam , 2 nữ , 3 không yêu cầu');
            $table->unsignedBigInteger('education_level_id')->index()->comment('trình độ học vấn');
            $table->unsignedBigInteger('work_exp_id')->index()->comment('kinh nghiệm làm việc');
           
            //các mục yêu cầu cv
            $table->tinyInteger('request_gender')->default(0)->comment('yêu cầu giới tính');
            $table->tinyInteger('request_year_born')->default(0)->comment('yêu cầu năm sinh');
            $table->tinyInteger('request_work_exp')->default(0)->comment('yêu cầu kinh nghiệm lv');
            $table->tinyInteger('request_edu_level')->default(0)->comment('yêu cầu trình độ hv');
            $table->tinyInteger('request_certificate')->default(0)->comment('yêu cầu bằng cấp');
            $table->tinyInteger('request_portrait_photo')->default(0)->comment('yêu cầu ảnh chân dung');
          
            $table->tinyInteger('have_question')->default(0)->comment('câu hỏi cho ứng viên');  
            $table->timestamps();
  



        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_jobs');
    }
};
