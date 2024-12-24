<?php

namespace App\Http\Controllers\Api\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRentHouseRequest;
use App\Models\ProductRentHouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Redis;


interface InterfaceProductRentController {
    public function addProductRent(Request $request);

    public function updateProductRent(Request $request, $id);

    public function getDataProductRentGetUserId($id);

    public function changeStatusPostData(Request $request);

    public function loadDataBtnPost(Request $request);
}

class ProductRentHouseController extends Controller implements InterfaceProductRentController
{

    public function addProductRent(Request $request)
    {
        DB::beginTransaction();
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'type_posting_id' => 'nullable|integer',
                'province_code' => 'required|string',
                'ward_code' => 'required|string',
                'category_id' => 'required|integer',
                'floor' => 'nullable|integer|min:0',
                'bedroom_id' => 'nullable|integer',
                'bathroom_id' => 'nullable|integer',
                'main_door_id' => 'nullable|integer',
                'legal_id' => 'nullable|integer',
                'condition_interior' => 'nullable|in:1,2,3',
                'car_alley' => 'nullable|in:0,1',
                'back_house' => 'nullable|in:0,1',
                'blooming_house' => 'nullable|in:0,1',
                'not_completed_yet' => 'nullable|in:0,1',
                'land_not_changed_yet' => 'nullable|in:0,1',
                'planning_or_road' => 'nullable|in:0,1',
                'diff_situation' => 'nullable|in:0,1',
                'land_area' => 'required|numeric|min:0',
                'usable_area' => 'nullable|numeric|min:0',
                'horizontal' => 'nullable|numeric|min:0',
                'length' => 'nullable|numeric|min:0',
                'cost' => 'required|numeric|min:0',
                'cost_deposit' => 'nullable|numeric|min:0',
                'district_code' => 'required|string',
            ]);

            if($request->has('images')){ //images
                $images = $this->UploadImages($request->file('images')); //  trả ra json encode
            }
            if($request->file) { // video
               $video = $this->uploadVideoDailyTraining($request); // trả ra file
            }

            $data = new ProductRentHouse();
            $data->fill($validatedData);
            $data->user_id = auth('api')->id();
            $data->type_product = 1;
            $data->images = isset($images) && !empty($images) ? $images : null;
            $data->video =  isset($video) && !empty($video) ? $video : null;
            $data->save();

            DB::commit();
            return response()->json(['message' => 'Product added successfully', 'data' => $data]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json(['errors' => $e->validator->errors()], 422);
        }
    }

    public function updateProductRent(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'type_posting_id' => 'nullable|integer',
                'province_code' => 'required|string',
                'ward_code' => 'required|string',
                'category_id' => 'required|integer',
                'floor' => 'nullable|integer|min:0',
                'bedroom_id' => 'nullable|integer',
                'bathroom_id' => 'nullable|integer',
                'main_door_id' => 'nullable|integer',
                'legal_id' => 'nullable|integer',
                'condition_interior' => 'nullable|in:1,2,3',
                'car_alley' => 'nullable|in:0,1',
                'back_house' => 'nullable|in:0,1',
                'blooming_house' => 'nullable|in:0,1',
                'not_completed_yet' => 'nullable|in:0,1',
                'land_not_changed_yet' => 'nullable|in:0,1',
                'planning_or_road' => 'nullable|in:0,1',
                'diff_situation' => 'nullable|in:0,1',
                'land_area' => 'required|numeric|min:0',
                'usable_area' => 'nullable|numeric|min:0',
                'horizontal' => 'nullable|numeric|min:0',
                'length' => 'nullable|numeric|min:0',
                'cost' => 'required|numeric|min:0',
                'cost_deposit' => 'nullable|numeric|min:0',
                'district_code' => 'required|string',
            ]);

            $data = ProductRentHouse::find($id);
            if (!$data) {
                return response()->json(['error' => 'Product not found'], 404);
            }

            if ($request->has('images')) {
                $images = $this->UploadImages($request->file('images')); //  trả ra json encode
            }

            if($request->file) { // video
                $video = $this->uploadVideoDailyTraining($request); // trả ra file
            }

            $data->fill($validatedData);
            $data->images = isset($images) && !empty($images) ? $images : null;
            $data->video = isset($video) && !empty($video) ? $video : null;
            $data->save();

            DB::commit();
            return response()->json(['message' => 'Product updated successfully', 'status' => true]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json(['errors' => $e->getMessage()], 422);
        }
    }

    public function getDataProductRentGetUserId($id)
    {
        $rows = ProductRentHouse::where('user_id', $id)->with(['province', 'district', 'ward'])->get();
        if ($rows) {
            foreach ($rows as $row) {
                $row->cost = convert_price((int)$row->cost, true);
                $row->cost_deposit = convert_price((int)$row->cost_deposit, true);
                $row->created_at2 = \Carbon::parse($row->created_at)->diffForHumans();
            }
        }
        return response()->json(['data' => $rows]);
    }


    public function getDetailProductRentById($id){

        try{
            $model = ProductRentHouse::findOrFail($id);
            $model->loadMissing(['province', 'ward', 'district','user']);
            $model->cost = convert_price((int)$model->cost, true);
            $model->cost_deposit = convert_price((int)$model->cost_deposit, true);
            $model->created_at2 = \Carbon::parse($model->created_at)->diffForHumans();
            $model->linkPlay = $model->getLinkPlay();

            return response()->json(['data' => $model]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->getMessage()], 422);
        }
    }



    public function changeStatusPostData(Request $request){
        $this->validateRequest([
            'id' => 'required',
            'status' => 'required|numeric|in:0,1'
        ], $request, [
            'id' => 'Đường dẫn dữ liệu',
            'status' => 'Trạng thái'
        ]);
        $model = ProductRentHouse::find($request->id);
        if (!$model)
            return response()->json(['message' => 'Không tìm thấy tin', 'status' => 'error']);
        $model->status = $request->status;
        $model->save();
        return response()->json(['message' => 'Cập nhật thành công', 'status' => 'success']);
    }

    public function loadDataBtnPost(Request $request)
    {
        $this->validateRequest([
            'id' => 'required'
        ], $request, [
            'id' => 'Dữ liệu bài đăng'
        ]);

        $model = ProductRentHouse::find($request->id);

        $key = 'post_id_'.$model->id_.'_load_btn';
        if(cache()->has($key)){
            $val = explode("_",cache()->get($key));
            $time =  \Carbon::createFromTimestamp($val[3])->diffForHumans();
            return response()->json(['message' => trans('general.time_exists_post').$time, 'status' => 'error']);
        }
        if(!$model->load_btn_post){
            return response()->json(['message' => 'Có lỗi xảy ra', 'status' => 'error']);
        }
        $model->created_at = \Carbon::now();
        $model->decrement('load_btn_post');
        $model->save();

        $time_exp = \Carbon::now()->addMinutes(20);
        $key = 'post_id_' . $model->id_ . '_load_btn';
        $value= 'id_'.$model->id.'_time_'.$time_exp->timestamp;
        if (!cache()->has($key)) {
            cache()->put($key, $value, $time_exp);
        }
        \Artisan::call('modelCache:clear', ['--model' => ProductRentHouse::class]);

        return response()->json(['message' => 'Cập nhật thành công', 'status' => 'success']);
    }

    public function index()
    {
        return view('pages.products.productHouse.index');
    }

    public function getStatistics(Request $request)
    {
        // Lấy kiểu thống kê (ngày, tháng, năm) từ request, mặc định là 'day'
        $type = $request->input('type', 'day');

        // Nếu chọn thống kê theo ngày
        if ($type === 'day') {
            $statistics = ProductRentHouse::selectRaw('
                SUM(cost) as total_cost,
                DATE(created_at) as date
            ')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();
        }
        // Nếu chọn thống kê theo tháng
        elseif ($type === 'month') {
            $statistics = ProductRentHouse::selectRaw('
                SUM(cost) as total_cost,
                MONTH(created_at) as month,
                YEAR(created_at) as year
            ')
            ->groupBy('year', 'month')  // Lưu ý: Group theo năm rồi đến tháng
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();
        }
        // Nếu chọn thống kê theo năm
        else {
            $statistics = ProductRentHouse::selectRaw('
                SUM(cost) as total_cost,
                YEAR(created_at) as year
            ')
            ->groupBy('year')
            ->orderBy('year', 'asc')
            ->get();
        }

        // Trả về dữ liệu dưới dạng JSON
        return response()->json($statistics);
    }

    public function managePostings()
    {
        return view('pages.products.productHouse.managePostings');
    }
    public function getProductData()
    {
        $data = ProductRentHouse::all();
        return response()->json([
            'data' => $data
        ]);
    }


}
