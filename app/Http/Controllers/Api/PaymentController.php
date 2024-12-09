<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\PostingProductExpect;
use App\Models\ProductRentHouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function index()
    {
        return '123123';
    }
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

        // $username = $request->input('username');
        // $product_id = $request->input('product_id');
        // $name_product = $request->input('name_product');

        // $vnp_OrderInfo = 'Thanh toán đơn hàng-' . 'Người chuyển: ' . $username . '-ID sản phẩm: ' . $product_id. '-Tên sản phẩm: '.$name_product . 'User_id: ' . $vnp_Inv_Customer;
        $user_id = $request->input('user_id', '');
        $product_id = $request->input('product_id', '');
        $type_posting_id = $request->input('type_posting_id', '');
        $load_key_post = $request->input('load_key_post', '');
        $day = $request->input('day', $day = now()->format('Y-m-d'));
        $vnp_OrderInfo =  $user_id . ' _ ' . $product_id . ' _ ' . $day . ' _ ' . $type_posting_id . ' _ ' . $load_key_post;
        // $vnp_OrderType = $request->hours; //  dạng theo khung giờ
        // $vnp_Amount = 123456789;
        $vnp_Locale = 'VN';
        $vnp_BankCode = 'NCB';
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

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
            "vnp_OrderType" => 'other',
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
        $vnp_SecureHash = $request->input('vnp_SecureHash');
        if ($request->input('vnp_TransactionStatus') == 00) {
            $vnp_OrderInfo = $request->input('vnp_OrderInfo');
            $orderInfoParts = explode('-', $vnp_OrderInfo);
            $productId = isset($orderInfoParts[1]) ? $orderInfoParts[1] : null;
            $day = isset($orderInfoParts[2]) ? $orderInfoParts[2] : null;
            $type_posting_id = isset($orderInfoParts[3]) ? $orderInfoParts[3] : null;
            $load_key_post = isset($orderInfoParts[4]) ? $orderInfoParts[4] : null;
            $hours = $request->hours && !is_array($request->hours) ? explode(',',$request->hours) : [];
            // $count_post = $request->vnp_OrderType;
            if ($productId) {
                $model = ProductRentHouse::findOrFail($productId);
                $model->approved = 1;
                $model->payment = 2;
                $model->type_posting_id = $type_posting_id;
                $model->day_posting_type = $day;
                // theo dạng load tin lưu số lần
                $model->load_btn_post = $load_key_post;
                $model->time_exipred = \Carbon::now()->addDays($day);
                $model->save();
                // tin thường
                if($model->type_posting_id == 1){
                    // tin ưu tiên
                    if(!empty($hours) && $request->priority_post && $model) {
                        foreach($hours as $item){
                            $data[] = new PostingProductExpect(['posting_data_action_id' =>$item , 'cron_completed' => null ]);
                        }
                        $model->posting_product_expect()->saveMany($data);
                    }
                }
            }
            $url = env('APP_URL_FRONTEND') . "/myads" ;
            return redirect($url);
        } else {
            return response()->json(['data' => '402']);
        }
    }
}
