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
        $search = $request->input('search');
        $price_lte = $request->input('price_lte');//max price
        $price_gte = $request->input('price_gte');//min price
        $bedroom = $request->input('bedroom_id');
        $type_product = $request->input('type_product'); // dạng tin
        // $address_code = $request->input('address_code'); // --> gửi request code tới {'provinces} => code, ....}
        $location = $request->input('location');
        // $district_code = $request->input('district_code');
        // $ward_code = $request->input('ward_code');




        $limit = $request->input('limit',12);

        $type = $request->type;//dạng tin nhà ở , buôn bán , việc làm;
        if(!$type){
            return response()->json(['status' => 'error','message' => 'Có lỗi xảy ra']);
        }
        // $date = \Carbon::now();
        $name_model = $this->checkNameInstance($type);
        $instance = $this->handleMadeClass('Models',$name_model);
        //làm tạm
        $query = $instance::query();

        $query->select(['a.*','b.email','b.username','b.phone','b.address',\DB::raw("CONCAT(b.firstname,' ',b.lastname) as user_name")]);
        $query->from( $this->checkNameInstance($type,'slug').' as a');
        $query->leftJoin('users as b','b.id','=','a.user_id');
        $query->leftJoin('posting_type as c','c.id','=','a.type_posting_id');
        if($search){
            $query->where(function($subquery) use($search){
                $subquery->where('title','like',$search."%");
                $subquery->orWhere('code','like',$search."%");
                $subquery->orWhere('address','like',$search."%");
                $subquery->orWhere('email','like',$search."%");
            });
        }


        if($location) {
            $values = str_replace(['d', 'w'],'-', $location);
            $temp_location = explode('-', $values);
            foreach($temp_location as $key => $location_item){
                $key_name = $key == 0 ? 'b.province_code' : ($key == 1 ? 'b.district_code' : "b.ward_code");
                $query->where($key_name,$location_item);
            }

        }
        if($price_lte || $price_gte){
            if($price_lte && $price_gte) {
                $query->whereBetween('a.cost',[$price_gte,$price_lte]);
            } elseif($price_gte){
                $query->where('a.cost','<=',$price_gte);
            } else {
                $query->where('a.cost','>=',$price_lte);
            }
        }
        if($bedroom) {
            $query->where('bedroom_id',$bedroom);
        }
        //dạng tin đăng
        if($type_product){
            $query->where('type_product',$type_product);
        }

        $query->where('a.status',1);
        $query->orderByRaw('a.type_posting_id DESC, a.updated_at DESC');

        $rows = $query->paginate($limit);
        foreach($rows as $key => $row){
            if($row->load_btn_post) {
                $key = 'post_id_'.$row->id_.'_load_btn';
                if(cache()->has($key)){
                    $row->load_btn_post = 'Đang khóa 20 phút';
                }
            }
            $row->cost = convert_price((int)$row->cost,true);
            $row->cost_deposit = convert_price((int)$row->cost_deposit,true);

        }
        return response()->json($rows);
    }



    // public function loadDataPostCount(Request $request) {
    //     $this->validateRequest([
    //         'id' => 'required'
    //     ],$request,[
    //         'id' => 'Có lỗi xảy ra'
    //     ]);
    //     $id = $request->input('id');

    //     $model = ProductRentHouse::find($id);
    //     if(is_null($model->load_btn_post) ) {
    //       return response()->json(['message' => 'Có lỗi xảy ra','status' => 'error']);
    //     }
    //     // lưu cache theo thời gian thực thi === > cách 10 phút sau khi load
    //     $post_key = 'product_rent_house_'.$id.'_'.$model->user_id;
    //     if(cache()->has($post_key)) {
    //         return response()->json(['message' => 'Tin đăng giới hạn load tin trong 10 phút','status' => 'warning']);
    //     }
    //     cache()->put($post_key,true,\Carbon::now()->addMinutes(10));
    //     $model->decrement('load_btn_post');
    //     $model->updated_at = \Carbon::now();
    //     $model->created_at = \Carbon::now();
    //     $model->save();
    //     return response()->json(['message' => 'Thành công','status' => 'success']);
    // }

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


    public function getLocation(Request $request){
        $this->validateRequest([
            'type' => 'required|in:provinces,districts,wards',
            'code' => 'required_if:type,districts,wards'
        ],$request,[
            'type' => 'Dạng location',
            'code' => 'Mã location'
        ]);
        $type = $request->input('type'); // dạng nào thì truyền key đó vào
        $code = $request->input('code'); // code của dạng đó nếu là type là district hay wards
        $instance = $this->handleMadeClass('Models',$type);
        if(!$instance)
            return response()->json(['message' => 'Định dạng locaiton không hợp lệ','status' => 'error']);
        $query = $instance::select(['id', 'code', 'full_name']);
        if($request->search){
            $query->where('full_name', 'like', $request->search . '%');
            $query->orWhere('code', 'like', $request->search . '%');
        }
        if($type && $code) {
            if($type == 'districts'){
                $query->where('province_code', $code);
            } elseif($type== 'wards') {
                $query->where('district_code', $code);
            }
        }
        $data = $query->get();
        return response()->json($data);


    }

}
