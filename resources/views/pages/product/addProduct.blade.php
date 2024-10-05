@extends('layouts.layouts')


@section('page_title')

@section('breadcrumbs')
    @php
        $breadcum = [
            [
                'name' => 'Them san pham',
                'url' => '/',
            ],
        ];

    @endphp
    <div class="row mb-3 mt-2 bg-white">
        <div class="col-md-12 px-0">
            @include('layouts.components.breadcrumb', $breadcum)
        </div>
    </div>

@endsection

@section('content')
    <div class="row bg-white backend-container pt-3" style="margin-left: -15px;margin-right:-15px">
        <div class="col-md-12 pb-3">
            <div class="">
                <div class="row">
                    {{-- Form nhập sản phẩm --}}
                    <div class="col-md-12">
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                        <form action="{{ route('products.add') }}" method="post" class="w-100 mb-3" id="form-add-product"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="title">Tiêu đề</label>
                                <input type="text" name="title" class="form-control" placeholder="Tiêu đề sản phẩm">
                            </div>
                            <div class="form-group">
                                <label for="content">Nội dung</label>
                                <textarea name="content" class="form-control" rows="3" placeholder="Nội dung sản phẩm"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="user_id">User ID</label>
                                <input type="text" name="user_id" class="form-control" placeholder="User ID">
                            </div>
                            <div class="form-group">
                                <label for="code">Mã sản phẩm</label>
                                <input type="text" name="code" class="form-control" placeholder="Mã sản phẩm">
                            </div>
                            <div class="form-group">
                                <label for="images">Hình ảnh</label>
                                <input type="file" name="images" class="form-control" multiple>
                            </div>
                            <div class="form-group">
                                <label for="video">Video</label>
                                <input type="file" name="video" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="category_id">Danh mục</label>
                                <select name="category_id" class="form-control select2"
                                    data-placeholder="-- Chọn danh mục sản phẩm --">
                                    {{-- chen database vao  --}}
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="type_posting_id">Loại tin đăng</label>
                                <select name="type_posting_id" class="form-control select2"
                                    data-placeholder="-- Chọn loại tin đăng --">

                                    {{-- chen database vao  --}}
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="approved">Trạng thái duyệt</label>
                                <select name="approved" class="form-control select2">
                                    <option value="0">Từ chối</option>
                                    <option value="1">Đã duyệt</option>
                                    <option value="2">Chờ duyệt</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="status">Trạng thái</label>
                                <input type="text" name="status" class="form-control" placeholder="Trạng thái sản phẩm">
                            </div>
                            <div class="form-group">
                                <label for="province_code">Mã tỉnh</label>
                                <input type="text" name="province_code" class="form-control" placeholder="Mã tỉnh">
                            </div>
                            <div class="form-group">
                                <label for="district_code">Mã quận/huyện</label>
                                <input type="text" name="district_code" class="form-control" placeholder="Mã quận/huyện">
                            </div>
                            <div class="form-group">
                                <label for="ward_code">Mã phường/xã</label>
                                <input type="text" name="ward_code" class="form-control" placeholder="Mã phường/xã">
                            </div>
                            <div class="form-group">
                                <label for="condition_used">Tình trạng sử dụng</label>
                                <select name="condition_used" class="form-control select2">
                                    <option value="1">Mới</option>
                                    <option value="2">Đã sử dụng (chưa sửa chữa)</option>
                                    <option value="3">Đã sử dụng (đã sửa chữa)</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="cost">Giá</label>
                                <input type="text" name="cost" class="form-control" placeholder="Giá sản phẩm">
                            </div>
                            <div class="form-group">
                                <label for="brand_id">Thương hiệu</label>
                                <select name="brand_id" class="form-control select2">
                                    {{-- Danh sách thương hiệu --}}
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="color_id">Màu sắc</label>
                                <select name="color_id" class="form-control select2">
                                    {{-- Danh sách màu sắc --}}
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="capacity_id">Dung lượng</label>
                                <select name="capacity_id" class="form-control select2">
                                    {{-- Dung lượng --}}
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="warrancy_policy_id">Chính sách bảo hành</label>
                                <select name="warrancy_policy_id" class="form-control select2">
                                    {{-- Chính sách bảo hành --}}
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="origin_from_id">Xuất xứ</label>
                                <select name="origin_from_id" class="form-control select2">
                                    {{-- Xuất xứ --}}
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="screen_size_id">Kích thước màn hình</label>
                                <select name="screen_size_id" class="form-control select2">
                                    {{-- Kích thước màn hình --}}
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="microprocessor_id">Bộ vi xử lý</label>
                                <select name="microprocessor_id" class="form-control select2">
                                    {{-- Bộ vi xử lý --}}
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="ram_id">RAM</label>
                                <select name="ram_id" class="form-control select2">
                                    {{-- RAM --}}
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="hard_drive_id">Ổ cứng</label>
                                <select name="hard_drive_id" class="form-control select2">
                                    {{-- Ổ cứng --}}
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="type_hard_drive">Loại ổ cứng</label>
                                <select name="type_hard_drive" class="form-control select2">
                                    <option value="1">HDD</option>
                                    <option value="2">SSD</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="card_screen_id">Card màn hình</label>
                                <select name="card_screen_id" class="form-control select2">
                                    {{-- Card màn hình --}}
                                </select>
                            </div>
                            <button type="submit">Lưu sản phẩm</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        function status_formatter(value, row, index) {
            var status = row.status == 1 ? 'checked' : '';
            var html = `<div class="custom-control custom-switch">
                            <input type="checkbox" ` + status + ` onclick="changeStatus(` + row.id +
                `)" class="custom-control-input" id="customSwitch_` + row.id + `">
                            <label class="custom-control-label" for="customSwitch_` + row.id + `"></label>
                        </div>`;
            return html;
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('categories.getdata') }}',
            remove_url: '{{ route('categories.remove') }}'
        });

        function changeStatus(status) {
            var ids = $("input[name=btSelectItem]:checked").map(function() {
                return $(this).val();
            }).get();
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
