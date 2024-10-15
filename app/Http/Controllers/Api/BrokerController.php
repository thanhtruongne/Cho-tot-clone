<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Broker;
use Illuminate\Http\Request;

class BrokerController extends Controller
{
    //
    public function addBroker(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'user_id' => 'required|integer',
                'product_rent_id' => 'required|integer',
                'status' => 'required|integer|in:1,2',
                'percent_commission' => 'nullable|integer|min:0|max:100',
            ]);
            $data = Broker::create($validatedData);
            if ($data) {
                return response()->json(['message' => "Thêm sản phẩm thành công",'data'=>$data]);
            } else {
                return response()->json(['data' => "Thêm asd phẩm asdsad công"]);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors' => $e->validator->errors()
            ], 422);
        }
    }

    public function updateBroker(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'user_id' => 'integer',
                'product_rent_id' => 'integer',
                'status' => 'integer|in:1,2',
                'percent_commission' => 'nullable|integer|min:0|max:100',
            ]);
            $data = Broker::find($id);
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

    public function deleteBroker($id)
    {
        $data = Broker::find($id);
        if (!$data) {
            return response()->json(['error' => 'Product not found'], 404);
        }
        $data->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }
}
