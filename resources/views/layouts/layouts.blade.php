<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('page_title','Dashboard')</title>
    @include('layouts.components.link')
    @yield('links')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div id="wrapper">
        {{-- Loader --}}
        @include('layouts.components.loader')

        {{-- Navbar --}}
        @include('layouts.topmenu')

        {{-- Sidebar --}}
        @include('layouts.aside')

        {{-- Content --}}
        <div class="content-wrapper">
            <!-- breadcrumb -->
            <div class="content-header">
              <div class="container-fluid">
                  {{-- @include('layouts.components.breadcrumb') --}}
                  @yield('breadcrumbs')
              </div><!-- /.container-fluid -->
            </div>
            <!-- /.breadcrumb -->

            <!-- Main content -->
            <section class="content">
              <div class="container-fluid">
                @yield('content')
                {{-- theo row --}}
              </div>
            </section>
            <!-- /.content -->
        </div>

        {{-- footer --}}
        @include('layouts.footer')


    </div>

    @include('layouts.components.scirpts')
    <script>
      $(document).ready(function(){
        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var scrollTrigger = 60,
          backToTop = function() {

          };

        $('.bootstrap-table').removeClass('table-bordered');
        $(window).on('scroll', function() {
            backToTop();
        });


        $('body').on('click','#logout_data',function() {
          var btn = $(this),
          btn_text = btn.html();
          btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
          $.ajax({
              type: 'POST',
              url: '{{route('logout')}}',
              data : true,
          }).done(function(data){
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
    @yield('scripts')
</body>
</html>
