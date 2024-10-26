<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $table = 'payments';
    protected $fillable = [
        'user_id',
        'product_id',
        'vnp_amount',
        'vnp_bank_code',
        'vnp_bankTran_no',
        'vnp_card_type',
        'vnp_orderInfo',
        'vnp_pay_date',
        'vnp_response_code',
        'vnp_tmn_code',
        'vnp_transaction_no',
        'vnp_transaction_status',
        'vnp_txn_ref',
       
    ];
}