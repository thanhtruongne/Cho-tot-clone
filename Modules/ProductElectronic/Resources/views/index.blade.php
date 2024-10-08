@extends('layouts.layouts')


@section('page_title')

@section('breadcrumbs')
    @php
        $breadcum = [
            [
                'name' => 'Quản lý sản phẩm bán',
                'url' => '/'
            ],
            [
                'name' => 'Sản phẩm bán',
                'url' => route('products.electronic')
            ],
        ];

    @endphp
<div class="row mb-3 mt-2 bg-white">
    <div class="col-md-12 px-0">
        @include('layouts.components.breadcrumb',$breadcum)
    </div>
</div>
    
@endsection

@section('content')
<style>
    .bootstrap-table .fixed-table-container .table thead th.detail {
    width: 30px;

    }
</style>


    <div class="row bg-white backend-container pt-3" style="margin-left: -15px;margin-right:-15px">
        <div class="col-md-12 pb-3">
            <div class="">
                <div class="row">
                  {{-- Tìm kiếm --}}
                   <div class="col-md-8 form-inline">
                      <form action="" class="form-inline w-100 form-search mb-3" id="form-search">
                           <input type="text" name="search" class="form-control w-30 mr-1" placeholder="-- Tên danh mục --">
                           <div class="" style="width: 28% !important;">
                            {{-- <select name="category_id" id="" class="select2 mr-2" data-placeholder="-- Danh mục --" multiple>
                                @foreach ($nodes as $item)
                                    <option value="{{$item->id}}">   
                                       {{ str_repeat('|---',($item->depth > 0) ? $item->depth : 0) }}
                                       {{ $item->name }}
                                   </option>
                                @endforeach
                          </select> --}}

                           </div>
                          
                           <input type="hidden" name="category_id" id="category_id">
                           <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;Tìm kiếm</button>
                      </form>
                   </div>


                   <div class="col-md-4 text-right ">
                         <div class="">
                            <div class="btn_group">
                                <button class="btn" id="publish_on" onclick="changeStatus(1,'publish_on')" data-status="1">
                                    <i class="fa fa-check-circle"></i> &nbsp;Bật
                                </button>
                                <button class="btn" id="publish_off" onclick="changeStatus(0,'publish_off')" data-status="1">
                                    <i class="fa fa-check-circle"></i> &nbsp;Tắt
                                </button>
                                <a class="btn"><i class="fa fa-download"></i> Xuất file</a>
                                <a onclick="create()" class="btn" href="#">
                                    <i class="fa fa-plus"></i> 
                                    Thêm mới
                                </a>

                                <button class="btn" id="delete-item" disabled>
                                    <i class="fa fa-trash"></i> 
                                    Xóa
                                </button>
                            </div>
                         </div>
                   </div>
                </div>
                <br>

                <table
                    class="tDefault table table-bordered bootstrap-table"
                >
                    <thead>
                        <tr>    
                          <th data-field="index" data-align="center" data-width="5%" data-formatter="index_formatter">#</th> 
                            <th data-field="check" data-checkbox="true" data-width="4%"></th>
                            <th data-field="name" data-width="20%" data-formatter="name_formatter">Tiêu đề bài đăng</th>
                            <th data-field="category_child" data-formatter="getModalCategory">Tên sản phẩm</th>
                            <th data-field="category_child" data-formatter="getModalCategory">Giá tiền</th>
                            <th data-field="category_child" data-formatter="getModalCategory">Ngày đăng</th>
                            <th data-field="approved" data-align="center" data-width="10%">Duyệt</th>
                            <th data-field="status" data-align="center" data-width="12%" data-formatter="status_formatter">Trạng thái</th>    
                        </tr>
                    </thead>
                </table>
        
            </div>
        </div>
    </div> 


@endsection

@section('scripts')
    <script src="{{asset('js/treeSelect.min.js')}}"></script>
    <script>
        function index_formatter(value, row, index) {
            console.log(row);
            return (index+1);
        }

        function name_formatter(value,row,index){
            return '<a class="overide" id="edit_'+row.id+'" href="#" onClick="edit('+row.id+')">'+ row.name +'</a>';
        }

        function getModalCategory(value,row,index){
            return '<a id="row_'+row.id+'" class="overide" href="#" onClick="getModal('+ row.id +')">'+ row.category_child +'</a>';
        }


 



         function status_formatter(value, row, index) {
            var status = row.status == 1 ? 'checked' : '';
            var html = `<div class="custom-control custom-switch">
                            <input type="checkbox" `+ status +` onclick="changeStatus(`+row.id+`)" class="custom-control-input" id="customSwitch_`+row.id+`">
                            <label class="custom-control-label" for="customSwitch_`+row.id+`"></label>
                        </div>`;
            return html;
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('categories.getdata') }}',
            remove_url: '{{ route('categories.remove',['type' => "all"]) }}'
        });


         function changeStatus(status,type) {
            var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
            let _this = $('#'+type);
            let html = _this.html();
            _this.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Đang xử lý...');
            if (ids.length <= 0) {
                show_message('Vui lòng chọn 1 dòng dữ liệu', 'error');
                return false;
            }
            $.ajax({
                url: '{{route('categories.change.status')}}',
                type: 'post',
                data: {
                    ids: ids,
                    status: status
                }
            }).done(function(data) {
                // if (id == 0) {
                //     show_message(data.message, data.status);
                // }
                _this.prop('disabled', false).html(html);
                $(table.table).bootstrapTable('refresh');
                return false;
            }).fail(function(data) {
                _this.prop('disabled', false).html(html);
                show_message('Lỗi hệ thống', 'error');
                return false;
            });
        };


    


    </script>
@endsection