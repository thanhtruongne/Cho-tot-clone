<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductRentHouseComment;
use Illuminate\Http\Request;

class ProductRentHouseCommentController extends Controller
{
    //
    public function addProductRentHouseComment(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'user_id' => 'required|integer|exists:users,id',
                'content' => 'required|string|max:5000',         
                'failed' => 'nullable|integer|min:0',           
            ]);
            
            $data = ProductRentHouseComment::create($validatedData);
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

    public function updateProductRentHouseComment(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'user_id' => 'integer|exists:users,id',
                'content' => 'string|max:5000',         
                'failed' => 'nullable|integer|min:0',           
            ]);
            $data = ProductRentHouseComment::find($id);
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

    public function deleteProductRentHouseComment($id)
    {
        $data = ProductRentHouseComment::find($id);
        if (!$data) {
            return response()->json(['error' => 'Product not found'], 404);
        }
        $data->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }
}
