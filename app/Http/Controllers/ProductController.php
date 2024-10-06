<?php

namespace App\Http\Controllers;

use App\Models\ProductElectronics;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return view('pages.product.addProduct');
    }


    public function addProduct(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'code' => 'required|string|unique:products,code',
            'category_id' => 'required|integer',
            'cost' => 'required|numeric',
            'brand_id' => 'required|integer',
            'condition_used' => 'required|integer',
        ]);

        ProductElectronics::create($validatedData);

        return redirect()->back()->with('success', 'Sản phẩm đã được thêm thành công!');
    }
}
