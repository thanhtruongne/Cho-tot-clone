<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Api\Products\ProductRentHouseController;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\PostingProductExpect;
use App\Models\ProductRentHouse;
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
        $vnp_Inv_Customer =$request->input('user_id');
        $vnp_TxnRef = $request->input('vnp_txnref');
        $vnp_Amount = $request->input('vnp_amount');
        
        // $username = $request->input('username');
        // $product_id = $request->input('product_id');
        // $name_product = $request->input('name_product');

        // $vnp_OrderInfo = 'Thanh toán đơn hàng-' . 'Người chuyển: ' . $username . '-ID sản phẩm: ' . $product_id. '-Tên sản phẩm: '.$name_product . 'User_id: ' . $vnp_Inv_Customer;
        $user_id = $request->input('user_id');
        $product_id = $request->input('product_id');
        $type_posting_id = $request->input('type_posting_id');
        $day =  $request->input('day') ;
        $vnp_OrderInfo =  $user_id . ' - ' . $product_id . ' - ' . $day . ' - ' . $type_posting_id;
        $vnp_OrderType = $request->time_frame; //  dạng theo khung giờ
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
        // convert lại sai r , lúc khi bấm đăng tin đã tạo data không phải khi thanh toán
        $vnp_HashSecret = "89M6MIY98WQOMEQS0AW9LGO785JVA83Q";
        // dd($request->all());
        $vnp_SecureHash = $request->input('vnp_SecureHash');
        if ($request->input('vnp_TransactionStatus') == 00) {
            $vnp_OrderInfo = $request->input('vnp_OrderInfo');
            $orderInfoParts = explode('-', $vnp_OrderInfo);

            $userId = isset($orderInfoParts[0]) ? $orderInfoParts[0] : null;
            $productId = isset($orderInfoParts[1]) ? $orderInfoParts[1] : null;
            $day = isset($orderInfoParts[2]) ? $orderInfoParts[2] : null;
            $type_posting_id = isset($orderInfoParts[3]) ? $orderInfoParts[3] : null;
            // $data = Payment::create([
            //     'user_id' => $userId,
            //     'product_id' => $productId,
            //     'vnp_amount' => $request->input('vnp_Amount'),
            //     'vnp_bank_code' => $request->input('vnp_BankCode'),
            //     'vnp_bankTran_no' => $request->input('vnp_BankTranNo'),
            //     'vnp_card_type' => $request->input('vnp_CardType'),
            //     'vnp_orderInfo' => $request->input('vnp_OrderInfo'),
            //     'vnp_pay_date' => $request->input('vnp_PayDate'),
            //     'vnp_response_code' => $request->input('vnp_ResponseCode'),
            //     'vnp_tmn_code' => $request->input('vnp_TmnCode'),
            //     'vnp_transaction_no' => $request->input('vnp_TransactionNo'),
            //     'vnp_transaction_status' => $request->input('vnp_TransactionStatus'),
            //     'vnp_txn_ref' => $request->input('vnp_TxnRef'),
            // ]);

            // if ($data) {
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
    
                if ($productId) {
                    // $request->merge(['approved' => 1]);
                    // $request->merge(['payment' => 2]);
                    // $request->merge(['remaining_days' => $day]);
                    // $request->merge(['day_package_expirition' => $day]);
                    // $request->merge(['type_posting_id' => $type_posting_id]);
                    // $productRentHouseController = new ProductRentHouseController();
                    // $productRentHouseController->updateProductRent($request,$productId);
                    $model = ProductRentHouse::find($productId);
                    $model->approved = 1;
                    $model->payment = 2;
                    // $model->fill()
                    // tin thường
                    if($model->type_posting_id == 1){
                       $model->day_posting_type = $day;
                       $model->time_exipred = \Carbon::now()->addDays($day);
                    }
                    else {// dạng tin ưu tiên chọn các dạng khung giờ 8 - 10h có data trong seeder sẵn
                      $time_frame = $request->time_frame ? explode(',',$request->time_frame) : [];
                      if(isset($time_frame) && count($time_frame) > 0){
                         foreach($time_frame as $item){
                            $data[] = new PostingProductExpect(['posting_data_action_id' =>$item , 'cron_completed' => null ]);
                         }
                         $model->posting_product_expect()->saveMany($data);
                      
                      }
                    }
                    $model->save();

                    
                }
                // $queryString = http_build_query($queryParams);
                // $url = env('APP_URL_FRONTEND') . "/myads?" . $queryString;
                $url = env('APP_URL_FRONTEND') . "/myads" ;

                return redirect($url);
            // } 
            // else {
            //     return response()->json(['data' => '401']);
            // }
        } else {
            return response()->json(['data' => '402']);
        }
    }
}
