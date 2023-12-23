<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>KKPM | {{ isset($title) ? $title : '' }}</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('vendor/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('vendor/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('vendor/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('vendor/dist/css/adminlte.min.css') }}">
    <style>
        .hidden-time {
            display: none;
        }

        .bg-teal,
        .bg-teal>a {
            color: #1f2d3d !important;
        }

        .input-sm {
            width: 60px;
            height: 28px;
        }

        .z-5 {
            z-index: 5;
        }

        input,
        input::-webkit-input-placeholder {
            font: 1rem;
        }


        .position-absolute {
            position: absolute;
            left: 50%;
            top: 45px;
            transform: translateX(-50%);
        }

        .aksi-button:hover i {
            font-size: 17px;
        }

        .delete {
            color: red;
        }

        .edit:hover i {
            font-size: 17px;
        }

        .delete:hover i {
            font-size: 17px;
            color: red;
        }
    </style>

</head>

<body class="sidebar-mini layout-fixed control-sidebar-slide-open sidebar-collapse text-sm">
    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="{{ asset('img/LOGO_KKPM.png') }}" alt="KKPM-Logo" height="200"
                width="200">
            <span><b>Versi</b> {{ env('APP_LARAVEL_VERSION') }}</span>
        </div>

        <!-- Navbar -->
        @include('Tamplate.navbar')
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        @include('Tamplate.sidebar')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper" id="topSection">
            <!-- Content Header (Page header) -->
            <div class="content-header py-0">
                <div class="container-fluid">
                    <div class="row d-flex justify-content-end">
                        {{-- <div class="col-sm-6">
                            <h1 class="m-0">{{ isset($title) ? $title : '' }}</h1>
                        </div><!-- /.col --> --}}
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="home">Home</a></li>
                                <li class="breadcrumb-item active">{{ isset($title) ? $title : '' }}</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    @yield('content')



</body>

</html>
