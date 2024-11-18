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
<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Content</th>
                <th>User ID</th>
                <th>Code</th>
                <th>Type Product</th>
                <th>Province Code</th>
                <th>Ward Code</th>
                <th>Land Area</th>
                <th>Cost</th>
                <th>Created At</th>
                <th>Action</th>

            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
                <tr>
                    <td>{{ $item['id'] }}</td>
                    <td>{{ $item['title'] }}</td>
                    <td>{{ $item['content'] }}</td>
                    <td>{{ $item['user_id'] }}</td>
                    <td>{{ $item['code'] }}</td>
                    <td>{{ $item['type_product'] }}</td>
                    <td>{{ $item['province_code'] }}</td>
                    <td>{{ $item['ward_code'] }}</td>
                    <td>{{ $item['land_area'] }}</td>
                    <td>{{ $item['cost'] }}</td>
                    <td>{{ $item['created_at'] }}</td>
                    <td>
                        <form action="{{ route('manage-postings-delete', $item['id']) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this item?');">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $data->links() }}
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
