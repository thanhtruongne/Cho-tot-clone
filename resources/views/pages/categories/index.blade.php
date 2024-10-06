@extends('layouts.layouts')


@section('page_title')

@section('breadcrumbs')
    @php
        $breadcum = [
            [
                'name' => 'Danh mục',
                'url' => '/'
            ],
        ];

    @endphp
<div class="row mb-3 mt-2 bg-white">
    <div class="col-md-12 px-0">
        @include('layouts.components.breadcrumb',$breadcum)
    </div>
</div>
    
@endsection

@section('links')
{{-- <link href="{{asset('css/iconIconic.css')}}" rel="stylesheet" type="text/css"> --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{asset('css/cusomTreeCategory.css')}}">
    <link rel="stylesheet" href="{{asset('css/treeSelect.min.css')}}">

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
                           @php
                               $nodes = App\Models\Categories::whereNotNull('name')->withDepth()->with('ancestors')->get()->toFlatTree();
                           @endphp
                           <div class="" style="width: 28% !important;">
                            <select name="category_id" id="" class="select2 mr-2" data-placeholder="-- Danh mục --" multiple>
                                @foreach ($nodes as $item)
                                    <option value="{{$item->id}}">   
                                       {{ str_repeat('|---',($item->depth > 0) ? $item->depth : 0) }}
                                       {{ $item->name }}
                                   </option>
                                @endforeach
                          </select>
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
                    data-detail-view="true"
                    data-detail-formatter="detailFormatter"
                >
                    <thead>
                        <tr>    
                          <th data-field="index" data-align="center" data-width="5%" data-formatter="index_formatter">#</th> 
                            <th data-field="check" data-checkbox="true" data-width="4%"></th>
                            <th data-field="name" data-width="20%" data-formatter="name_formatter">Tên danh mục</th>
                            <th data-field="category_child" data-formatter="getModalCategory">Số lượng danh mục con</th>
                            <th data-field="type" data-align="center" data-width="10%">Loại</th>
                            <th data-field="status" data-align="center" data-width="12%" data-formatter="status_formatter">Trạng thái</th>    
                        </tr>
                    </thead>
                </table>
        
            </div>
        </div>
    </div> 
    <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="form_save" onsubmit="return false;" method="post"
                    action="{{ route('categories.save') }}" class="form-validate form-ajax" role="form"
                    enctype="multipart/form-data">
                    <input type="hidden" name="id" value="">
                    <div class="modal-header">
                        <div class="btn-group">
                            <h5 class="modal-title" id="exampleModalLabel"></h5>
                        </div>
                        <div class="btn-group act-btns">
                                <button type="button" id="btn_save" onclick="save(event)" class="btn save mr-2"
                                    data-must-checked="false"><i class="fa fa-save"></i>
                                    &nbsp; Lưu</button>
                            <button data-dismiss="modal" aria-label="Close" class="btn"><i
                                class="fa fa-times-circle"></i>&nbsp;Hủy</button>
                        </div>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <div class="col-sm-4 control-label">
                                        <label>Tên danh mục<span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-7">
                                        <input name="name" type="text" class="form-control" value=""
                                            required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-4 control-label">
                                        <label>Danh mục cha<span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="tree_select_demo"></div>
                                        <input type="hidden" id="category_parent_id" name="category_parent_id" value="">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-4 control-label">
                                        <label for="group">Loại danh mục</label>
                                    </div>
                                    <div class="col-md-7" id="position_modal">
                                        <select name="type" class="form-control select2" id="type_id" data-placeholder="-- Loại danh mục --">
                                            <option value="1" >Thuê căn hộ / phòng trọ</option>
                                            <option value="2"> Buôn bán điện tử</option>
                                            <option value="3" > Việc làm</option>
                                        </select>
                                    </div>

                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4 control-label">
                                        <label>Trạng thái <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-7">
                                        <label class="radio-inline">
                                            <input id="enable" required type="radio" name="status" value="1"
                                                checked> Bật
                                        </label>
                                        <label class="radio-inline">
                                            <input id="disable" required type="radio" name="status"
                                                value="0"> Tắt
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
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


        function detailFormatter(index, row) {
            var html = []
            var rows = $('<nav>').addClass('tree-nav');
            rows.html(row.html)
            return rows;
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

        function deleteRow(id){
          let _this = $('#row_'.id);
          let html = _this.html();
          _this.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
          Swal.fire({
                title: '',
                text: 'Bạn có muốn xóa dữ liệu này ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText:'Đồng ý',
                cancelButtonText: 'Hủy',
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: '{{route('categories.remove')}}',
                        dataType: 'json',
                        data : {
                        'id' : id
                        }
                    }).done(function(result){
                        console.log(result);
                        show_message(result.message, result.status);
                        $(table.table).bootstrapTable('refresh');
                        _this.prop('disabled', false).html(html); 
                    }).fail(function(data) {
                        _this.prop('disabled', false).html(html);
                        show_message('Lỗi hệ thống', 'error');
                        return false;
                    });
                }
            });

    
        }

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

        function create(){
            $('#exampleModalLabel').html('Thêm mới');
            $('#form_save').trigger("reset");
            $("input[name=id]").val('');
            $('.tree_select_demo').html(' ');
            let position = '<option value="1">Thuê căn hộ / phòng trọ</option> <option value="2">Buôn bán điện tử</option> <option value="3">Việc làm</option>';
            $("#type_id").html(position)
            treeSelect();
            $('#myModal2').modal();

        }

            function treeSelect(value){
                const domElement = document.querySelector('.tree_select_demo')
                const treeselect = new Treeselect({
                    parentHtmlContainer: domElement,
                    value: value ? value : [],
                    options: @json($categories),
                    placeholder: '-- Chon danh mục cha --',
                    isSingleSelect: true,
                })

                treeselect.srcElement.addEventListener('input', (e) => {
                console.log('Selected value:', e.detail)
                    $('#category_parent_id').val(e.detail );
                })
            }

            function save(){
                let item = $('.save');
                let oldtext = item.html();
                item.html('<i class="fa fa-spinner fa-spin"></i>');
                item.attr('disabled', true);
                event.preventDefault();
                $.ajax({
                    url: "{{ route('categories.save') }}",
                    type: 'POST',
                    data: $("#form_save").serialize(),
                }).done(function(data) {
                    console.log(data);
                    item.html(oldtext);
                    $('.save').attr('disabled', false);
                    if (data && data.status == 'success') {
                        $('#myModal2').modal('hide');
                        show_message(data.message, data.status);
                        $(table.table).bootstrapTable('refresh');

                    } else {
                        show_message(data.message, data.status);
                    }
                    return false;
                }).fail(function(data) {
                    item.html(oldtext);
                    $('.save').attr('disabled', false);
                    show_message('Lỗi dữ liệu', 'error');
                    return false;
                });

            }


            function edit(id){
                let item = $('#edit_' + id);
                let oldtext = item.html();
                item.prop('disabled',true).html('<i class="fa fa-spinner fa-spin"></i>')
                $("input[name=id]").val('');
                $('.tree_select_demo').html(' ');
                let position = '<option value="1">Thuê căn hộ / phòng trọ</option> <option value="2">Buôn bán điện tử</option> <option value="3">Việc làm</option>';
                $("#type_id").html(position)
                $.ajax({
                url: "{{ route('categories.edit') }}",
                type: 'get',
                data: {
                    id: id,
                }
            }).done(function(data) {
                item.prop('disabled',false).html(oldtext)
                console.log(data)
                $('.tree_select_data').html(' ');
                $('#exampleModalLabel').html('Chỉnh sửa');
                $("input[name=id]").val(data.model.id);
                $("input[name=name]").val(data.model.name);
                treeSelect(data.model.parent_id);

                if (data.model.type) {
                    $("#position_modal select").val(data.model.type);
                    $("#position_modal select").val(data.model.type).change();
                } else {
                    $("#position_modal select").val('');
                    $("#position_modal select").val('').change();
                }

                if (data.model.status == 1) {
                    $('#enable').prop('checked', true)
                    $('#disable').prop('checked', false)
                } else {
                    $('#enable').prop('checked', false)
                    $('#disable').prop('checked', true)
                }

                $('#myModal2').modal();
                return false;
            }).fail(function(data) {
                item.prop('disabled',false).html(oldtext)
                show_message('Lỗi dữ liệu', 'error');
                return false;
            });

            }

    </script>
@endsection