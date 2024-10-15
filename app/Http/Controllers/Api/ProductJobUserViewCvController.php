<?php

namespace App\Http\Controllers;

use App\Models\ProductJobUserViewCv;
use Illuminate\Http\Request;

class ProductJobUserViewCvController extends Controller
{
    //
    public function addProducJobUserViewCv(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'product_id' => 'required', 
                'user_id' => 'required', 
                'order' => 'nullable|integer|min:0', 
                'name' => 'required|string|max:255', 
                'year_born_id' => 'nullable', 
                'gender' => 'nullable|in:1,2,3', 
                'phone' => 'required|string|max:15', 
                'edu_level_id' => 'nullable', 
                'certificate_id' => 'nullable', 
                'portrait_photo' => 'nullable|url', 
                'content' => 'nullable|string|max:1000', 
            ]);

            $data = ProductJobUserViewCv::create($validatedData);
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

    public function updateProducJobUserViewCv(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'product_id' => 'integer', 
                'user_id' => 'integer', 
                'order' => 'nullable|integer|min:0', 
                'name' => 'string|max:255', 
                'year_born_id' => 'nullable', 
                'gender' => 'nullable|in:1,2,3', 
                'phone' => 'string|max:15', 
                'edu_level_id' => 'nullable', 
                'certificate_id' => 'nullable', 
                'portrait_photo' => 'nullable|url', 
                'content' => 'nullable|string|max:1000', 
            ]);
            $data = ProductJobUserViewCv::find($id);
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

    public function deleteProducJobUserViewCv($id)
    {
        $data = ProductJobUserViewCv::find($id);
        if (!$data) {
            return response()->json(['error' => 'Product not found'], 404);
        }
        $data->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }
}
