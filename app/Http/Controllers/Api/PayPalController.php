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
        $price = $request->input('price');

        // Tạo đối tượng Payer
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        // Tạo đối tượng Item cho sản phẩm
        $item = new Item();
        $item->setName('Example Product') // Tên sản phẩm
            ->setCurrency('USD') // Thiết lập tiền tệ là USD (PayPal không hỗ trợ VND)
            ->setQuantity(1) // Số lượng sản phẩm
            ->setPrice($price); // Giá sản phẩm (200 USD)

        // Danh sách các sản phẩm
        $itemList = new ItemList();
        $itemList->setItems([$item]);

        // Tạo đối tượng Amount cho tổng giá trị thanh toán
        $amount = new Amount();
        $amount->setCurrency('USD') // Thiết lập tiền tệ là USD
            ->setTotal($price); // Tổng giá trị thanh toán

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

            $productId = $customData['product_id'];
            $day = $customData['day'];
            $type_posting_id = $customData['type_posting_id'];

            if ($productId) {
                // Tìm bản ghi ProductRentHouse theo productId
                $model = ProductRentHouse::findOrFail($productId);

                // Đảm bảo rằng chúng ta thay đổi đúng giá trị và lưu vào cơ sở dữ liệu
                $model->approved = 1; // Đặt trạng thái approved là 1
                $model->payment = 2; // Cập nhật payment là 2
                $model->type_posting_id = $type_posting_id;

                // Tiến hành lưu thay đổi vào cơ sở dữ liệu
                $model->save();

                // Xử lý các trường hợp khác với type_posting_id = 1 (tin thường)
                if ($model->type_posting_id == 1) {
                    $model->day_posting_type = $day;
                    $model->time_exipred = \Carbon::now()->addDays($day);
                    $model->save();

                    // Kiểm tra tin ưu tiên (priority_post)
                    if (!empty($hours) && $request->priority_post && $model) {
                        foreach ($hours as $item) {
                            $data[] = new PostingProductExpect(['posting_data_action_id' => $item, 'cron_completed' => null]);
                        }
                        $model->posting_product_expect()->saveMany($data);
                    }
                }
            }

            // Redirect về trang myads
            $url = env('APP_URL_FRONTEND') . "/myads";
            return redirect($url);
        } catch (\Exception $ex) {
            // Trả về lỗi nếu có vấn đề xảy ra
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }
}
