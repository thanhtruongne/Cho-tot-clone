<?php

namespace App\Http\Controllers\Api\Type;

use App\Http\Controllers\Controller;
use App\Models\TypeOfHouse;
use Illuminate\Http\Request;

class TypeOfHouseController extends Controller
{
    //
    public function addTypeOfHouse(Request $request)
    {
        try {

            $validatedData = $request->validate([
                'code' => 'required|string|max:150', 
                'name' => 'required|string',
                'status' => 'nullable|integer|in:0,1', 
                'created_by' => 'nullable|integer', 
                'updated_by' => 'nullable|integer', 
                'order' => 'nullable|integer',
            ]);
            // $data = ProductRentHouse::create($request->validated());
            $data = TypeOfHouse::create($validatedData);
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

    public function updateTypeOfHouse(Request $request, $id)
    {
        try {

            $validatedData = $request->validate([
                'code' => 'string|max:150', 
                'name' => 'string',
                'status' => 'nullable|integer|in:0,1', 
                'created_by' => 'nullable|integer', 
                'updated_by' => 'nullable|integer', 
                'order' => 'nullable|integer',
            ]);
            $data = TypeOfHouse::find($id);
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

    public function deleteTypeOfHouse($id)
    {
        $data = TypeOfHouse::find($id);
        if (!$data) {
            return response()->json(['error' => 'Product not found'], 404);
        }
        $data->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }
}
