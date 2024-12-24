<?php

namespace App\Http\Controllers\Api\Type;

use App\Http\Controllers\Controller;
use App\Models\PostingType;
use Illuminate\Http\Request;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;
class PostingTypeController extends Controller
{

    public function addPostingType(Request $request)
    {
        try {

            $validatedData = $request->validate([
                'code' => 'string|max:150|unique:bathroom_type,code', // Bắt buộc, tối đa 150 ký tự, duy nhất trong bảng bathroom_type
                'name' => 'string', // Bắt buộc, kiểu chuỗi
                'content' => 'string',  // Bắt buộc, kiểu chuỗi
                'status' => 'nullable|integer|in:0,1', // Trạng thái, có thể là 0 hoặc 1, không bắt buộc
                'created_by' => 'nullable|integer', // Không bắt buộc, kiểu số nguyên
                'updated_by' => 'nullable|integer', // Không bắt buộc, kiểu số nguyên
                'order' => 'nullable|integer', // Không bắt buộc, kiểu số nguyên
                'cost' => 'numeric|min:0|max:99999999.99'
            ]);
            $data = PostingType::create($validatedData);
            if ($data) {
                return redirect()->route('type-posting')->with('success', ' thêm thành công!');

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
                return redirect()->route('type-posting')->with('error', 'Product not found');
            }

            $data->fill($validatedData);

            if ($data->save()) {
                return redirect()->route('type-posting')->with('success', 'Product updated successfully');
            } else {
                return redirect()->route('type-posting')->with('error', 'Update failed');
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator->errors())->withInput();
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




  public function getDataPostingType(Request $request)
      {
          $data = PostingType::whereNotNull('name')->with('posting_data_type')->get();
          return response()->json(['data' => $data]);
      }

    //   public function index()
    //   {
    //     return view
    //   }

    public function index()
    {
        return view('pages.TypeProduct.index');
    }

    public function PostingTypesData()
    {
        $dataPost = PostingType::all();

        if ($dataPost->isEmpty()) {
            return response()->json(data: ['dataPost' => []]);
        }

        return DataTables::of($dataPost)
            ->addColumn('action', function ($data) {
                return '<a href="' . route('type-posting.edit', $data->id) . '" <a href="#" class="btn btn-primary btn-sm rounded-pill shadow-lg hover-shadow-lg text-white px-4 py-2" style="font-size: 16px;">Edit</a>
';
            })
            ->make(true);
    }

    public function PostingTypesEdit($id)
    {
        $data = PostingType::find($id);

        if (!$data) {
            return redirect()->route('type-posting')->with('error', 'Không tìm thấy người dùng!');
        }
        return view('pages.TypeProduct.edit', compact('data')); // Chuyển dữ liệu data vào view
    }
}

