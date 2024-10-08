<?php

namespace App\Http\Controllers\Api\Products;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class ProductElectronicController extends Controller
{
    public function getData(Request $request)
    {
       return response()->json(['data' => 123]);
    }


    public function save(Request $request)
    {
         
    }
}
