<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductRentHouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    public function getData(Request $request){
        $search = $request->search;
        $order = $request->input('order','id');
        $type = $request->type;//dạng tin nhà ở , buôn bán , việc làm;
        if(!$type || !is_int((int)$type)){
            return response()->json(['status' => 'error','message' => 'Có lỗi xảy ra']);
        }

        $query = $this->checkNameInstance($type)::query();
        // $query->leftJoin('')
        
        // do lúc trước thầy kia chia các bảng

    }

    private function checkNameInstance($type){
        return $type == 1 ? 'ProductRentHouse' : ($type == 2 ? 'ProductElectricnic' : 'ProductJobs');
    }

}
