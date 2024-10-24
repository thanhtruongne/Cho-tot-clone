<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    //
    public function createPaymentLink(Request $request)
    {
        error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = "http://localhost:8000/api/zalopay/handle-return-url";
        $vnp_TmnCode = "IT1YF6TR";
        $vnp_HashSecret = "89M6MIY98WQOMEQS0AW9LGO785JVA83Q";

        $vnp_TxnRef = $request->input('vnp_txnref');
        $vnp_Amount = $request->input('vnp_amount');
        $product_id = $request->input('product_id');
          $vnp_OrderInfo =$product_id;
        $vnp_OrderType = 'badasd';
        // $vnp_Amount = 123456789;
        $vnp_Locale = 'VN';
        $vnp_BankCode = 'NCB';
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
        //Add Params of 2.0.1 Version

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount * 100,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,

        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
            $inputData['vnp_Bill_State'] = $vnp_Bill_State;
        }

        //var_dump($inputData);
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret); //  
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        $returnData = array(
            'code' => '00',
            'message' => 'success',
            'data' => $vnp_Url
        );
        if (isset($_POST['redirect'])) {
            header('Location: ' . $vnp_Url);
            die();
        } else {
            echo json_encode($returnData);
        }
    }

    public function handleReturnUrl(Request $request)
    {
        $vnp_HashSecret = "89M6MIY98WQOMEQS0AW9LGO785JVA83Q";
        // dd($request->all());
        $vnp_SecureHash = $request->input('vnp_SecureHash');
            if($request->input('vnp_TransactionStatus') == 00)
            {
                $data = Payment::create([
                    'vnp_amount'=>$request->input('vnp_Amount'),
                    'vnp_bank_code'=>$request->input('vnp_BankCode'),
                    'vnp_bankTran_no'=>$request->input('vnp_BankTranNo'),
                    'vnp_card_type'=>$request->input('vnp_CardType'),
                    'vnp_orderInfo'=>$request->input('vnp_OrderInfo'),
                    'vnp_pay_date'=>$request->input('vnp_PayDate'),
                    'vnp_response_code'=>$request->input('vnp_ResponseCode'),
                    'vnp_tmn_code'=>$request->input('vnp_TmnCode'),
                    'vnp_transaction_no'=>$request->input('vnp_TransactionNo'),
                    'vnp_transaction_status'=>$request->input('vnp_TransactionStatus'),
                    'vnp_txn_ref'=>$request->input('vnp_TxnRef'),
                ]);

                if ($data) {
                    $queryParams = [
                        'vnp_Amount' => $request->input('vnp_Amount'),
                        'vnp_BankCode' => $request->input('vnp_BankCode'),
                        'vnp_BankTranNo' => $request->input('vnp_BankTranNo'),
                        'vnp_CardType' => $request->input('vnp_CardType'),
                        'vnp_OrderInfo' => $request->input('vnp_OrderInfo'),
                        'vnp_PayDate' => $request->input('vnp_PayDate'),
                        'vnp_ResponseCode' => $request->input('vnp_ResponseCode'),
                        'vnp_TmnCode' => $request->input('vnp_TmnCode'),
                        'vnp_TransactionNo' => $request->input('vnp_TransactionNo'),
                        'vnp_TransactionStatus' => $request->input('vnp_TransactionStatus'),
                        'vnp_TxnRef' => $request->input('vnp_TxnRef'),
                        'vnp_SecureHash' => $request->input('vnp_SecureHash')
                    ];
        
                    // return response()->json(['message' => 'Thêm sản phẩm thành công','data'=>$data]);
                    $queryString = http_build_query($queryParams);
                    $url = env('APP_URL_FRONTEND') . "/bill?" . $queryString;
                
                    // Chuyển hướng đến URL mới
                    return redirect($url);
                } else {
                    return response()->json(['data' => '401']);
                }
            }else{
                return response()->json(['data' => '402']);
            }
      
    }
}
