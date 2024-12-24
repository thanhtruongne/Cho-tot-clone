<?php

namespace App\Http\Controllers\Api;

use App\Helpers\VideoStream;
use App\Http\Controllers\Controller;
use App\Models\ProductRentHouse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
class DashboardController extends Controller
{

    private $district_query = 'd';

    private $ward_query = 'w';

    public function index(){
        return view('pages.Dashboard.index');
    }

    public function getData(Request $request){
        $search = $request->input('search');
        $price_lte = $request->input('price_lte');//max price
        $price_gte = $request->input('price_gte');//min price
        $bedroom = $request->input('bedroom_id');
        $type_product = $request->input('type_product'); // dạng tin
        $location = $request->input('location');

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
            $values = str_replace([$this->district_query, $this->ward_query],'-', $location);
            $temp_location = explode('-', $values);
            foreach($temp_location as $key => $location_item){
                $key_name = $key == 0 ? 'a.province_code' : ($key == 1 ? 'a.district_code' : "a.ward_code");
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
            $query->where('a.type_product',$type_product);
        }

        $query->where('a.status',1);
        $query->orderByRaw('a.type_posting_id DESC, a.updated_at DESC');

        $rows = $query->paginate($limit);
        foreach($rows as $key => $row){
            $row->created_at2 = \Carbon::parse($row->created_at)->diffForHumans();
            $row->cost = convert_price((int)$row->cost,true);
            $row->cost_deposit = convert_price((int)$row->cost_deposit,true);

        }
        return response()->json($rows);
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
        $code = $request->input('code','none'); // code của dạng đó nếu là type là district hay wards
        $key = 'location_'.$type.'_child_'.$code;
        $temp = Cache::tag('location')->rememberForever($key,function() use($type,$code,$request){
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
            return $data;
        });

        
        return response()->json($temp);


    }

    public function videoStreaming($file){
        $file = decrypt_array($file);
        if (!isset($file['path'])) {
            return response()->json(['message' => 'Không tìm thấy file truyền','status' => false]);
        }

        if (!file_exists($file['path'])) {
            return response()->json(['message' => 'Không tồn tại file truyền','status' => false]);
        }
        $stream = new VideoStream($file['path']);
        return $stream->start();
    }

    public function getProductForUserID(Request $request){
        
    }


    public function getAllDataProductHome(Request $request){

    }

}
