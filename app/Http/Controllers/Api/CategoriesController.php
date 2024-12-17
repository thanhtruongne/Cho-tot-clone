<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CategoriesController extends Controller
{
    public function getData(Request $request) {
        try {
            $this->validateRequest([
                'type' => 'required'
            ],$request,[
                'type' => 'Dạng danh mục'
            ]);
            
            $parent = Categories::where('key',$request->type)->firstOrFail('id');
            $key = 'categories_id_cache_'.$request->type;
            $child = Cache::tags(['categories'])->remember($key,\Carbon::now()->addHours(12),function() use($parent) {
                return  Categories::descendantsOf($parent->id,['name','id','key']);
            });
            return response()->json(['data' => $child , 'message' => 'Transport data thành công','status' =>'success']);
        
        } catch (\Throwable $th) {
           return response()->json(['message' => $th->getMessage(),'status' =>'error']);
        }
       

    }
}
