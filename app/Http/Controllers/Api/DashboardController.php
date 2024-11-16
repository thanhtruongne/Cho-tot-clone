<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductRentHouse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    public function getData(Request $request){
        $search = $request->search;
        $order = $request->input('order','id');
        $limit = $request->input('limit',12);
        $type = $request->type;//dạng tin nhà ở , buôn bán , việc làm;
        if(!$type || !is_int((int)$type)){
            return response()->json(['status' => 'error','message' => 'Có lỗi xảy ra']);
        }
        $date = \Carbon::now();
       

        $name_model = $this->checkNameInstance($type);
        $instance = $this->handleMadeClass('Models',$name_model);

        // trả ra sớ lượng bài đã đăng của current user;
        $check_user = $this->checkUserPostData($type);
        // trường hợp này làm sau
      

        //làm tạm
        $query = $instance::query();
        $query->where('approved',1)->where('status',1);
        $query->from( $this->checkNameInstance($type,'slug').' as a');
        $query->join('user as b','b.id','=','a.user_id');
        $query->leftJoin('posting_type as c','c.id','=','a.type_posting_id');
     
        if($check_user == 0){
            $query->whereNotIn('a.user_id',auth('api')->id());
        }
        $query->where(function($subquery) use($date){
            $subquery->whereExists(function($sub_child_query) use($date){
                $sub_child_query->where('a.time_exipred','>',$date);
            });
            $subquery->orWhereNotNull('a.time_exipred');
        });
        //check theo posting type là 2
        // $query->whereHas('posting_product_expect',function($sub_query_2) use($date){
        //      $sub_query_2->whereNull('a.time_exipred');
        //      $sub_query_2->with(['posting_product_expect' => function($sub_query_3) use($date){
        //         $sub_query_3->selectRaw('DATE_FORMAT(posting_product_expect.)')    
        //         $sub_query_3->where 
        //      }]);
        // });
        $query->orderByRaw('c.id DESC, a.sort DESC');

        if($check_user == 0){
            $model =  $instance::query();
            $model->where('user_id',auth('api')->id());
            $model->unionall($query);
            $rows = $query->paginate($limit);
        }
        else {
            $rows = $query->paginate($limit);
        }
        foreach($rows as $key => $row){
            if($row->type_posting_id == 2){
                foreach($row->posting_product_expect as $index => $item){
                    $time_1 = \Carbon::createFromTime($item->val_1);
                    $time_2 = \Carbon::createFromTime($item->val_2);
                    if(!$item && !$date->gte($time_1) && !$date->lte($time_2)) {
                       $rows->forget($key);
                    }
                }
            }
            $row->cost = number_format($row->cost,2);
        }
        return response()->json(['data' => $rows,'count' => count($rows->toArray())]);
    }


    // get data address của data theo ward, district, province
    public function getAddress(Request $request) {
         
    }

    private function checkNameInstance(int $integer,string $type = 'model'){
        if($type != 'model'){
            return $integer == 1 ? 'product_rent_house' : ($integer == 2 ? 'product_electrinic' : 'product_jobs');
        }
        return $integer == 1 ? 'ProductRentHouse' : ($integer == 2 ? 'ProductElectricnic' : 'ProductJobs');
    }

    private function handleMadeClass(string $app = '',string $model = '') {
        $nameSpace = "\App\\".$app.'\\'.ucfirst($model);
        if(class_exists($nameSpace)) {
           $instance = app($nameSpace);
        }
        return $instance;
    }

    private function checkUserPostData($type){
        $slug_name = $this->checkNameInstance($type,'slug');
        $count_product = User::where(['id' => auth('api')->id(),'status' => 1])->first(['id'])->{$slug_name}->count() ?? null;
        return $count_product;
    }

}
