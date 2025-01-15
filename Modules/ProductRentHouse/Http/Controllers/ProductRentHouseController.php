<?php

namespace Modules\ProductRentHouse\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class ProductRentHouseController extends Controller
{
    public function index(Request $request)
    {
        return view('productrenthouse::index');
    }

    public function getData(Request $request) {}

    public function remove(Request $request) {}
}
