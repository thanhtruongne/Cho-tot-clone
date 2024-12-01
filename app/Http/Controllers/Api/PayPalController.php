<?php

namespace App\Http\Controllers\Api;

use PayPal\Api\Payer;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use App\Http\Controllers\Controller;
use App\Models\PostingProductExpect;
use App\Models\ProductRentHouse;
use Illuminate\Http\Request;

class PayPalController extends Controller
{
    private $apiContext;

    public function __construct()
    {
        $this->apiContext = new ApiContext(
            new OAuthTokenCredential(
                config('paypal.client_id'), // Client ID
                config('paypal.secret')     // Secret
            )
        );

        $this->apiContext->setConfig([
            'mode' => config('paypal.mode'), // live hoặc sandbox
            'http.ConnectionTimeOut' => 30,
            'log.LogEnabled' => true,
            'log.FileName' => storage_path('logs/paypal.log'),
            'log.LogLevel' => 'ERROR', // DEBUG để debug
        ]);
    }

    public function createPayment(Request $request)
{
    // dd($request->all());

    // Lấy các tham số từ request
    $userId = $request->input('user_id');
    $productId = $request->input('product_id');
    $day = $request->input('day');
    $typePostingId = $request->input('type_posting_id');

    // Tạo đối tượng Payer
    $payer = new Payer();
    $payer->setPaymentMethod('paypal');

    // Tạo đối tượng Item cho sản phẩm
    $item = new Item();
    $item->setName('Example Product') // Tên sản phẩm
        ->setCurrency('USD') // Thiết lập tiền tệ là USD (PayPal không hỗ trợ VND)
        ->setQuantity(1) // Số lượng sản phẩm
        ->setPrice(200); // Giá sản phẩm (200 USD)

    // Danh sách các sản phẩm
    $itemList = new ItemList();
    $itemList->setItems([$item]);

    // Tạo đối tượng Amount cho tổng giá trị thanh toán
    $amount = new Amount();
    $amount->setCurrency('USD') // Thiết lập tiền tệ là USD
        ->setTotal(200); // Tổng giá trị thanh toán

    // Tạo đối tượng Transaction
    $transaction = new Transaction();
    $transaction->setAmount($amount)
        ->setItemList($itemList)
        ->setDescription('Payment description')
        ->setCustom(json_encode([ // Lưu thêm thông tin vào trường custom
            'user_id' => $userId,
            'product_id' => $productId,
            'day' => $day,
            'type_posting_id' => $typePostingId,
        ])); // Lưu thông tin bổ sung dưới dạng JSON

    // Đường dẫn chuyển hướng sau khi thanh toán thành công
    $redirectUrls = new RedirectUrls();
    $redirectUrls->setReturnUrl(route('paypal.success')) // URL sau khi thanh toán thành công
        ->setCancelUrl(route('paypal.cancel')); // URL sau khi thanh toán bị hủy

    // Tạo đối tượng Payment
    $payment = new Payment();
    $payment->setIntent('sale')
        ->setPayer($payer)
        ->setRedirectUrls($redirectUrls)
        ->setTransactions([$transaction]);

    try {
        // Tạo giao dịch trên PayPal
        $payment->create($this->apiContext);
        return response()->json([
            'approval_link' => $payment->getApprovalLink(),
            'message' => 'Thanh toán thành công'
        ]);
    } catch (\Exception $ex) {
        return response()->json(['error' => $ex->getMessage()], 500);
    }
}



    public function executePayment(Request $request)
    {
        // Lấy các tham số từ request
        $paymentId = $request->input('paymentId');
        $payerId = $request->input('PayerID');

        try {
            // Lấy thông tin giao dịch từ PayPal
            $payment = Payment::get($paymentId, $this->apiContext);

            // Thực hiện thanh toán
            $paymentExecution = new PaymentExecution();
            $paymentExecution->setPayerId($payerId);
            $result = $payment->execute($paymentExecution, $this->apiContext);

            // Lấy thông tin từ trường custom
            $transaction = $result->getTransactions()[0];
            $customData = json_decode($transaction->getCustom(), true); // Giải mã JSON
            dd($customData['user_id']);
            dd($customData['product_id']);
            $productId = $customData['product_id'];
            $day = $customData['day'];
            $type_posting_id = $customData['type_posting_id'];
            if ($productId) {
                $model = ProductRentHouse::findOrFail($productId);
                $model->approved = 1;
                $model->payment = 2;
                $model->type_posting_id = $type_posting_id;
                // tin thường
                if($model->type_posting_id == 1){
                    $model->day_posting_type = $day;
                    $model->approved = 1;
                    $model->payment = 2;
                    $model->time_exipred = \Carbon::now()->addDays($day);
                    $model->save();
                }


            }
            $url = env('APP_URL_FRONTEND') . "/myads" ;

            return redirect($url);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }
}
