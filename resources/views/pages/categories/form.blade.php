@extends('layouts.layouts')


@section('page_title')

@section('breadcrumbs')
    @php

       if($model &&  $model->id ){
              $breadcum = [
                  [
                  'name' => 'Danh mục',
                  'url' => route('categories')
                    ],
                  [
                      'name' => 'Chỉnh sửa',
                      'url' =>  ''
                  ],
                  [
                      'name' => $model->name,
                      'url' => ''
                  ]
              ];
        }else{
              $breadcum = [
                    [
                        'name' => 'Danh mục',
                        'url' => route('categories')
                  ],
                  [
                      'name' => 'Thêm mới',
                      'url' => ''
                  ]
            ];
         }

    @endphp
<div class="row mb-3 mt-2 bg-white">
    <div class="col-md-12 px-0">
        @include('layouts.components.breadcrumb',$breadcum)
    </div>
</div>
    
@endsection



@section('content')
    <link rel="stylesheet" href="{{asset('css/treeSelect.min.css')}}">
    <script src="{{asset('js/treeSelect.min.js')}}"></script>

    <div class="row bg-white backend-container pt-3">
        <div class="col-md-12 pb-3">
            <div class="">
                <ul class="nav nav-pills mb-4">
                    <li class="nav-item">
                        <button type="button" class="nav-link active">
                            Thông tin
                        </button>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="">
                        <form action="" id="form-data" method="POST" class="form-validate form-ajax">
                            <input type="hidden" name="id" value="{{$model->id ?? null}}">
                            <div class="">
                                <div class="col-md-12">
                                    <div class="text-right">
                                         <div class="btn-group">
                                             <button onclick="save()" class="btn mr-2 save" type="button">
                                                <i class="fas fa-save"></i>
                                                Lưu
                                            </button>
                                            <a href="{{route('categories')}}" class="btn">
                                                <i class="fas fa-times-circle"></i>
                                                Hủy
                                            </a>
                                         </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <div class="col-sm-3 control-label">
                                            <label for="name">Tên danh mục <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" name="name" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-sm-3 control-label">
                                            <label for="name">Danh mục cha <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="tree_select_data"></div>
                                            <input type="hidden" name="category_parent_id" id="category_parent_id">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-3 control-label">
                                            <label for="category_id">Thể loại<span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-6">
                                            <select name="category_id" class="form-control select2" id="" data-placeholder="-- Loại danh mục --">
                                                <option value="1" >Thuê căn hộ / phòng trọ</option>
                                                <option value="2"> Buôn bán điện tử</option>
                                                <option value="3" > Việc làm</option>
                                          </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-3 control-label">
                                            <label for="status">Trạng thái</label>
                                        </div>
                                        <div class="col-md-6">
                                          <select name="status" class="form-control select2" id="" data-placeholder="-- Trạng thái --">
                                                <option value="1" selected>Bật</option>
                                                <option value="0">Tắt</option>
                                          </select>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div> 

    

@endsection

@section('scripts')
   <script>


    function save(){
        let _this = $('.save');
        let htmlOld = _this.html();
        _this.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
        $.ajax({
            type: 'POST',
            url: '{{route('categories.save')}}',
            dataType: 'json',
            data : {
             'data' : $('#form-data').serialize()
            }
        }).done(function(result){
            _this.prop('disabled', false).html(htmlOld); 
            if(result?.status == 'error'){
                show_message(result.message, result.status);
                return false;
            }
         
           
        }).fail(function(data) {
            _this.prop('disabled', false).html(htmlOld);
            show_message('Lỗi hệ thống', 'error');
            
        });
    } 
    let val = '{{value ? value : [],}}'
    console.log(val)



     //tree select
     const domElement = document.querySelector('.tree_select_data')
     const treeselect = new Treeselect({
        parentHtmlContainer: domElement,
        value: [],
        options: @json($categories),
        placeholder: '-- Chon danh mục cha --',
        isSingleSelect: true,
    })

    treeselect.srcElement.addEventListener('input', (e) => {
    console.log('Selected value:', e.detail)
        $('#category_parent_id').val(e.detail );
    })

   </script>

 
@endsection