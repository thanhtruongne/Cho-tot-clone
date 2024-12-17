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
        <!-- Button thêm mới người dùng -->
        <div class="mb-3">
            <button class="btn btn-purple btn-lg fw-bold shadow-lg rounded-5" id="addUserButton">Thêm người dùng</button>
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

                <button type="submit" class="btn" style="background: linear-gradient(45deg, #FF7E5F, #D83B01); color: white; border-radius: 50px; padding: 12px 24px; font-size: 16px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">Lưu</button>
<button type="button" class="btn" style="background: linear-gradient(45deg, #00C6FF, #0072FF); color: white; border-radius: 50px; padding: 12px 24px; font-size: 16px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);" id="cancelButton">Hủy</button>



            </form>
        </div>
    </div>



    {{-- hien thi --}}
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="productTable">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Firstname</th>
                    <th>Lastname</th>
                    <th>gender</th>
                    <th>Phone</th>
                    <th>status</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- Dữ liệu sẽ được tải bằng AJAX -->
            </tbody>
        </table>
    </div>
    </div>

@endsection

@section('scripts')
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('js/treeSelect.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            var table = $('#productTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('manage-users.data') }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'firstname',
                        name: 'firstname'
                    },
                    {
                        data: 'lastname',
                        name: 'lastname'
                    },
                    {
                        data: 'gender',
                        name: 'gender'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'action', // Đảm bảo cột Action được hiển thị
                        name: 'action',
                        orderable: false, // Không cần sắp xếp
                        searchable: false, // Không cần tìm kiếm
                        render: function(data, type, row) {
                            return data; // Trả về HTML của nút Action
                        }
                    }
                ]
            });
        });

        
        const addUserButton = document.getElementById('addUserButton');
        const addUserForm = document.getElementById('addUserForm');
        const cancelButton = document.getElementById('cancelButton');

        // Xử lý sự kiện khi bấm nút "Thêm người dùng"
        addUserButton.addEventListener('click', function() {
            addUserForm.style.display = 'block'; // Hiển thị form thêm người dùng
        });

        // Xử lý sự kiện khi bấm nút "Hủy"
        cancelButton.addEventListener('click', function() {
            addUserForm.style.display = 'none'; // Ẩn form thêm người dùng
        });

        // Kiểm tra sự khớp của mật khẩu và xác nhận mật khẩu
        document.getElementById('userForm').addEventListener('submit', function(event) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;

            if (password !== confirmPassword) {
                event.preventDefault(); // Ngừng gửi form nếu mật khẩu không khớp
                document.getElementById('passwordError').style.display = 'block'; // Hiển thị thông báo lỗi
            } else {
                document.getElementById('passwordError').style.display = 'none'; // Ẩn thông báo lỗi
            }
        });
    </script>
@endsection
