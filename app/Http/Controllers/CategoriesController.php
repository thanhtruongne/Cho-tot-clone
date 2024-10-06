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
    protected $tree;

    public function __construct(){
        $categories = Categories::whereNotNull('name')->get()->toTree()->toArray();
        $this->tree =  $this->rebuildTree($categories);
    }

    public function index(){
        return view('pages.categories.index',['categories' => $this->tree]);
    }

    private function rebuildTree($categories,$parent_id = 0){
        foreach($categories as $key => $children){
                if($parent_id == $children['parent_id']){
                    $data[] = [
                       'name' => $children['name'],
                       'value' => $children['id'],
                       'children' => count($children['children']) ?  $this->rebuildTree($children['children'],$children['id']) : []
                   ];
                }
            }
             
        return  $data;
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
        $count = $query->count();
        $rows = $query->get()->toTree();
        foreach($rows as $row){
            $row->category_child = count($row->children);
            // $row->edit_url= route('categories.edit',['id' => $row->id]);
            $row->html = $this->renderHTML($row);
        }
        return response()->json(['rows' => $rows , 'total' =>$count]);
           
    }


    private function renderHTML($row){
        $html = '';
         if($row){
            foreach($row->children as $item){
                $hasChild =  count($item->children) > 0 ? 'is-expandable' : '';
                $html .= 
                  ' <details class="tree-nav__item '.$hasChild.'" open>
                        <summary class="tree-nav__item-title ">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="node" style="min-width:98%">
                                    <a class="overide" id="edit_'.$item->id.'" onClick="edit('.$item->id.')" href="#">'.$item->name.'</a>
                                </div>
                                <button id="row_'.$item->id.'" onClick="deleteRow('.$item->id.')" class="btn"><i class="fas fa-trash"></i></button>
                            </div>
                          
                        </summary>
                        '.$this->renderHTML($item).'
                    </details> ';
            }
         }
         return $html;
        
    }
    public function remove(Request $request){
        if($request->type && $request->type == 'all'){
            $ids = $request->input('ids', null);
            foreach ($ids as $id){
                if(!$check = Categories::descendantsOf($id)->isEmpty()){
                    return response()->json(['status' => 'error','message' => 'Danh mục chứa hoặc tồn tại danh mục con']);
                }
                $remove = Categories::find($id);
                $remove->delete();
            }
        }
        else {
            $id = $request->id;
            if(!$check = Categories::descendantsOf($id)->isEmpty()){
                return response()->json(['status' => 'error','message' => 'Danh mục chứa hoặc tồn tại danh mục con']);
            }
            $remove = Categories::find($id);
            $remove->delete();
        }
        return response()->json(['status' => 'success','message' => 'Xóa danh mục thành công']);
      
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
                $model = Categories::find($id);
                $model->status = $status;
                $model->save();
            }
        } else {
            $model = Categories::find($ids);
            $model->status = $status;
            $model->save();
        }


        return response()->json(['status' => 'success','message' => 'Thay đổi trạng thái thành công']);

    }


    public function form(Request $request){
        if($request->id){
            $model = Categories::find($request->id);
            return response()->json(['status' => 'success','model' => $model,'categories' => $this->tree]);
            // return view('pages.categories.form',['model' => $model, 'ancestor' => $ancestor,'categories' => $this->tree]);
        }
        return response()->json(['status' => 'error','message' => 'Có lỗi xảy ra !']);
    }


    public function save(Request $request){
        $rules = [
            'name' => 'required',
            'type' => 'required',
            'status' => 'required',
        ];
        $messages = [
            'name.required' => 'Tên danh mục không được bỏ trống',
            'type.required' => 'Loại danh mục không được bỏ trống',
            'status.required' => 'Trạng thái bắt buộc chọn',
        ];
        $validator = \Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->all()[0] , 'status' => 'error']);
        }
        $model = Categories::firstOrCreate(['id' => $request->id]);
        $model->name = $request->name;
        $model->type = $request->type;
        $model->status = $request->status;
        $model->save();

        if($request->category_parent_id){
            $parent = Categories::find($request->category_parent_id);
            $parent->appendNode($model);
        }
        return response()->json(['message' => 'Lưu thành công' , 'status' => 'success']);
    }
}
