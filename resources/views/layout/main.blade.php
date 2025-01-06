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
      <style>
          /* Menyembunyikan scrollbar */
          ::-webkit-scrollbar {
              width: 0;  /* Menyembunyikan scrollbar untuk Chrome, Safari, dan Opera */
          }

          /* Menyembunyikan scrollbar untuk Firefox */
          html {
              scrollbar-width: none;
          }

          /* Menyembunyikan scrollbar untuk IE dan Edge */
          body {
              -ms-overflow-style: none;
          }

          /* Mengatur konten agar memiliki gulir sendiri */
          .scrollable-content {
              overflow-y: auto; /* Mengaktifkan gulir vertikal */
              max-height: calc(100vh - 100px); /* Maksimum tinggi sesuai viewport, dikurangi ruang untuk header/footer */
              padding-right: 15px; /* Memberi ruang agar konten tidak tertutup oleh scrollbar yang disembunyikan */
          }
      </style>
   </head>
  
   <body class="nav-md" style="background-color: #F7F7F7">
      <div class="container body">
         <div class="main_container">
            <div class="col-md-3 left_col" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
               @yield('menu-content')
            </div>
            @yield('navbar')

            <!-- Konten utama dengan scrollable-content -->
            <div class="scrollable-content">
               @yield('content')
            </div>
         </div>
      </div>

      <!-- Scripts -->
      <script src="{{ asset('asset/gentelella/vendors/jquery/dist/jquery.min.js')}}"></script>
      <script src="{{ asset('asset/gentelella/vendors/bootstrap/dist/js/bootstrap.min.js')}}"></script>
      <script src="{{ asset('asset/gentelella/vendors/fastclick/lib/fastclick.js')}}"></script>
      <script src="{{ asset('asset/gentelella/vendors/nprogress/nprogress.js')}}"></script>
      <script src="{{ asset('asset/gentelella/build/js/custom.min.js')}}"></script>
      <script>
         window.onload = function() {
            sessionStorage.setItem('statusLogin', 'sukses');
         };
      </script>
   </body>
</html>