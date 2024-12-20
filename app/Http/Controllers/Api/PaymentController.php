<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\PostingProductExpect;
use App\Models\ProductRentHouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{

    protected $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";

    protected $vnp_Returnurl ="http://localhost:8000/api/vnpay/handle-return-url";

    protected $vnp_TmnCode = "IT1YF6TR";

    protected  $vnp_HashSecret = "89M6MIY98WQOMEQS0AW9LGO785JVA83Q";

    private $product_type_post = 1;

    
    
    public function setProductType($type) {
        return $this->product_type_post = $type;
    }

    public function createPaymentLink(Request $request)
    {
        error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        $vnp_TxnRef = $request->input('vnp_txnref');
        $vnp_Amount = $request->input('vnp_amount');
        $user_id = $request->input('user_id', '');
        $product_id = $request->input('product_id', '');
        $type_posting_id = $request->input('type_posting_id', '');
        $type = $request->input('type', $this->product_type_post);
        $load_key_post = $request->input('load_key_post', '');
        $day = $request->input('day', $day = now()->format('Y-m-d'));
        $vnp_OrderInfo =  $user_id . ' _ ' . $product_id . ' _ ' . $day . ' _ ' . $type_posting_id . ' _ ' . $load_key_post.'_'.$type;
        $vnp_Locale = 'VN';
        $vnp_BankCode = 'NCB';
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
      
        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $this->vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount * 100,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => 'other',
            "vnp_ReturnUrl" => $this->vnp_Returnurl,
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

                        $vnp_Url = $this->vnp_Url . "?" . $query;
                        if (isset($this->vnp_HashSecret)) {
                            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $this->vnp_HashSecret); //
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
        if ($request->input('vnp_TransactionStatus') == 00) {
            $vnp_OrderInfo = $request->input('vnp_OrderInfo');
            $orderInfoParts = explode('_', $vnp_OrderInfo);
            $productId = isset($orderInfoParts[1]) ? $orderInfoParts[1] : null;
            $day = isset($orderInfoParts[2]) ? $orderInfoParts[2] : null;
            $type_posting_id = isset($orderInfoParts[3]) ? $orderInfoParts[3] : null;
            $load_key_post = isset($orderInfoParts[4]) ? $orderInfoParts[4] : null;
            $type = isset($orderInfoParts[5]) ? $orderInfoParts[5] : null;
            $this->setProductType($type);
            $modelClass = $this->checkNameInstance($this->product_type_post);
            $instance = $this->handleMadeClass($modelClass);
            if ($productId) {
                $model = $instance::findOrFail($productId);
                $model->approved = 1;
                $model->payment = 2;
                $model->type_posting_id = $type_posting_id;
                $model->day_posting_type = null;
                // theo dạng load tin lưu số lần
                $model->load_btn_post = $load_key_post;
                $model->time_exipred = null;
                $model->save();
            }
            $url = env('APP_URL_FRONTEND') . "/myads" ;
            return redirect($url);
        } else {
            return response()->json(['data' => '402']);
        }
    }

    public function setReturnURL(string $url) {
        $this->vnp_Returnurl = $url;
    }

    public function handleLoadVertifyPost(Request $request){

        $this->setReturnURL(route('fe.load.subpayment'));
        $vnp_Locale = 'VN';
        $vnp_BankCode = 'NCB';
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
        $vnp_TxnRef = $request->input('vnp_txnref');
        $vnp_Amount = $request->input('vnp_amount');
        $load_key_post = $request->input('load_key_post', '');
        $type = $request->input('type', $this->product_type_post);
        $vnp_OrderInfo = $request->id .'_'.$load_key_post.'_'.$type;
        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $this->vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount * 100,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => 'other',
            "vnp_ReturnUrl" => $this->vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,

        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
            $inputData['vnp_Bill_State'] = $vnp_Bill_State;
        }
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

        $vnp_Url = $this->vnp_Url . "?" . $query;
        if (isset($this->vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $this->vnp_HashSecret); //
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

    public function handleUrlLoadPost(Request $request){
        if ($request->input('vnp_TransactionStatus') == 00) {
            $vnp_OrderInfo = $request->input('vnp_OrderInfo');
            $orderInfoParts = explode('_', $vnp_OrderInfo);
            $productId = isset($orderInfoParts[0]) ? $orderInfoParts[0] : null;
            $load_key_post = isset($orderInfoParts[1]) ? $orderInfoParts[1] : null; 
            $type = isset($orderInfoParts[2]) ? $orderInfoParts[2] : null;
            $this->setProductType($type);
            $modelClass = $this->checkNameInstance($this->product_type_post);
            $instance = $this->handleMadeClass($modelClass); 
            if ($productId) {
                $model = $instance::findOrFail($productId);
                $model->load_btn_post = $load_key_post;
                $model->time_exipred = null;
                $model->save();           
            }
            $url = env('APP_URL_FRONTEND') . "/myads" ;
            return redirect($url);
        } else {
            return response()->json(['data' => '402']);
        }
    }

}
