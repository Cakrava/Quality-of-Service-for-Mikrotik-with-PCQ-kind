<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Queue | Login</title>
    <link rel="icon" href="{{ asset('asset/image/logo2.png') }}" type="image/png">
    <link href="{{ asset('asset/gentelella/vendors/bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{ asset('asset/gentelella/vendors/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
    <link href="{{ asset('asset/gentelella/build/css/custom.min.css')}}" rel="stylesheet">
    <style>
        /* Layer Blur */
        .blur-background {
            filter: blur(8px);
        }

        /* Loader Style */
        #loader-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            display: none;
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }

        /* Loader Design */
        @keyframes ldio-x2uulkbinbj-1 {
            0% { top: 36px; height: 128px }
            50% { top: 60px; height: 80px }
            100% { top: 60px; height: 80px }
        }
        @keyframes ldio-x2uulkbinbj-2 {
            0% { top: 41.99999999999999px; height: 116.00000000000001px }
            50% { top: 60px; height: 80px }
            100% { top: 60px; height: 80px }
        }
        @keyframes ldio-x2uulkbinbj-3 {
            0% { top: 48px; height: 104px }
            50% { top: 60px; height: 80px }
            100% { top: 60px; height: 80px }
        }
        .ldio-x2uulkbinbj div {
            position: absolute;
            width: 30px;
        }
        .ldio-x2uulkbinbj div:nth-child(1) {
            left: 35px;
            background: #11685c;
            animation: ldio-x2uulkbinbj-1 1s cubic-bezier(0,0.5,0.5,1) infinite;
            animation-delay: -0.2s;
        }
        .ldio-x2uulkbinbj div:nth-child(2) {
            left: 85px;
            background: #11685c;
            animation: ldio-x2uulkbinbj-2 1s cubic-bezier(0,0.5,0.5,1) infinite;
            animation-delay: -0.1s;
        }
        .ldio-x2uulkbinbj div:nth-child(3) {
            left: 135px;
            background: #11685c;
            animation: ldio-x2uulkbinbj-3 1s cubic-bezier(0,0.5,0.5,1) infinite;
        }
        .loadingio-spinner-pulse-nq4q5u6dq7r {
            width: 200px;
            height: 200px;
            display: inline-block;
            overflow: hidden;
            background: #ffffff;
        }
        .ldio-x2uulkbinbj {
            width: 100%;
            height: 100%;
            position: relative;
            transform: translateZ(0) scale(1);
            backface-visibility: hidden;
            transform-origin: 0 0;
        }
        .ldio-x2uulkbinbj div { box-sizing: content-box; }
    </style>
</head>

<body class="login">
    <!-- Loader Overlay -->
    <div id="loader-overlay">
        <div class="loadingio-spinner-pulse-nq4q5u6dq7r">
            <div class="ldio-x2uulkbinbj">
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
    </div>

    <div id="main-content">
        <a class="hiddenanchor" id="signin"></a>
        <div class="login_wrapper">
            <div class="animate form login_form">
                <section class="login_content">
                    <div>
                        <img src="{{ asset('asset/image/logo1.png') }}" alt="Logo" style="width: auto; height: 120px; margin-bottom: 20px;">
                    </div>
                    <form action="{{ route('auth.proses_login') }}" method="post" onsubmit="showLoader()">
                        @csrf
                        <h1>Login Form</h1>
                        @error('login')
                        <div class="card" style="background-color: white; margin-bottom: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); padding : 15px ;justify-content : center; align-items : center">
                         
                            <p style="color : red">{{ $message }}</p>
                        </div>
                        @enderror
                        <div>
                            <input class="form-control" placeholder="Host atau IP Address" name="host" value="{{ old('host') }}" />
                        </div>
                        @error('host')
                        <span class="text-danger" style="font-size: small; display: block; margin-top: 1rem; text-align: left;">
                            {{ $message }}
                        </span>
                        @enderror

                        <br>

                        <div>
                            <input class="form-control" placeholder="Username" name="user" value="{{ old('user') }}" />
                        </div>
                        @error('user')
                        <span class="text-danger" style="font-size: small; display: block; margin-top: 1rem; text-align: left;">
                            {{ $message }}
                        </span>
                        @enderror

                        <br>

                        <div>
                            <input type="password" class="form-control" placeholder="Password" name="password" />
                        </div>
                        @error('password')
                        <span class="text-danger" style="font-size: small; display: block; margin-top: 1rem; text-align: left;">
                            {{ $message }}
                        </span>
                        @enderror

                        <br>

                        <div>
                            <button type="submit" class="btn btn-default submit">Masuk</button>
                        </div>

                        <div class="clearfix"></div>

                        <div class="separator">
                            <div class="clearfix"></div>
                            <br />
                            <div>
                                <p>©2024 Halaman Konfigurasi Mikrotik</p>
                                <p>Proyek Skripsi 2024</p>
                            </div>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </div>

    <script>
        function showLoader() {
            // Tampilkan loader
            document.getElementById('loader-overlay').style.display = 'flex';
            // Blur latar belakang
            document.getElementById('main-content').classList.add('blur-background');
        }
    </script>
</body>
</html>
