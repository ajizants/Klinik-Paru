{{-- @extends('Template.lte') --}}
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
    <link rel="stylesheet" href="{{ asset('vendor/plugins/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/panzoom/panzoom.css" />
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('vendor/dist/css/adminlte.min.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset('css/mystyle.css') }}">
    <!-- jQuery -->
    <script src="{{ asset('vendor/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('vendor/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('vendor/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('vendor/dist/js/adminlte.min.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/panzoom/panzoom.umd.js"></script>
    <script>
        var appUrlRo = @json($appUrlRo);

        async function cari() {
            Swal.fire({
                title: 'Loading',
                didOpen: () => {
                    Swal.showLoading();
                },
            });

            var norm = $('#norm').val().padStart(6, '0');

            try {
                const response = await fetch("/api/hasilRo", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        norm
                    }),
                });

                // Extract the JSON data even if the response is not OK
                const data = await response.json();

                if (!response.ok) {
                    // Show the error message from the response
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: data.message, // Extracted message from response
                    });
                    return; // Exit if data not found
                }

                const foto = data.data;
                show(foto); // Assuming show is a function to display the data
                Swal.close(); // Close any open SweetAlert if successful
            } catch (error) {
                // Catch and log any error that occurs during the fetch process
                console.error("Terjadi kesalahan saat mencari data:", error);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Terjadi kesalahan saat mencari data, silahkan coba lagi.',
                });
            }

        }

        function show(foto) {
            const preview = document.getElementById('preview');
            const buttondiv = document.getElementById('buttondiv');
            buttondiv.innerHTML = '';
            preview.innerHTML = '';

            if (!Array.isArray(foto) || foto.length === 0) {
                preview.innerHTML =
                    '<div class="carousel-item active"><img src="placeholder.jpg" class="d-block w-100" alt="No images available" style="width: 18rem;"></div>';
                return;
            }

            foto.forEach((item, index) => {
                const imageUrl = `${appUrlRo}${item.foto}`;
                const caption = `${item.norm} - ${item.nama} - ${item.tanggal}`;
                const cardid = `${item.id}_${item.tanggal}`;
                const panzoomid = `myPanzoom${item.id}`;
                const buttonid = `btn${item.id}`;

                const button =
                    `<a type="button" class="btn btn-primary btn-sm mx-3" id="${buttonid}" onclick="toggleImage('${cardid}', '${buttonid}')">Foto Tanggal: ${item.tanggal}</a>`;

                const card = `
            <div class="col-6 gallery" id=${cardid} style="display:none; ">
                <div class="card m-2" style="cursor: pointer; height: 700px;">
                    <div class="f-panzoom" id="${panzoomid}"style=" height: 700px;">
                        <div class="f-custom-controls top-right">
                            <button data-panzoom-action="toggleFS" class="toggleFullscreen">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <g>
                                        <path d="M14.5 9.5 21 3m0 0h-6m6 0v6M3 21l6.5-6.5M3 21v-6m0 6h6" />
                                    </g>
                                    <g>
                                        <path d="m14 10 7-7m-7 7h6m-6 0V4M3 21l7-7m0 0v6m0-6H4" />
                                    </g>
                                </svg>
                            </button>
                        </div>
                        <div class="f-custom-controls bottom-right">
                            <button data-panzoom-change='{"angle": 90}'>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M9 4.55a8 8 0 0 1 6 14.9M15 15v5h5M5.63 7.16v.01M4.06 11v.01M4.63 15.1v.01M7.16 18.37v.01M11 19.94v.01" />
                                </svg>
                            </button>
                            <button data-panzoom-action="zoomIn">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 5v14M5 12h14" />
                                </svg>
                            </button>
                            <button data-panzoom-action="zoomOut">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M5 12h14" />
                                </svg>
                            </button>
                        </div>
                        <img class="f-panzoom__content" id="zoomed-image" src="${imageUrl}" />
                    </div>
                    <div class="card-footer">
                        <h5 class="text-center">${item.norm} - ${item.nama}</h5>
                        <h5 class="text-center">Tanggal: ${item.tanggal}</h5>
                    </div>
                </div>
            </div>`;

                buttondiv.insertAdjacentHTML('beforeend', button);
                preview.insertAdjacentHTML('beforeend', card);

                const container = document.getElementById(panzoomid);
                const options = {
                    click: "toggleCover",
                    Toolbar: {
                        display: ["zoomIn", "zoomOut"],
                    },
                };

                new Panzoom(container, options);
            });
        }

        function toggleImage(id, buttonid) {
            const card = document.getElementById(id);
            const button = document.getElementById(buttonid);

            if (card) {
                if (card.style.display === 'block') {
                    // Hide the card
                    card.style.display = 'none';
                    // button.style.opacity = '0';
                    button.classList.remove('btn-success'); // Remove the success class
                    button.classList.add('btn-primary'); // Add the primary class back
                } else {
                    // Show the card
                    card.style.display = 'block';
                    // button.style.opacity = '1';
                    button.classList.remove('btn-primary'); // Remove the existing class
                    button.classList.add('btn-success'); // Add the success class
                }
            }
        }




        function openPanZoom(imageUrl, caption, panzoomid) {
            const modal = document.getElementById('exampleModal');
            const zoomedImage = document.getElementById('zoomed-image');
            const captionId = document.getElementById('caption');
            const container = document.getElementById("myPanzoom");
            const options = {
                click: "toggleCover",
                Toolbar: {
                    display: ["zoomIn", "zoomOut"],
                },
            };

            zoomedImage.src = imageUrl;
            captionId.innerHTML = caption;

            new Panzoom(container, options, {
                // Toolbar
            });
        }
        document.addEventListener("DOMContentLoaded", function() {
            var tglTransInput = document.getElementById("waktu");

            function updateDateTime() {
                var now = new Date();
                var options = {
                    timeZone: "Asia/Jakarta",
                    year: "numeric",
                    month: "2-digit",
                    day: "2-digit",
                    hour: "2-digit",
                    minute: "2-digit",
                    second: "2-digit",
                };
                // var formattedDate = now.toLocaleString("id-ID", options);
                let tglnow = now
                    .toLocaleString("id-ID", options)
                    .replace(
                        /(\d{4})\D(\d{2})\D(\d{2})\D(\d{2})\D(\d{2})\D(\d{2})/,
                        "$1-$2-$3 $4.$5.$6"
                    );

                tglTransInput.value = tglnow;
            }
            setInterval(updateDateTime, 1000);
        });
    </script>
    <style>
        .f-custom-controls {
            position: absolute;

            border-radius: 4px;
            overflow: hidden;
            z-index: 1;
        }

        .f-custom-controls.top-right {
            right: 16px;
            top: 16px;
        }

        .f-custom-controls.bottom-right {
            right: 16px;
            bottom: 16px;
        }

        .f-custom-controls button {
            width: 32px;
            height: 32px;
            background: none;
            border: none;
            margin: 0;
            padding: 0;
            background: #222;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .f-custom-controls svg {
            pointer-events: none;
            width: 18px;
            height: 18px;
            stroke: #fff;
            stroke-width: 2;
        }

        .f-custom-controls button[disabled] svg {
            opacity: 0.7;
        }

        [data-panzoom-action=toggleFS] g:first-child {
            display: flex
        }

        [data-panzoom-action=toggleFS] g:last-child {
            display: none
        }

        .in-fullscreen [data-panzoom-action=toggleFS] g:first-child {
            display: none
        }

        .in-fullscreen [data-panzoom-action=toggleFS] g:last-child {
            display: flex
        }
    </style>
