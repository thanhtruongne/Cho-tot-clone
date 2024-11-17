<?php

namespace App\Http\Controllers\Api\Products;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProductElectronics;
use Illuminate\Support\Facades\DB;

class ProductElectronicController extends Controller
{
    public function getData(Request $request)
    {
        return response()->json(['data' => 123]);
    }


    public function save(Request $request) {}

    public function addProduct(Request $request)
    {
        DB::beginTransaction();

        try {
            $validatedData = $request->validate([
                'video' => 'required|string|max:255',
                'cost' => 'required|int',
                'images' => 'nullable|string',
                'title' => 'required|string|max:255',
                'content' => 'nullable|string',
                'user_id' => 'required|integer|exists:users,id',
                'code' => 'required|string|max:150',
                'category_id' => 'required|integer|exists:categories,id',
                'type_posting_id' => 'nullable|integer|in:1,2',
                'approved' => 'nullable|integer|in:0,1,2',
                'status' => 'nullable|integer|in:0,1',
                'province_code' => 'required|string|max:255',
                'district_code' => 'required|string|max:255',
                'ward_code' => 'required|string|max:255',
                'condition_used' => 'required|integer|in:1,2,3',
                'brand_id' => 'nullable|integer',
                'color_id' => 'nullable|integer',
                'capacity_id' => 'nullable|integer',
                'warrancy_policy_id' => 'nullable|integer',
                'origin_from_id' => 'nullable|integer',
                'screen_size_id' => 'nullable|integer',
                'microprocessor_id' => 'nullable|integer',
                'ram_id' => 'nullable|integer',
                'hard_drive_id' => 'nullable|integer',
                'type_hard_drive' => 'nullable|integer|in:1,2',
                'card_screen_id' => 'nullable|integer|exists:card_screens,id',
            ]);

            $data = ProductElectronics::create($validatedData);

            if ($data) {
                DB::commit();
                return response()->json(['message' => 'Product added successfully', 'data' => $data]);
            } else {
                DB::rollBack();
                return response()->json(['message' => 'Failed to add product'], 500);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json(['errors' => $e->validator->errors()], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'An error occurred'], 500);
        }
    }


    public function deleteProduct($id)
    {
        $data = ProductElectronics::find($id);
        if (!$data) {
            return response()->json(['error' => 'Product not found'], 404);
        }
        $data->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }

    public function updateProduct(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $validatedData = $request->validate([
                'video' => 'nullable|string|max:255',
                'cost' => 'nullable|int',
                'images' => 'nullable|string',
                'title' => 'nullable|string|max:255',
                'content' => 'nullable|string',
                'user_id' => 'nullable|integer|exists:users,id',
                'code' => 'nullable|string|max:150',
                'category_id' => 'nullable|integer|exists:categories,id',
                'type_posting_id' => 'nullable|integer|in:1,2',
                'approved' => 'nullable|integer|in:0,1,2',
                'status' => 'nullable|integer|in:0,1',
                'province_code' => 'nullable|string|max:255',
                'district_code' => 'nullable|string|max:255',
                'ward_code' => 'nullable|string|max:255',
                'condition_used' => 'nullable|integer|in:1,2,3',
                'brand_id' => 'nullable|integer',
                'color_id' => 'nullable|integer',
                'capacity_id' => 'nullable|integer',
                'warrancy_policy_id' => 'nullable|integer',
                'origin_from_id' => 'nullable|integer',
                'screen_size_id' => 'nullable|integer',
                'microprocessor_id' => 'nullable|integer',
                'ram_id' => 'nullable|integer',
                'hard_drive_id' => 'nullable|integer',
                'type_hard_drive' => 'nullable|integer|in:1,2',
                'card_screen_id' => 'nullable|integer|exists:card_screens,id',
            ]);

            $data = ProductElectronics::find($id);

            if (!$data) {
                DB::rollBack();
                return response()->json(['error' => 'Product not found'], 404);
            }

            $data->fill($validatedData);

            if ($data->save()) {
                DB::commit();
                return response()->json(['message' => 'Product updated successfully', 'data' => $data]);
            } else {
                DB::rollBack();
                return response()->json(['message' => 'Failed to update product'], 500);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json(['errors' => $e->validator->errors()], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'An error occurred'], 500);
        }
        }
    }
