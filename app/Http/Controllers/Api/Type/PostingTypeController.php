<?php

namespace App\Http\Controllers\Api\Type;

use App\Http\Controllers\Controller;
use App\Models\PostingType;
use Illuminate\Http\Request;

class PostingTypeController extends Controller
{
    //
    public function addPostingType(Request $request)
    {
        try {

            $validatedData = $request->validate([
                'code' => 'required|string|max:150|unique:bathroom_type,code', // Bắt buộc, tối đa 150 ký tự, duy nhất trong bảng bathroom_type
                'name' => 'required|string', // Bắt buộc, kiểu chuỗi
                'content' => 'required|string',  // Bắt buộc, kiểu chuỗi
                'status' => 'nullable|integer|in:0,1', // Trạng thái, có thể là 0 hoặc 1, không bắt buộc
                'created_by' => 'nullable|integer', // Không bắt buộc, kiểu số nguyên
                'updated_by' => 'nullable|integer', // Không bắt buộc, kiểu số nguyên
                'order' => 'nullable|integer', // Không bắt buộc, kiểu số nguyên
                'cost' => 'required|numeric|min:0|max:99999999.99'
            ]);
            $data = PostingType::create($validatedData);
            if ($data) {
                return response()->json(['message' => 'Thêm sản phẩm thành công','data'=>$data]);

            } else {
                return response()->json(['data' => 'Thêm asd phẩm asdsad công']);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors' => $e->validator->errors()
            ], 422);
        }
    }

    public function updatePostingType(Request $request, $id)
    {
        try {

            $validatedData = $request->validate([
                'code' => 'string|max:150',
                'name' => 'string|max:150', 
                'content' => 'string', 
                'status' => 'nullable|integer|in:0,1', 
                'cost' => 'numeric', 
                'number_day' => 'nullable|integer', 
                'rule_make_by_order' => 'nullable|integer', 
            ]);
            $data = PostingType::find($id);
            if (!$data) {
                return response()->json(['error' => 'Product not found'], 404);
            }
            $data->fill($validatedData);

            if ($data->save()) {
                return response()->json(
                    [
                        'message' => 'Product update successfully',
                        'data'=>$data

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

    public function deletePostingType($id)
    {
        $data = PostingType::find($id);
        if (!$data) {
            return response()->json(['error' => 'Product not found'], 404);
        }
        $data->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }
    public function getDataPostingType()
    {
        $data = PostingType::whereNotNull('name')->get();
        return response()->json(['data' => $data]);
    }
}
