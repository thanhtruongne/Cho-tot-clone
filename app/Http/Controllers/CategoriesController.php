<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Categories;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;
use App\Models\UserActivities;
use App\Models\Visits;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{

    public function index(){
        // dd(123);
        return view('pages.categories.index');
    }


    public function getData(Request $request){
        $search = $request->input('search');
        $category_id = $request->input('category_id');
        $sort = $request->input('sort','id');
        $order = $request->input('order','desc');
        $offset = $request->input('offset',0);
        $limit = $request->input('limit',20);
        
        $query = Categories::query();
        $query->select(['name','type','status','parent_id','id']);
        if($search){
            $query->where('name','like','%'.$search.'%');
        }
        $query->orderBy($sort,$order);
        $query->offset($offset);
        $query->limit($limit);
        // $query->with('ancestors');
        // $query->withDepth()->reversed();
        $count = $query->count();
        $rows = $query->get()->toTree();

        foreach($rows as $row){
            $row->category_child = count($row->children);
            $row->edit_url= route('categories.form',['id' => $row->id]);
        }
        // dd($rows);
        // dd($rows);
        return response()->json(['rows' => $rows , 'total' =>$count]);
           
    }

    public function remove(Request $request){

    }
}
