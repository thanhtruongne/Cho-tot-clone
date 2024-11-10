<?php

namespace App\Http\Controllers\Api\Products;

use App\Http\Controllers\Controller;
use App\Models\ProductRentHouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductRentHouseController extends Controller
{
    public function test()
    {
        return response()->json(['data' => "them that bai"]);
    }

    public function addProductRent(Request $request)
    {
        DB::beginTransaction();
        try {
            $validatedData = $request->validate(
                [
                    'title' => 'required|string|max:255',
                    'content' => 'required|string',
                    'user_id' => 'required|exists:users,id',
                    'type_product' => 'required|in:1,2', // 1 là nhà ở, 2 là phòng trọ
                    'images' => 'required|string', // Có thể thay đổi thành 'array' nếu là mảng ảnh
                    'video' => 'nullable',
                    'type_posting_id' => 'nullable',
                    'approved' => 'nullable|in:0,1,2', // 0 là từ chối, 1 đã duyệt, 2 chờ duyệt
                    'type_rental' => 'required|in:1,2,3,4', // 1 theo ngày, 2 theo tuần, 3 theo tháng, 4 theo năm
                    'province_code' => 'required|string',
                    // 'district_code' => 'required|string',
                    'ward_code' => 'required|string',
                    'category_id' => 'required|integer',
                    'subdivision_code' => 'nullable|string',
                    'floor' => 'nullable|integer|min:0',
                    'bedroom_id' => 'nullable',
                    'bathroom_id' => 'nullable',
                    'main_door_id' => 'nullable',
                    'legal_id' => 'nullable|',
                    'condition_interior' => 'nullable|in:1,2,3,4', // 1 nội thất cao cấp, 2 đầy đủ, 3 nhà trống,ban giao tho
                    'car_alley' => 'nullable|in:0,1', // 0 không có, 1 có
                    'back_house' => 'nullable|in:0,1', // 0 không có, 1 có
                    'blooming_house' => 'nullable|in:0,1', // 0 không có, 1 có
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
                    'rule_compensation' => 'nullable|integer|min:0',
                    'type_user' => 'nullable|in:1,2', // 1 là cá nhân, 2 là môi giới
                ]
            );
            $data = ProductRentHouse::firstOrNew(['id' => $request->id]);
            $data->fill($request->all());
            $data->save();
            DB::commit();
            return response()->json(['message' => "Thêm sản phẩm thành công", 'data' => $data] );
            // if ($data) {
            //     return response()->json(['message' => "Thêm sản phẩm thành công", 'data' => $data]);
            // } else {
            //     return response()->json(['data' => "Thêm asd phẩm asdsad công"]);
            // }
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            return response()->json(['errors' => $e->validator->errors()], 422);
        }
    }


    public function updateProductRent(Request $request, $id)
    {
        try {
            $validatedData = $request->validate(
                [
                    'day_package_expirition'=>'integer',
                    'payment'=>'integer',
                    'remaining_days'=>'integer',
                    'title' => 'string|max:255',
                    'content' => 'string',
                    'user_id' => 'exists:users,id',
                    'type_product' => 'in:1,2', // 1 là nhà ở, 2 là phòng trọ
                    'images' => 'string', // Có thể thay đổi thành 'array' nếu là mảng ảnh
                    'video' => 'nullable',
                    'type_posting_id' => 'nullable',
                    'approved' => 'nullable|in:0,1,2', // 0 là từ chối, 1 đã duyệt, 2 chờ duyệt
                    'type_rental' => 'in:1,2,3,4', // 1 theo ngày, 2 theo tuần, 3 theo tháng, 4 theo năm
                    'province_code' => 'string',
                    // 'district_code' => 'string',
                    'ward_code' => 'string',
                    'category_id' => 'integer',
                    'subdivision_code' => 'nullable|string',
                    'floor' => 'nullable|integer|min:0',
                    'bedroom_id' => 'nullable',
                    'bathroom_id' => 'nullable',
                    'main_door_id' => 'nullable',
                    'legal_id' => 'nullable',
                    'condition_interior' => 'nullable|in:1,2,3,4', // 1 nội thất cao cấp, 2 đầy đủ, 3 nhà trống,ban giao tho
                    'car_alley' => 'nullable|in:0,1', // 0 không có, 1 có
                    'back_house' => 'nullable|in:0,1', // 0 không có, 1 có
                    'blooming_house' => 'nullable|in:0,1', // 0 không có, 1 có
                    'not_completed_yet' => 'nullable|in:0,1',
                    'land_not_changed_yet' => 'nullable|in:0,1',
                    'planning_or_road' => 'nullable|in:0,1',
                    'diff_situation' => 'nullable|in:0,1',
                    'land_area' => 'numeric|min:0',
                    'usable_area' => 'nullable|numeric|min:0',
                    'horizontal' => 'nullable|numeric|min:0',
                    'length' => 'nullable|numeric|min:0',
                    'cost' => 'numeric|min:0',
                    'cost_deposit' => 'nullable|numeric|min:0',
                    'rule_compensation' => 'nullable|integer|min:0',
                    'type_user' => 'nullable|in:1,2', // 1 là cá nhân, 2 là môi giới
                ]
            );
            $data = DB::transaction(function () use ($validatedData, $id) {
                $product = ProductRentHouse::findOrFail($id);
                $product->update($validatedData);
                return $product;
            });

            return response()->json([
                'message' => 'Product updated successfully',
                'data' => $data
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->validator->errors()], 422);
        }
    }
    public function deleteProductRent($id)
    {
        $data = ProductRentHouse::find($id);
        if (!$data) {
            return response()->json(['error' => 'Product not found'], 404);
        }
        $data->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }
    public function getDataProductRent()
    {
        $data = DB::table('product_rent_house')->get();
        return response()->json(['data' => $data]);
    }
    public function getDataProductRentByUserId($id)
    {
        $data = ProductRentHouse::where('user_id', $id)->get();
        return response()->json(['data' => $data]);
    }
}
