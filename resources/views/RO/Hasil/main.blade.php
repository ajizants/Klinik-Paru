{{-- @extends('Template.lte') --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>KKPM | {{ isset($title) ? $title : '' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@0,400..800;1,400..800&display=swap"
        rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('vendor/plugins/fontawesome-free/css/all.min.css') }}">
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
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('vendor/dist/css/adminlte.min.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset('css/mystyle.css') }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/panzoom/panzoom.css" />
    {{-- Scripting --}}
    <!-- jQuery -->
    <script src="{{ asset('vendor/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('vendor/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('vendor/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('vendor/plugins/select2/js/select2.full.min.js') }}"></script>
    <!-- InputMask -->
    <script src="{{ asset('vendor/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('vendor/plugins/inputmask/jquery.inputmask.min.js') }}"></script>
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('vendor/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('vendor/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('vendor/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('vendor/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('vendor/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('vendor/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('vendor/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('vendor/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('vendor/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('vendor/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('vendor/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('vendor/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('vendor/plugins/daterangepicker/daterangepicker.js') }}"></script>
    {{-- confetti --}}
    <script src="{{ asset('vendor/plugins/confetti/confetti.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('vendor/dist/js/adminlte.min.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/panzoom/panzoom.umd.js"></script>
    <script>
        var appUrlRo = @json($appUrlRo);
        var hasilRo = @json($hasilRo);
        var hasilLab = @json($hasilLab);

        async function cari() {
            const preview = document.getElementById('preview');
            const buttondiv = document.getElementById('buttondiv');
            buttondiv.innerHTML = '';
            preview.innerHTML = '';
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
                    const preview = document.getElementById('preview');
                    const info = `
                            <div class="col d-flex justify-content-center h2">
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h2><i class="icon fas fa-ban"></i> Alert!</h2>
                                    ${data.message}
                                </div>
                            </div>
                            `;
                    preview.innerHTML = info;
                    Swal.close();
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

        async function cariLab() {
            const previewLab = document.getElementById('previewLab');
            previewLab.innerHTML = '';
            Swal.fire({
                title: 'Loading',
                didOpen: () => {
                    Swal.showLoading();
                },
            });

            var norm = $('#norm').val().padStart(6, '0');

            try {
                const response = await fetch("/api/hasil/lab", {
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
                    if ($.fn.DataTable.isDataTable("#reportKunjungan")) {
                        var tabel = $("#reportKunjungan").DataTable();
                        tabel.clear().destroy();
                        $("#reportKunjungan thead").remove();
                        $("#reportKunjungan tbody").remove();
                    }
                    const previewLab = document.getElementById('previewLab');
                    const info = `
                            <div class="col d-flex justify-content-center h2">
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h2><i class="icon fas fa-ban"></i> Alert!</h2>
                                    ${data}
                                </div>
                            </div>
                            `;
                    previewLab.innerHTML = info;
                    return; // Exit if data not found
                }

                showHasilLab(data); // Assuming show is a function to display the data
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
                    `<a type="button" class="btn btn-primary btn-sm mx-3 mt-3" id="${buttonid}" onclick="toggleImage('${cardid}', '${buttonid}')">Foto Tanggal: ${item.tanggal}</a>`;

                const card = `
                                <div class="col gallery" id=${cardid} style="display:none; ">
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
                                </div>
                            `;

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

        function showHasilLab(hasilLab) {
            if ($.fn.DataTable.isDataTable("#reportKunjungan")) {
                var tabel = $("#reportKunjungan").DataTable();
                tabel.clear().destroy();
                $("#reportKunjungan thead").remove();
                $("#reportKunjungan tbody").remove();
            }

            // Format date to dd-mm-yyyy
            function formatDate(dateStr) {
                var date = new Date(dateStr);
                var day = String(date.getDate()).padStart(2, '0');
                var month = String(date.getMonth() + 1).padStart(2, '0'); // January is 0!
                var year = date.getFullYear();
                return `${day}-${month}-${year}`;
            }

            // Grouping data by date
            var groupedData = {};
            hasilLab.forEach(function(item) {
                var dateKey = formatDate(item.created_at);
                if (!groupedData[dateKey]) {
                    groupedData[dateKey] = Object.assign({}, item, {
                        pemeriksaan: {}
                    });
                }
                // Merge pemeriksaan results into the group
                groupedData[dateKey].pemeriksaan[item.pemeriksaan.nmLayanan] = item.hasil;
            });

            // Convert groupedData into an array for DataTable
            var dataTableData = Object.keys(groupedData).map(function(dateKey) {
                var item = groupedData[dateKey];
                item.created_at = dateKey;
                return item;
            });

            // Extract all unique pemeriksaan types and their ket from the hasilLab
            var uniquePemeriksaan = new Set();
            var pemeriksaanKetMap = {}; // Map to store pemeriksaan with their ket
            hasilLab.forEach(function(item) {
                const keterangan = item.ket ? item.ket : ""; // Use empty string if null
                const pemeriksaanLabel = item.pemeriksaan.nmLayanan + (keterangan ? " - " + keterangan : "");
                uniquePemeriksaan.add(item.pemeriksaan.nmLayanan);
                pemeriksaanKetMap[item.pemeriksaan.nmLayanan] = keterangan; // Store ket for each pemeriksaan
            });

            // Create DataTable columns dynamically
            var columns = [{
                    data: null,
                    title: "No",
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    data: "created_at",
                    title: "Tanggal"
                },
                {
                    data: "norm",
                    title: "Nomor Rekam Medis"
                },
                {
                    data: "pasien.layanan",
                    title: "Jaminan"
                },
                {
                    data: "pasien.nama",
                    title: "Nama",
                    className: "col-2",
                    render: function(data, type, row) {
                        return data.toUpperCase();
                    },
                },
                {
                    data: "pasien.alamat",
                    title: "Alamat",
                    className: "col-3"
                },
                {
                    data: "dokter.biodata.nama",
                    title: "Nama Dokter",
                    className: "col-3"
                },
            ];

            // Add each unique pemeriksaan as a column with its name as title
            uniquePemeriksaan.forEach(function(pemeriksaan) {
                const ket = pemeriksaanKetMap[pemeriksaan] ? pemeriksaanKetMap[pemeriksaan] : ""; // Get ket value
                columns.push({
                    data: "pemeriksaan." + pemeriksaan,
                    title: pemeriksaan + (ket ? " - " + ket : ""), // Append ket if exists
                    defaultContent: "-",
                });
            });

            // Initialize DataTable with dynamic columns
            var table = $("#reportKunjungan").DataTable({
                data: dataTableData,
                columns: columns,
                order: [1, "asc"],
                lengthChange: true,
                autoWidth: true,
                buttons: [{
                        extend: "copyHtml5",
                        text: "Salin",
                    },
                    {
                        extend: "excel",
                        text: "Export to Excel",
                        title: "Laporan Hasil Pemeriksaan Lab",
                        filename: "Daftar Penjamin Laboratorium",
                    },
                    {
                        extend: "colvis",
                        text: "Tampilkan Kolom",
                    },

                ],
                rowGroup: {
                    dataSrc: 'created_at' // Group rows by the formatted date
                },
                initComplete: function() {
                    this.api().table().node().classList.add("table");
                },
            });

            table.buttons().container().appendTo("#reportKunjungan_wrapper .col-md-6:eq(0)");
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

            if (Array.isArray(hasilRo) && hasilRo.length > 0) {
                show(hasilRo);
            } else {
                const preview = document.getElementById('preview');
                const info = `
                            <div class="col d-flex justify-content-center h2">
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h2><i class="icon fas fa-ban"></i> Alert!</h2>
                                    ${hasilRo}
                                </div>
                            </div>
                            `;
                preview.innerHTML = info;
            }
            if (Array.isArray(hasilLab) && hasilLab.length > 0) {
                showHasilLab(hasilLab);
            } else {
                const previewLab = document.getElementById('previewLab');
                const info = `
                            <div class="col d-flex justify-content-center h3">
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h2><i class="icon fas fa-ban"></i> Alert!</h2>
                                    ${hasilLab}
                                </div>
                            </div>
                            `;
                previewLab.innerHTML = info;
            }

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
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="http://172.16.10.88/ro" target="_blank"" class="nav-link">Aplikasi Radiologi</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="https://kkpm.banyumaskab.go.id/administrator/auth" target="_blank""
                        class="nav-link">Aplikasi KOMINFO</a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Navbar Search -->
                <li class="nav-item form-inline">
                    <label for="waktu" class="font-weight-bold mb-0 mr-2">Waktu
                        :</label>
                    <input type="text" id="waktu" class="bg-white border border-white " readonly />
                </li>
                <li class="nav-item">
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
                                    class="img-size-50 mr-3 img-circle">/
                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        <span class="float-right text-sm text-danger"><i
                                                class="fas fa-star"></i></span>
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


        @include('Template.sidebar')

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
    </div>
    {{-- @include('Template.footer') --}}
</body>

</html>
