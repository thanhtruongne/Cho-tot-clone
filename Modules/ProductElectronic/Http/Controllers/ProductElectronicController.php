<?php

namespace Modules\ProductElectronic\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class ProductElectronicController extends Controller
{
    public function index()
    {
        return view('productelectronic::index');
    }


    public function save(Request $request)
    {
         
    }


    public function getData(Request $request){
        
    }
}
