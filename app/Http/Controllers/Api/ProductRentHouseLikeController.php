<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductRentHouseLike;
use Illuminate\Http\Request;

class ProductRentHouseLikeController extends Controller
{
    //
    public function addProductRentHouseLike(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'user_id' => 'required|integer',
                'comment_id' => 'nullable|integer',
                'star' => 'nullable|integer|min:1|max:5',  
                'like' => 'nullable|integer|min:0',      
                'dislike' => 'nullable|integer|min:0',
            ]);
            $data = ProductRentHouseLike::create($validatedData);
            if ($data) {
                return response()->json(['message' => "Thêm sản phẩm thành công", 'data' => $data]);
            } else {
                return response()->json(['data' => "Thêm asd phẩm asdsad công"]);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors' => $e->validator->errors()
            ], 422);
        }
    }

    public function updateProductRentHouseLike(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'user_id' => 'integer',
                'comment_id' => 'nullable|integer',
                'star' => 'nullable|integer|min:1|max:5',  
                'like' => 'nullable|integer|min:0',      
                'dislike' => 'nullable|integer|min:0',
            ]);
            $data = ProductRentHouseLike::find($id);
            if (!$data) {
                return response()->json(['error' => 'Product not found'], 404);
            }
            $data->fill($validatedData);

            if ($data->save()) {
                return response()->json(
                    [
                        'message' => 'Product update successfully',
                        'data' => $data

                    ]
                );
            } else {
                return response()->json(
                    [
                        'message' => 'FAIL',
                    ]
                );
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors' => $e->validator->errors()
            ], 422);
        }
    }

    public function deleteProductRentHouseLike($id)
    {
        $data = ProductRentHouseLike::find($id);
        if (!$data) {
            return response()->json(['error' => 'Product not found'], 404);
        }
        $data->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }
}
