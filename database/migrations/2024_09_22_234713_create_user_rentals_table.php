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
        Schema::create('partner_manager', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();   
            $table->unsignedBigInteger('user_code')->index();    
            $table->tinyInteger('type')->default(1)->comment('1 đối tác bên nhà tốt , 2 đối tác bên chợ tốt , 3, đối tác bên việc làm');    
            $table->longText('content')->comment('cam kết các thông tin khi trở thành đối tác');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_rentals');
    }
};
