<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ZaloPayController extends Controller
{
    //
    private $appId = "553";
    private $key1 = "9phuAOYhan4urywHTh0ndEXiV3pKHr5Q";
    private $createOrderUrl = "https://sandbox.zalopay.com.vn/v001/tpe/createorder";

    public function createOrder(Request $request)
    {
        // Tạo mã giao dịch
        $transId = uniqid();
        $appTransId = now()->format('ymd') . '_' . $transId;

        // EmbedData và Items
        $embedData = json_encode(['merchantinfo' => 'embeddata123']);
        $items = json_encode([
            [
                'itemid' => 'knb',
                'itemname' => 'kim nguyen bao',
                'itemprice' => 198400,
                'itemquantity' => 1
            ]
        ]);

        // Thời gian hiện tại (timestamp milliseconds)
        $appTime = round(microtime(true) * 1000);

        // Các tham số yêu cầu
        $params = [
            'appid' => $this->appId,
            'appuser' => 'demo',
            'apptime' => $appTime,
            'amount' => 50000,
            'apptransid' => $appTransId,
            'embeddata' => $embedData,
            'item' => $items,
            'description' => 'ZaloPay demo',
            'bankcode' => '',
        ];

        // Tạo chuỗi `data` để ký HMAC
        $data = implode('|', [
            $params['appid'],
            $params['apptransid'],
            $params['appuser'],
            $params['amount'],
            $params['apptime'],
            $params['embeddata'],
            $params['item'],
        ]);

        // Tạo chữ ký HMAC
        $params['mac'] = hash_hmac('sha256', $data, $this->key1);

        // Gửi yêu cầu đến ZaloPay API
        $response = Http::asForm()->post($this->createOrderUrl, $params);

        // Trả về kết quả
        if ($response->successful()) {
            return response()->json($response->json());
        } else {
            return response()->json([
                'error' => 'API request failed',
                'message' => $response->body()
            ], $response->status());
        }
    }
}
