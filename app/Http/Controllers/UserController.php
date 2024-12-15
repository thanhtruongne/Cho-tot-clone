<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    private $attemp_admin = 'admin';

    public function setTypeAdmin(string $name){
        return $this->attemp_admin = $name;
    }
    
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
      $query->from('users');
      $query->select(['users.*']);
    //   $query->leftJoin('user_activity_durations as a','a.user_id','=','users.id');
      //set cứng
      $query->where('users.username','!=',$this->attemp_admin);
      if($search) {
          $query->where(function($subquery) use($search) {
              $subquery->where('users.email','like',$search.'%');
              $subquery->orWhere('users.firstname','like',$search.'%');
              $subquery->orWhere('users.lastname','like',$search.'%');
          });
      }
      $query->limit($limit);
      $query->offset($offset);
      $query->orderBy($sort,$order);
      $count = $query->count();
      $query->with(['province','district','ward','user_activities']);
      $rows = $query->get();
      //get user online
      $users_onlines = \Cache::get('online-users-id');
      foreach($rows as $row){
        if(isset($users_onlines) && !empty($users_onlines) && in_array($row->id,$users_onlines)) {
            $row->online = 'active';
        } else {
            $timestamp = Carbon::createFromTimestamp($row?->user_activities->last()->last_acti_time);
            $row->online = $timestamp->diffForHumans(); 
        }
        if($row->address)
            $row->address_temp = $row->address .' ,'.$row?->province?->name.' ,'.$row?->district?->name.' ,'.$row?->ward?->name;
        else $row->address_temp = null;
      }
      return response()->json(['rows' => $rows , 'total' =>$count]);
    

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