</head>

<body class="sidebar-mini layout-fixed control-sidebar-slide-open sidebar-collapse text-sm">
    <div class="wrapper">

        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="{{ asset('img/LOGO_KKPM.png') }}" alt="KKPM-Logo" height="200"
                width="200">
            <span><b>Versi</b> {{ env('APP_LARAVEL_VERSION') }}</span>
        </div>
        <nav class="main-header navbar navbar-expand navbar-light bg-white font-weight-bold">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="home" class="nav-link" id="top">Home</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="http://rsparu.kkpm.local" target="_blank"" class="nav-link">RS Paru</a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Navbar Search -->
                <li class="class="nav-item form-inline"">
                    <label for="waktu" class="font-weight-bold mb-0 mr-2">Waktu
                        :</label>
                    <input type="text" id="waktu" class="bg-white border border-white " readonly />
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                        <i class="fas fa-search"></i>
                    </a>
                    <div class="navbar-search-block">
                        <form class="form-inline">
                            <div class="input-group input-group-sm">
                                <input class="form-control form-control-navbar" type="search" placeholder="Search"
                                    aria-label="Search">
                                <div class="input-group-append">
                                    <button class="btn btn-navbar" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </li>

                <!-- Messages Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="fa-solid fa-user"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <a href="#" class="dropdown-item">
                            <!-- Message Start -->
                            <div class="media">
                                <img src="{{ asset('img/user1.webp') }}" alt="User Avatar"
                                    class="img-size-50 mr-3 img-circle">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        <b>Guest</b>
                                        <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                                    </h3>
                                    <p class="text-sm">Wis Rampung Lik...?</p>
                                </div>
                            </div>
                            <!-- Message End -->
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                            Logout
                        </a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
            </ul>
        </nav>

        <aside class="main-sidebar sidebar-dark-navy elevation-4">
            <!-- Brand Logo -->
            <a href="" class="brand-link">
                <img src="{{ asset('img/LOGO KKPM.jpg') }}" alt="KKPM Logo"
                    class="bg bg-light brand-image img-circle elevation-3" width="30" height="30">
                <span class="brand-text font-weight-light text-md"><b>KKPM</b></span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar bg-navy font-weight-bold">
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <!-- Dashboard -->
                        <li class="nav-item">
                            <a href="{{ url('home') }}" class="nav-link">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-header">TRANSAKSI</li>
                        <!-- IGD Section -->
                        <li class="nav-item">
                            <a href="" class="nav-link">
                                <i class="fa-solid fa-truck-medical nav-icon"></i>
                                <p>
                                    Ruang Tindakan
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview" style="display: none;">
                                <li class="nav-item ml-4">
                                    <a class="nav-link" href="{{ url('/igd') }}" id="masukIGD">
                                        <i class="nav-icon fas fa-edit"></i>
                                        <p>Input Tindakan</p>
                                    </a>
                                </li>

                                <li class="nav-item ml-4">
                                    <a class="nav-link" href="{{ url('/gudangIGD') }}">
                                        <i class="fa-solid fa-database nav-icon"></i>
                                        <p>Master Tindakan</p>
                                    </a>
                                </li>

                                <li class="nav-item ml-4">
                                    <a class="nav-link" href="{{ url('/askep') }}">
                                        <i class="fa-solid fa-file-pen nav-icon"></i>
                                        <p>ASKEP</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="" class="nav-link">
                                <i class="nav-icon fa-solid fa-hand-holding-medical"></i>
                                <p>
                                    Dots Center
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview" style="display: none;">
                                <li class="nav-item ml-4">
                                    <a class="nav-link" href="{{ url('/dots') }}">
                                        <i class="nav-icon fas fa-edit"></i>
                                        <p>Input Dots Center</p>
                                    </a>
                                </li>
                                <li class="nav-item ml-4">
                                    <a class="nav-link" href="{{ url('/dots_master') }}">
                                        <i class="fa-solid fa-database nav-icon"></i>
                                        <p>Master Dots Center</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- Gizi Section -->
                        <li class="nav-item">
                            <a href="" class="nav-link">
                                {{-- <i class="fa-solid fa-pills nav-icon"></i> --}}
                                <i class="fa-brands fa-nutritionix  nav-icon"></i>
                                <p>
                                    Gizi
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview" style="display: none;">
                                <li class="nav-item ml-4">
                                    <a class="nav-link" href="{{ url('/gizi') }}">
                                        <i class="nav-icon fas fa-edit"></i>
                                        <p>Input Gizi</p>
                                    </a>
                                </li>
                                <li class="nav-item ml-4">
                                    <a class="nav-link" href="{{ url('/masterGizi') }}">
                                        <i class="fa-solid fa-database nav-icon"></i>
                                        <p>Master Gizi</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- Farmasi Section -->
                        <li class="nav-item">
                            <a href="" class="nav-link">
                                <i class="fa-solid fa-pills nav-icon"></i>
                                <p>
                                    Farmasi
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview" style="display: none;">
                                <li class="nav-item ml-4">
                                    <a class="nav-link" href="{{ url('/farmasi') }}">
                                        <i class="nav-icon fas fa-edit"></i>
                                        <p>Input Farmasi</p>
                                    </a>
                                </li>
                                <li class="nav-item ml-4">
                                    <a class="nav-link" href="{{ url('/gudangFarmasi') }}">
                                        <i class="fa-solid fa-database nav-icon"></i>
                                        <p>Master Farmasi</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- lab Section -->
                        <li class="nav-item">
                            <a href="" class="nav-link">
                                <i class="fa-solid fa-microscope nav-icon"></i>
                                <p>
                                    Laboratorium
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview" style="display: none;">
                                <li class="nav-item ml-4">
                                    <a class="nav-link" href="{{ url('/lab') }}">
                                        {{-- <i class="fa-regular fa-address-card nav-icon"></i> --}}
                                        <i class="fa-solid fa-user-pen nav-icon"></i>
                                        <p>Pendaftaran Lab</p>
                                    </a>
                                </li>
                                <li class="nav-item ml-4">
                                    <a class="nav-link" href="{{ url('/hasilLab') }}">
                                        <i class="nav-icon fas fa-edit"></i>
                                        <p>Input Hasil Lab</p>
                                    </a>
                                </li>

                                <li class="nav-item ml-4">
                                    <a class="nav-link" href="{{ url('/masterLab') }}">
                                        <i class="fa-solid fa-database nav-icon"></i>
                                        <p>Master Lab</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- RO Section -->
                        <li class="nav-item">
                            <a href="" class="nav-link">
                                <i class="nav-icon fa-solid fa-circle-radiation"></i>
                                <p>
                                    Radiologi
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview" style="display: none;">
                                <li class="nav-item ml-4">
                                    <a class="nav-link" href="{{ url('/ro') }}">
                                        {{-- <i class="fa-regular fa-id-card nav-icon"></i> --}}
                                        <i class="fa-regular nav-icon fas fa-edit"></i>
                                        <p>Input Radiologi</p>
                                    </a>
                                </li>
                                <li class="nav-item ml-4">
                                    <a class="nav-link" href="{{ url('/masterRo') }}">
                                        <i class="fa-solid fa-database nav-icon"></i>
                                        <p>Master Radiologi</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- Kasir Section -->
                        {{-- @if (auth()->user()->role === 'kasir' || auth()->user()->role === 'admin') --}}
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/kasir') }}">
                                <i class="fa-solid fa-cash-register nav-icon"></i>
                                <p>Kasir</p>
                            </a>
                        </li>
                        {{-- @endif --}}

                        {{-- LAPORAN --}}
                        <li class="nav-header">LAPORAN</li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/RO/Hasil') }}">
                                <i class="fa-solid fa-x-ray nav-icon"></i>
                                <p>Hasil RO</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/report') }}">
                                <i class="fa-solid fa-chart-column nav-icon"></i>
                                <p>Laporan Petugas</p>
                            </a>
                        </li>
                        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Farmasi">
                            <a class="nav-link" href="{{ url('/riwayatGizi') }}">
                                <i class="fa-solid fa-chart-column nav-icon"></i>
                                <p>Riwayat Transaksi Gizi</p>
                            </a>
                        </li>
                        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Farmasi">
                            <a class="nav-link" href="{{ url('/logFarmasi') }}">
                                <i class="fa-solid fa-chart-column nav-icon"></i>
                                <p>Riwayat Transaksi Farmasi</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/riwayatRo') }}">
                                <i class="fa-solid fa-chart-column nav-icon"></i>
                                <p>Laporan Radiologi</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/riwayatLab') }}">
                                <i class="fa-solid fa-chart-column nav-icon"></i>
                                <p>Laporan Laboratorium</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/Laporan/Pendaftaran') }}">
                                <i class="fa-solid fa-chart-column nav-icon"></i>
                                <p>Laporan Pendaftaran</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/Riwayat/Pasien') }}">
                                <i class="fa-solid fa-book-medical nav-icon"></i>
                                <p>Riwayat Pasien</p>
                            </a>
                        </li>
                        <li class="nav-item"
                            style="margin-top: 100px;>
                            <a class="nav-link" href="#">

                            </a>
                        </li>

                    </ul>
                </nav>

                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <div class="content-wrapper" id="topSection">
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

            <section class="content">
                <div class="container-fluid">
                    @include('RO.Hasil.input')
                </div>
            </section>
        </div>

        <!-- /.footer -->
        <footer class="main-footer">
            <strong>Copyright &copy; {{ date('Y') }} <a href="#">Klinik Utama Kesehatan Paru Masyarakat
                    Kelas
                    A</a>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b>Versi</b> {{ env('APP_LARAVEL_VERSION') }}
            </div>
        </footer>


        <!-- Logout Modal-->
        <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalLabel">Ready to Leave?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">Select "Logout" below if you are ready to end your current session.
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <a class="btn btn-primary" href="{{ route('actionlogout') }}">Logout</a>
                    </div>
                </div>
            </div>
        </div>


    </div>

</body>

</html>
