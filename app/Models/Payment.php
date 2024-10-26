<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $table = 'payments';
    protected $fillable = [
<<<<<<< HEAD
        "user_id",
        "product_id",
=======
        'user_id',
        'product_id',
>>>>>>> 58dd1629e6f2384bcd3cfd6a287f06f2241297b0
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