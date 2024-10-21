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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('vnp_amount'); // Số tiền
            $table->string('vnp_bank_code'); // Mã ngân hàng
            $table->string('vnp_bankTran_no'); // Số giao dịch ngân hàng
            $table->string('vnp_card_type'); // Loại thẻ
            $table->text('vnp_orderInfo'); // Thông tin đơn hàng
            $table->string('vnp_pay_date'); // Ngày thanh toán
            $table->string('vnp_response_code'); // Mã phản hồi
            $table->string('vnp_tmn_code'); // Mã thương mại
            $table->string('vnp_transaction_no'); // Số giao dịch
            $table->string('vnp_transaction_status'); // Trạng thái giao dịch
            $table->string('vnp_txn_ref'); // Tham chiếu giao dịch
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};