<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đăng nhập vào hệ thống</title>

    <link rel="stylesheet" href="{{asset('plugins//fontawesome-free/css/all.min.css')}}">
    <!-- icheck bootstrap -->
    {{-- <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}"> --}}
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('css/adminlte.css')}}">
    {{-- <link rel="stylesheet" href="{{asset()}}"> --}}
</head>
<body class="hold-transition login-page">
    <div class="login-box">
      <!-- /.login-logo -->
      <div class="card card-outline card-primary">
        <div class="card-header text-center">
          <a href="#" class="h1"><b>Hệ thống</b></a>
        </div>
        <div class="card-body">
          <p class="login-box-msg">Đăng nhập vào hệ thống quản lý</p>
    
          <form action="../../index3.html" method="post">
            <div class="input-group mb-3">
              <input type="text" class="form-control" name="username" placeholder="-- Tài khoản --">
              <div class="input-group-append">
                <div class="input-group-text">
                    <i class="fas fa-user"></i>
                </div>
              </div>
            </div>
            <div class="input-group mb-3">
              <input type="password" class="form-control" name="password" placeholder="-- Mật khẩu --">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-lock"></span>
                </div>
              </div>
            </div>
            <div class="row">
              <!-- /.col -->
              <div class="col-5">
                <button type="btn" class="btn btn-primary btn-block save-login">Đăng nhập</button>
              </div>
              <!-- /.col -->
            </div>
          </form>  
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
    <!-- /.login-box -->
    
 </body>
  <!-- jQuery -->
  <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
  <script src="{{asset('plugins/sweetalert2/sweetalert2.all.min.js')}}"></script>
  <!-- Bootstrap 4 -->
  <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <!-- AdminLTE App -->
  <script src="{{asset('js/adminlte.min.js')}}"></script>

  <script src="{{asset('js/backend2.js')}}"></script>

  <script>
        $.ajaxSetup({
        headers: {
           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
  </script>
<script type="text/javascript">
  $(document).ready(function(){

      $('.save-login').on('click',function(){
        var btn = $(this),
        btn_text = btn.html();
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
        $.ajax({
            type: 'POST',
            url: '{{route('post.login')}}',
            data : {
                'username' : $('input[name="username"]').val(),
                'password' : $('input[name="password"]').val(),
            }
        }).done(function(data){
          console.log(data);
            btn.prop('disabled', false).html(btn_text);
            if(data){
                if(data.status == 'error'){
                    show_message(data?.message, data?.status);
                    return false;
                }
                else {
                  show_message(data?.message, data?.status);
                  window.location.href = data.redirect;
                }
            }
        }).fail(function(data) {
            btn.prop('disabled', false).html(btn_text);
            show_message('Lỗi hệ thống', 'error');
            return false;
        });
      })
    
  })

</script>

</html>
