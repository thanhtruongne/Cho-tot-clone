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
<div class="container">
    <h2 class="mb-4">Danh sách người dùng</h2>
    <!-- Button thêm mới người dùng -->
    <div class="mb-3">
        <button class="btn btn-purple btn-lg fw-bold shadow-lg rounded-5" id="addUserButton">Thêm người dùng</button>
    </div>



    <!-- Form thêm người dùng  -->
    <div id="addUserForm" class="card p-4 mb-4" style="display: none;">
        <h5>Thêm người dùng mới</h5>
        <form action="{{ route('type-posting-add') }}" method="POST" id="userForm">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">name</label>
                <input type="name" class="form-control" name="name" name="name" value="{{ old('name') }}"
                    required>
                <!-- Hiển thị lỗi nếu email bị trùng -->
                @if ($errors->has('name'))
                    <div class="text-danger mt-1">{{ $errors->first('name') }}</div>
                @endif
            </div>

            <div class="mb-3">
                <label for="content" class="form-label">content</label>
                <input type="text" class="form-control" id="content" name="content"
                    value="{{ old('content') }}" required>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">status</label>
                <input type="text" class="form-control" id="status" name="status" value="{{ old('status') }}"
                    required>
            </div>

            <div class="mb-3">
                <label for="cost" class="form-label">cost</label>
                <input type="cost" class="form-control" id="cost" name="cost" required>
            </div>



            <button type="submit" class="btn" style="background: linear-gradient(45deg, #FF7E5F, #D83B01); color: white; border-radius: 50px; padding: 12px 24px; font-size: 16px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">Lưu</button>
<button type="button" class="btn" style="background: linear-gradient(45deg, #00C6FF, #0072FF); color: white; border-radius: 50px; padding: 12px 24px; font-size: 16px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);" id="cancelButton">Hủy</button>



        </form>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-bordered table-hover" id="productTable">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>name</th>
                <th>content</th>
                <th>status</th>
                <th>cost</th>
                <th>created_at</th>
                <th>action</th>
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
<script src="{{ asset('js/treeSelect.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            var table = $('#productTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('type-posting.data') }}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'content', name: 'content' },
                    { data: 'status', name: 'status' },
                    { data: 'cost', name: 'cost' },
                    { data: 'created_at', name: 'created_at' },
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
