<?php

namespace Modules\Permission\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('permission::index');
    }
    
    public function getData(Request $request){
        
    }
   
}
