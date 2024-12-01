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
        // $check_user = $this->checkUserPostData($type);
        // trường hợp này làm sau


        //làm tạm
        $query = $instance::query();

        $query->select(['a.*','b.email','b.username','b.phone','b.address',\DB::raw("CONCAT(b.firstname,' ',b.lastname) as user_name")]);
        $query->from( $this->checkNameInstance($type,'slug').' as a');
        $query->leftJoin('users as b','b.id','=','a.user_id');
        $query->leftJoin('posting_type as c','c.id','=','a.type_posting_id');
        $query->whereExists(function($subquery) use($date){
            $subquery->where('a.time_exipred','>=',$date);
            $subquery->orWhereNotNull('a.time_exipred'); // set tạm
        });
        $query->where('a.approved',1);
        $query->where('a.status',1);
        $query->orderByRaw('a.updated_at ASC, a.type_posting_id DESC');


        $rows = $query->paginate($limit);
        foreach($rows as $key => $row){
//             if($row->type_posting_id == 1){
//                 foreach($row->posting_product_expect as $index => $item){
//                     $time_1 = \Carbon::createFromTime($item->val_1);
//                     $time_2 = \Carbon::createFromTime($item->val_2);
//                     if($item && $date->gte($time_1) && !date->lte($time_2)) {
//                         $rows->updated_at = \Carbon::now();
//                     }
//                 }
//             }
            $row->cost = number_format($row->cost,2);
        }
        return response()->json($rows);
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
        $count_product = User::where(['id' => auth('api')->id(),'status' => 1])->first()->{$slug_name}->count() ?? null;
        return $count_product;
    }

}
