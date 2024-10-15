<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductJobs;
use Illuminate\Http\Request;

class ProductJobController extends Controller
{
    //
    public function addProductJobs(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'user_id' => 'required|exists:users,id',
                'code' => 'required|string|max:150',
                'images' => 'nullable|string', 
                'category_id' => 'required',
                'province_code' => 'required|string|max:10',
                'district_code' => 'required|string|max:10',
                'ward_code' => 'required|string|max:10',
                'quantity_user' => 'nullable|integer|min:0',
                'type_profession_id' => 'required',
                'type_jobs_id' => 'required',
                'form_salary_id' => 'required',
                'min_salary' => 'required|numeric|min:0',
                'max_salary' => 'nullable|numeric|gte:min_salary', 
                'salary' => 'nullable|numeric',
                'min_age' => 'required|integer|min:0',
                'max_age' => 'required|integer|gte:min_age', 
                'gender' => 'required|integer|in:1,2,3', 
                'education_level_id' => 'required',
                'work_exp_id' => 'required',
                'request_gender' => 'nullable|tinyInteger',
                'request_year_born' => 'nullable|tinyInteger',
                'request_work_exp' => 'nullable|tinyInteger',
                'request_edu_level' => 'nullable|tinyInteger',
                'request_certificate' => 'nullable|tinyInteger',
                'request_portrait_photo' => 'nullable|tinyInteger',
                'have_question' => 'nullable|tinyInteger',
            ]);
            $data = ProductJobs::create($validatedData);
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

    public function updateProductJobs(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'title' => 'string|max:255',
                'content' => 'string',
                'user_id' => 'exists:users,id',
                'code' => 'string|max:150',
                'images' => 'nullable|string', 
                'category_id' => 'int',
                'province_code' => 'string|max:10',
                'district_code' => 'string|max:10',
                'ward_code' => 'string|max:10',
                'quantity_user' => 'nullable|integer|min:0',
                'type_profession_id' => 'integer',
                'type_jobs_id' => 'integer',
                'form_salary_id' => 'integer',
                'min_salary' => 'numeric|min:0',
                'max_salary' => 'nullable|numeric|gte:min_salary', 
                'salary' => 'nullable|numeric',
                'min_age' => 'integer|min:0',
                'max_age' => 'integer|gte:min_age', 
                'gender' => 'integer|in:1,2,3', 
                'education_level_id' => 'integer',
                'work_exp_id' => 'integer',
                'request_gender' => 'nullable|tinyInteger',
                'request_year_born' => 'nullable|tinyInteger',
                'request_work_exp' => 'nullable|tinyInteger',
                'request_edu_level' => 'nullable|tinyInteger',
                'request_certificate' => 'nullable|tinyInteger',
                'request_portrait_photo' => 'nullable|tinyInteger',
                'have_question' => 'nullable|tinyInteger',
            ]);
            $data = ProductJobs::find($id);
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

    public function deleteProductJobs($id)
    {
        $data = ProductJobs::find($id);
        if (!$data) {
            return response()->json(['error' => 'Product not found'], 404);
        }
        $data->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }
}
