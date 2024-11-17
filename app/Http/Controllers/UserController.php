<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function index(){
        return view('pages.users.index');   
    }


    public function getData(Request $request)  {
      $search = $request->input("search");
      $limit = $request->input("limit",10);
      $offset = $request->input("offset",0);
      $sort = $request->input("sort","id");
      $order = $request->input("order","desc");


      $query = User::query();
      $query->leftJoin('user_activity_durations as a','a.user_id','=','user.id');
      if($search) {
          $query->where(function($subquery) use($search) {
              $subquery->where('email','like',$search.'%');
              $subquery->orWhere('firstname','like',$search.'%');
              $subquery->orWhere('lastname','like',$search.'%');
          });
      }
      $query->limit($limit);
      $query->offet($offset);
      $query->orderBy($sort,$order);
      $count = $query->count();
      $rows = $query->get();
      dd(\Cache::get('online-users'));
      foreach($rows as $row) {

      }
    

    }

    public function remove(Request $request) {

    }
}
