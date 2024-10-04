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

@section('content')
    <div class="row bg-white backend-container pt-3" style="margin-left: -15px;margin-right:-15px">
        <div class="col-md-12 pb-3">
            <div class="">
                <div class="row">
                  {{-- Tìm kiếm --}}
                   <div class="col-md-6 form-inline">
                      <form action="" class="form-inline w-100 form-search mb-3" id="form-search">
                           <input type="text" name="search" class="form-control w-30 mr-1" placeholder="-- Tên danh mục --">
                           <div class="w-25 mr-2" style="width: 26% !important">
                                <select name="category_id" class="form-control select2" id="" data-placeholder="-- Danh mục sản phẩm--">
                                    {{-- <option value="123">asd</option> --}}
                                </select>
                           </div>
                           <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;Tìm kiếm</button>
                      </form>
                   </div>

                   <div class="col-md-6 text-right ">
                         <div class="">
                            <div class="btn_group">
                                <button class="btn" onclick="changeStatus(1)" data-status="1">
                                    <i class="fa fa-check-circle"></i> &nbsp;Bật
                                </button>
                                <button class="btn" onclick="changeStatus(0)" data-status="1">
                                    <i class="fa fa-check-circle"></i> &nbsp;Tắt
                                </button>
                                <a class="btn" href="https://vus.toplearning.vn/admin-cp/libraries/ebook/export"><i class="fa fa-download"></i> Xuất file</a>
                                <a class="btn" href="https://vus.toplearning.vn/admin-cp/libraries/ebook/export"><i class="fa fa-download"></i> Xuất file</a>
                                <button class="btn" id="delete-item" disabled>
                                    <i class="fa fa-trash"></i> 
                                    Xóa<
                                </button>
                            </div>
                         </div>
                   </div>
                </div>
                <br>

                <table class="tDefault table table-hover bootstrap-table">
                    <thead>
                        <tr>
                            <th data-field="index" data-align="center" data-width="2%" data-formatter="index_formatter">#</th>
                            <th data-field="check" data-checkbox="true" data-width="2%"></th>
                            <th data-field="name" data-width="25%" data-formatter="name_formatter">{{trans('lamenu.document')}}</th>
                            <th data-field="category_parent">{{trans("backend.document_category")}}</th>
                            <th data-field="type" data-align="center" data-width="10%">{{trans('backend.last_updated')}}</th>
                            <th data-field="status" data-align="center" data-width="5%" data-formatter="status_formatter">{{trans('latraining.status')}}</th>
                        </tr>
                    </thead>
                </table>
        
            </div>
        </div>
    </div> 

    

@endsection

@section('scripts')
    <script>
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
            remove_url: '{{ route('categories.remove') }}'
        });

         function changeStatus(status) {
            var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
            if (ids.length <= 0) {
                show_message('{{ trans('backend.please_select_data_line') }}', 'error');
                return false;
            }
            $.ajax({
                url: ajax_isopen_publish,
                type: 'post',
                data: {
                    ids: ids,
                    status: status
                }
            }).done(function(data) {
                if (id == 0) {
                    show_message(data.message, data.status);
                }
                $(table.table).bootstrapTable('refresh');
                return false;
            }).fail(function(data) {
                show_message('Lỗi hệ thống', 'error');
                return false;
            });
        };

    </script>
@endsection