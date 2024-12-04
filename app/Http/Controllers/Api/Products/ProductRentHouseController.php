<?php

namespace App\Http\Controllers\Api\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRentHouseRequest;
use App\Models\ProductRentHouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductRentHouseController extends Controller
{
    public function test()
    {
        return response()->json(['data' => "thêm thất bại"]);
    }

    public function managePostings()
    {
        $data = ProductRentHouse::paginate(2);
        return view('pages.products.productHouse.managePostings', compact('data'));
    }

    public function deletemanagePostings($id)
    {
        $data = ProductRentHouse::find($id);
        $data->delete();
        return redirect()->back()->with('success','');
    }

    public function addProductRent(Request $request)
    {
    
        DB::beginTransaction();
        try {
            $this->validateRequest([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'user_id' => 'required|exists:users,id',
                'code' => 'required|string|max:150',
                'type_product' => 'required|in:1,2',
                'images' => 'required|string',
                'video' => 'nullable|string',
                'type_posting_id' => 'nullable|integer',
                'approved' => 'nullable|in:0,1,2',
                'type_rental' => 'required|in:1,2,3,4',
                'province_code' => 'required|string',
                'ward_code' => 'required|string',
                'category_id' => 'required|integer',
                'subdivision_code' => 'nullable|string',
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
                // 'cost' => 'required|numeric|min:0',
                'cost_deposit' => 'nullable|numeric|min:0',
                'rule_compensation' => 'nullable|integer|min:0',
            ],$request,ProductRentHouse::getAttributeName());
    
    
    
            $data = ProductRentHouse::create($request->all());
            // $data->fill($request->all());
            // $data->save();
            // $data = \DB::table('product_rent_house')->insert($request->all());
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
                'title' => 'string|max:255',
                'content' => 'string',
                'user_id' => 'exists:users,id',
                'code' => 'string|max:150',
                'type_product' => 'in:1,2',
                'images' => 'string',
                'video' => 'nullable|string',
                'type_posting_id' => 'nullable|integer',
                'approved' => 'nullable|in:0,1,2',
                'type_rental' => 'in:1,2,3,4',
                'province_code' => 'string',
                'ward_code' => 'string',
                'category_id' => 'integer',
                'subdivision_code' => 'nullable|string',
                'floor' => 'nullable|integer|min:0',
                'bedroom_id' => 'nullable|integer',
                'bathroom_id' => 'nullable|integer',
                'main_door_id' => 'nullable|integer',
                'legal_id' => 'nullable|integer',
                'condition_interior' => 'nullable|in:1,2,3,4',
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
                'rule_compensation' => 'nullable|integer|min:0',
            ]);

            $data = ProductRentHouse::find($id);
            if (!$data) {
                DB::rollBack();
                return response()->json(['error' => 'Product not found'], 404);
            }

            $data->fill($validatedData);
            $data->save();

            DB::commit();
            return response()->json(['message' => 'Product updated successfully', 'data' => $data]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json(['errors' => $e->validator->errors()], 422);
        }
    }

    public function getDataProductRent()
    {
        $data = ProductRentHouse::all();
        return response()->json(['data' => $data]);
    }

    public function getDataProductRentByUserId($id)
    {
        $data = ProductRentHouse::where('user_id', $id)->get();
        return response()->json(['data' => $data]);
    }

    public function changeStatusPostData(Request $request){
        $this->validateRequest([
            'id' => 'required'
        ],$request,[
            'id' => 'Có lỗi xảy ra'
        ]);
        $model = ProductRentHouse::find($request->id);
        if(!$model)
            return response()->json(['message' => 'Không tìm thấy tin', 'status' => 'error']);
        $model->status = 0;
        $model->save();
        return response()->json(['message' => 'Cập nhật thành công', 'status' => 'success']);
    }

}
