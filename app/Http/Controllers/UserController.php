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
    //   dd(\Cache::get('online-users'));
      foreach($rows as $row) {

      }
      return response()->json(['rows' => $rows,'count' => $count]);
    

    }

    public function remove(Request $request){
        $this->validateRequest([
            'ids' => 'required',
            // 'status' => 'required|in:0,1',
        ], $request, [
            'ids' => 'Trường dữ liệu chon không được trống',
            // 'status' => 'Trạng thái tróng !'
        ]);

        $ids = $request->input('ids', null);
        $status = $request->input('status') ?? 0;
        if(is_array($ids)) {
            foreach ($ids as $id) {
                $model = User::find($id);
                $model->status = $status;
                $model->remove();
            }
        } else {
            $model = User::find($ids);
            $model->status = $status;
            $model->remove();
        }


        return response()->json(['status' => 'success','message' => 'Xóa thành công']);
    }

    public function changeStatus(Request $request){
        $this->validateRequest([
            'ids' => 'required',
            'status' => 'required|in:0,1',
        ], $request, [
            'ids' => 'Trường dữ liệu chon không được trống',
            'status' => 'Trạng thái tróng !'
        ]);

        $ids = $request->input('ids', null);
        $status = $request->input('status') ?? 0;
        if(is_array($ids)) {
            foreach ($ids as $id) {
                $model = User::find($id);
                $model->status = $status;
                $model->save();
            }
        } else {
            $model = User::find($ids);
            $model->status = $status;
            $model->save();
        }


        return response()->json(['status' => 'success','message' => 'Thay đổi trạng thái thành công']);

    }

    public function form(Request $request) {

    }
}
