
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Queue | Login </title>
    <link rel="icon" href="{{ asset('asset/image/logo2.png') }}" type="image/png">
  
    <!-- Bootstrap -->
    <link href="{{ asset('asset/gentelella/vendors/bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ asset('asset/gentelella/vendors/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
    <!-- NProgress -->
    <link href="{{ asset('asset/gentelella/vendors/nprogress/nprogress.css')}}" rel="stylesheet">
    <!-- Animate.css -->
    <link href="{{ asset('asset/gentelella/vendors/animate.css/animate.min.css')}}" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="{{ asset('asset/gentelella/build/css/custom.min.css')}}" rel="stylesheet">
  </head>

  
  <body class="login">
    <div>
      <a class="hiddenanchor" id="signin"></a>

      <div class="login_wrapper">
        
        <div class="animate form login_form">
          
          <section class="login_content">
            <div>
              <img src="{{ asset('asset/image/logo1.png') }}" alt="Logo" style="width: auto; height: 120px; margin-bottom: 20px;">
            </div>
            <form action="{{ route('auth.proses_login') }}" method="post">
              @csrf
              
              <h1>Login Form</h1>
             
              <div>
                <input  class="form-control" placeholder="Nomor atau Email" name="login" />
              </div>
              @error('login')
                  <span class="text-danger" style="font-size: small; display: block; margin-top: 1rem;  text-align: left;">{{ $message }}</span>
                @enderror
              <br>
              <div>
                <input type="password" class="form-control" placeholder="Password" name="password" />
                 @error('password')
                  <span class="text-danger" style="font-size: small; display: block; margin-top: 1rem;  text-align: left;">{{ $message }}</span>
                @enderror
              </div>
              
              <br>
        
              
             
        
              <div>
                <button type="submit" class="btn btn-default submit">Masuk</button>
                
              </div>
              

              <div class="clearfix"></div>

              <div class="separator">
                <div class="clearfix"></div>
                <br />
               
                <div>
                  <h1><i class="fa fa-cogs"></i> Mikrotik Configuration</h1>
                  <p>©2024 Halaman Konfigurasi Mikrotik</p>
                  <p>Proyek Skripsi 2024</p>
                </div>
              </div>
            </form>
          </section>
        </div>
      </div>
    </div>
  </body>
</html>
