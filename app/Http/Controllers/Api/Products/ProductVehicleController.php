<?php

namespace App\Http\Controllers\Api\Products;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProductVehicle;
use Illuminate\Support\Facades\DB;

class ProductVehicleController extends Controller
{
    public function saveProductVehicle(Request $request)
    {
        DB::beginTransaction();
        try {
            $validatedData = $request->validate([
                'cost' => 'nullable|int',
                'title' => 'nullable|string|max:255',
                'content' => 'nullable|string',
                'category_id' => 'nullable|integer|exists:categories,id',
                'type_posting_id' => 'nullable|integer|in:1,2',
                'status' => 'nullable|integer|in:0,1',
                'province_code' => 'nullable|string|max:255',
                'district_code' => 'nullable|string|max:255',
                'ward_code' => 'nullable|string|max:255',
                'condition_used' => 'nullable|integer|in:1,2,3',
                'brand_id' => 'nullable|integer',
                'color_id' => 'nullable|integer',
                'company' => 'nullable|integer',
            ]);

            if ($request->has('images')) {
                $images = $this->UploadImages($request->file('images')); //  trả ra json encode
            }

            if($request->file) { // video
                $video = $this->uploadVideoDailyTraining($request); // trả ra file
            }

            $data =  ProductVehicle::firstOrNew(['id' => $request->id]);
            $data->fill($validatedData);
            $data->images = isset($images) && !empty($images) ? $images : null;
            $data->video = isset($video) && !empty($video) ? $video : null;
            $data->save();

            if ($data) {
                DB::commit();
                return response()->json(['message' => 'Product updated successfully', 'data' => $data]);
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
            $model = ProductVehicle::findOrFail($id);
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
        $model = ProductVehicle::findOrFail($request->id);
        $model->status = $request->status;
        $model->save();
        return response()->json(['message' => 'Cập nhật thành công', 'status' => 'success']);
    }



    public function deleteProduct($id)
    {
        $data = ProductVehicle::findOrFail($id);
        $data->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }

}
