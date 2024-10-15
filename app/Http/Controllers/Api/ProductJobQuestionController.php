<?php

namespace App\Http\Controllers;

use App\Models\ProductJobQuestion;
use Illuminate\Http\Request;

class ProductJobQuestionController extends Controller
{
    //
    public function addProducJobQuestion(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'product_id' => 'required',
                'user_id' => 'required',
                'order' => 'nullable|integer|min:0',
                'content' => 'required|string|max:500',
            ]);

            $data = ProductJobQuestion::create($validatedData);
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

    public function updateProducJobQuestion(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'product_id' => 'integer',
                'user_id' => 'integer',
                'order' => 'nullable|integer|min:0',
                'content' => 'string|max:500',
            ]);
            $data = ProductJobQuestion::find($id);
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

    public function deleteProducJobQuestion($id)
    {
        $data = ProductJobQuestion::find($id);
        if (!$data) {
            return response()->json(['error' => 'Product not found'], 404);
        }
        $data->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }
}
