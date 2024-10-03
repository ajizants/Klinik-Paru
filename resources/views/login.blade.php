<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>KKPM - Login</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('vendor/plugins/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    {{-- <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet"> --}}
    <link rel="stylesheet" href="{{ asset('vendor/dist/css/adminlte.min.css') }}">
    <style>
        body {
            background: url("{{ asset('img/halaman kkpm.jpg') }}") no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
        }

        .container2 {
            position: absolute;
            top: 50%;
            left: 30%;
            transform: translate(-50%, -50%);
            scale: 1.2;
        }
    </style>

</head>

<body>
    <div class="container" style="height: 100vh">
        <div class="container2">
            <div class="card border-primary shadow-lg my-5" style="width: 30rem;">
                <div class="card-body transparent">
                    <div class="p-3">
                        <div class="text-center">
                            <img src="{{ asset('img/LOGO KKPM.jpg') }}" alt="Logo KKPM" width="50">
                            <h1 class="h4 text-gray-900 mb-4"><b>Selamat Datang di Aplikasi KKPM</b></h1>
                        </div>
                        <hr>
                        @if (session('error'))
                            <div class="alert alert-danger">
                                <b>Opps!</b> {{ session('error') }}
                            </div>
                        @endif
                        <form action="{{ route('actionlogin') }}" method="post" class="user">
                            @csrf
                            <div class="form-group">
                                <input type="email" name="email" class="form-control form-control-user"
                                    id="exampleInputEmail" aria-describedby="emailHelp"
                                    placeholder="Enter Email Address..."required="">
                            </div>
                            <div class="form-group">
                                <input type="password" name="password" class="form-control form-control-user"
                                    id="exampleInputPassword" placeholder="Password" required="">
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-checkbox small">
                                    <input type="checkbox" class="custom-control-input" id="customCheck">
                                    <label class="custom-control-label" for="customCheck">Remember
                                        Me</label>
                                </div>
                            </div>
                            <button type="submit" class="btn bg-gradient-primary btn-block">Login</button>
                            <hr>
                        </form>
                        <hr>
                        <div class="text-center">
                            <a class="small" href="forgot-password.html">Forgot Password?</a>
                        </div>
                        <div class="text-center">
                            <a class="small" href="register.html">Create an Account!</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('vendor/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('vendor/plugins/jquery-easing/jquery.easing.min.js') }}"></script>

</body>

</html>
