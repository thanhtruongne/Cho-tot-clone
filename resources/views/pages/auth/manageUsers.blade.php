@extends('layouts.layouts')


@section('page_title')

@section('breadcrumbs')
    @php
        $breadcum = [
            [
                'name' => 'Quản lý sản phẩm bán',
                'url' => '/',
            ],
            [
                'name' => 'Sản phẩm bán',
                'url' => route('products.electronic'),
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
    <style>
        .bootstrap-table .fixed-table-container .table thead th.detail {
            width: 30px;

        }
    </style>

    <div class="container">
        <h2 class="mb-4">Danh sách người dùng</h2>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Hiển thị thông báo lỗi -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <!-- Button thêm mới người dùng -->
        <div class="mb-3">
            <button class="btn btn-info btn-lg fw-bold shadow-sm" id="addUserButton">Thêm người dùng</button>
        </div>
        <!-- Form thêm người dùng  -->
        <div id="addUserForm" class="card p-4 mb-4" style="display: none;">
            <h5>Thêm người dùng mới</h5>
            <form action="{{ route('manage-users-add') }}" method="POST" id="userForm">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}"
                        required>
                    <!-- Hiển thị lỗi nếu email bị trùng -->
                    @if ($errors->has('email'))
                        <div class="text-danger mt-1">{{ $errors->first('email') }}</div>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="firstname" class="form-label">Firstname</label>
                    <input type="text" class="form-control" id="firstname" name="firstname"
                        value="{{ old('firstname') }}" required>
                </div>

                <div class="mb-3">
                    <label for="lastname" class="form-label">Last name</label>
                    <input type="text" class="form-control" id="lastname" name="lastname" value="{{ old('lastname') }}"
                        required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <div class="mb-3">
                    <label for="confirmPassword" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                    <div id="passwordError" class="text-danger mt-1" style="display: none;">Mật khẩu không khớp</div>
                </div>

                <button type="submit" class="btn btn-primary">Lưu</button>
                <button type="button" class="btn btn-secondary" id="cancelButton">Hủy</button>
            </form>
        </div>
    </div>



    {{-- hien thi --}}
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>first name</th>
                <th>lastname</th>
                <th>Email</th>
                <th>Ngày sinh</th>
                <th>Giới tính</th>
                <th>Ngày đăng ký</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->username ?? 'N/A' }}</td>
                    <td>{{ $user->firstname }}</td>
                    <td>{{ $user->lastname }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->dob ? \Carbon\Carbon::parse($user->dob)->format('d/m/Y') : 'N/A' }}</td>
                    <td>{{ $user->gender == 1 ? 'Nam' : 'Nữ' }}</td>
                    <td>{{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                    <td>{{ $user->status == 1 ? 'Hoạt động' : 'Không hoạt động' }}</td>
                    <td>
                        <!-- Button cập nhật -->
                        <a href="{{ route('manage-users-edit',$user->id) }}" class="btn btn-warning btn-sm">Cập nhật</a>
                        <!-- Button xóa -->
                        <form action="{{ route('manage-users-delete',$user->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?')">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('js/treeSelect.min.js') }}"></script>
    <script>
        document.getElementById('addUserButton').addEventListener('click', function() {
            document.getElementById('addUserForm').style.display = 'block';
        });

        document.getElementById('cancelButton').addEventListener('click', function() {
            document.getElementById('addUserForm').style.display = 'none';
        });


        document.getElementById('userForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const passwordError = document.getElementById('passwordError');

            if (password !== confirmPassword) {
                e.preventDefault(); // Ngăn không cho gửi form
                passwordError.style.display = 'block'; // Hiển thị lỗi
            } else {
                passwordError.style.display = 'none'; // Ẩn lỗi nếu khớp
            }
        });

        function index_formatter(value, row, index) {
            console.log(row);
            return (index + 1);
        }

        function name_formatter(value, row, index) {
            return '<a class="overide" id="edit_' + row.id + '" href="#" onClick="edit(' + row.id + ')">' + row.name +
                '</a>';
        }

        function getModalCategory(value, row, index) {
            return '<a id="row_' + row.id + '" class="overide" href="#" onClick="getModal(' + row.id + ')">' + row
                .category_child + '</a>';
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
                            <input type="checkbox" ` + status + ` onclick="changeStatus(` + row.id +
                `)" class="custom-control-input" id="customSwitch_` + row.id + `">
                            <label class="custom-control-label" for="customSwitch_` + row.id + `"></label>
                        </div>`;
            return html;
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('categories.getdata') }}',
            remove_url: '{{ route('categories.remove', ['type' => 'all']) }}'
        });


        function changeStatus(status, type) {
            var ids = $("input[name=btSelectItem]:checked").map(function() {
                return $(this).val();
            }).get();
            let _this = $('#' + type);
            let html = _this.html();
            _this.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Đang xử lý...');
            if (ids.length <= 0) {
                show_message('Vui lòng chọn 1 dòng dữ liệu', 'error');
                return false;
            }
            $.ajax({
                url: '{{ route('categories.change.status') }}',
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
