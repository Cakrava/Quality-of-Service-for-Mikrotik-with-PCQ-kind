

<!DOCTYPE html>
<html lang="en">
   @include('Component.include_component')
   <head>
  
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  
    <!-- Title -->
    <title>Queue| Qos of Service</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('asset/image/logo2.png') }}" type="image/png">
  
    <!-- Stylesheets -->
    <link href="{{ asset('asset/gentelella/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/gentelella/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/gentelella/vendors/nprogress/nprogress.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/gentelella/vendors/iCheck/skins/flat/green.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/gentelella/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/gentelella/vendors/jqvmap/dist/jqvmap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/gentelella/vendors/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/gentelella/build/css/custom.min.css') }}" rel="stylesheet">
  </head>
  
  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          @yield('menu-content')
        </div>

        <!-- top navigation -->
   
        <!-- /top navigation -->
        @yield('navbar')

        <!-- page content -->
  
        @yield('content')
        <!-- /page content -->
      

        <div class="clearfix"></div>
        <!-- footer content -->
       
        <!-- /footer content -->
      </div>
    </div>

 
        <!-- Bootstrap 4 CDN -->
      <!-- jQuery -->
<script src="{{ asset('asset/gentelella/vendors/jquery/dist/jquery.min.js')}}"></script>
<!-- Bootstrap -->
<script src="{{ asset('asset/gentelella/vendors/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<!-- FastClick -->
<script src="{{ asset('asset/gentelella/vendors/fastclick/lib/fastclick.js')}}"></script>
<!-- NProgress -->
<script src="{{ asset('asset/gentelella/vendors/nprogress/nprogress.js')}}"></script>

<!-- Custom Theme Scripts -->

    <script src="{{ asset('asset/gentelella/build/js/custom.min.js')}}"></script>
  <script>
    window.onload = function() {
      sessionStorage.setItem('statusLogin', 'sukses');
    };
  </script>
  </body>
</html>
