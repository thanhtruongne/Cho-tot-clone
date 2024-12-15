@extends('layouts.layouts')

@section('page_title')
    Quản lý sản phẩm bán
@endsection

@section('breadcrumbs')
    @php
        $breadcum = [
            ['name' => 'Quản lý sản phẩm bán', 'url' => '/'],
            ['name' => 'Sản phẩm bán', 'url' => route('products.electronic')],
        ];
    @endphp
    <div class="row mb-3 mt-2 bg-white">
        <div class="col-md-12 px-0">
            @include('layouts.components.breadcrumb', $breadcum)
        </div>
    </div>
@endsection

@section('content')
<div class="table-responsive">
    <table class="table table-bordered table-hover" id="productTable">
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
            </tr>
        </thead>
        <tbody>
            <!-- Dữ liệu sẽ được tải bằng AJAX -->
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {
            var table = $('#productTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('manage-postings.data') }}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'title', name: 'title' },
                    { data: 'content', name: 'content' },
                    { data: 'user_id', name: 'user_id' },
                    { data: 'code', name: 'code' },
                    { data: 'type_product', name: 'type_product' },
                    { data: 'province_code', name: 'province_code' },
                    { data: 'ward_code', name: 'ward_code' },
                    { data: 'land_area', name: 'land_area' },
                    { data: 'cost', name: 'cost' },
                    { data: 'created_at', name: 'created_at' },
                ]
            });
        });

        table.ajax.reload()
    </script>
@endsection
