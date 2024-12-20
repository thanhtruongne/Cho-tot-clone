<?php

namespace App\Http\Controllers\Api\Products;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProductElectronics;
use Illuminate\Support\Facades\DB;

class ProductElectronicController extends Controller
{
    public function saveProductElectrics(Request $request)
    {
        DB::beginTransaction();

        try {
            $validatedData = $request->validate([
                'cost' => 'required|int',
                'title' => 'required|string|max:255',
                'content' => 'nullable|string',
                'category_id' => 'required|integer|exists:categories,id',
                'type_posting_id' => 'nullable|integer|in:1,2',
                'status' => 'nullable|integer|in:0,1',
                'province_code' => 'required|string|max:255',
                'district_code' => 'required|string|max:255',
                'ward_code' => 'required|string|max:255',
                'condition_used' => 'required',
                'brand_id' => 'nullable|integer',
                'color_id' => 'nullable|integer',
                'origin_from_id' => 'nullable|integer',
                'ram_id' => 'required'
            ]);

            if($request->has('images')){ //images
                $images = $this->UploadImages($request->file('images')); //  trả ra json encode
            }
            if($request->file) { // video
               $video = $this->uploadVideoDailyTraining($request); // trả ra file
            }

            $data = ProductElectronics::firstOrNew(['id' => $request->id]);
            $data->fill($validatedData);
            $data->user_id = auth('api')->id();
            $data->images = isset($images) && !empty($images) ? $images : null;
            $data->video =  isset($video) && !empty($video) ? $video : null;
            if ($data->save()) {
                DB::commit();
                return response()->json(['message' => 'Tạo tin đăng thành công', 'data' => $data]);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json(['errors' => $e->validator->errors()], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'An error occurred'], 500);
        }
    }

    public function getDetailProductElectricById($id){

        try{
            $model = ProductElectronics::findOrFail($id);
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
        $model = ProductElectronics::findOrFail($request->id);
        $model->status = $request->status;
        $model->save();
        return response()->json(['message' => 'Cập nhật thành công', 'status' => 'success']);
    }

    public function deleteProduct($id)
    {
        $data = ProductElectronics::findOrFail($id);
        $data->delete();
        return response()->json(['message' => 'Xóa thành công','status' => true]);
    }
    
}
