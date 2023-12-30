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
    <link type="text/css" rel="stylesheet" href="{{ asset('css/mystyle.css') }}">
    <style>
        body {
            background: url("{{ asset('img/halaman kkpm.jpg') }}") no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
        }

        .ambil {
            width: 15cm;
            background-color: #ffffff;
            height: 4cm;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            text-decoration: none;
            color: rgb(0, 0, 0);
            font-size: 60px;
            font-weight: bold;

        }

        .ambil:hover {
            background-color: #3cff00;
            color: black;
            border: 2px solid #555555;
            border-radius: 5px;
            text-decoration: none;
            font-size: 60px;
            cursor: pointer;
            transition: 0.3s;
        }

        .judul {
            font-size: 4rem;
            font-weight: bold;
            border: 1px solid #3498db;
            /* Warna garis tepi dan ketebalan */
            padding: 5px;
            /* Ruang di dalam garis tepi dan teks */
            display: inline-block
        }

        .ket {
            font-weight: bold;
            border: 1px solid #3498db;
            /* Warna garis tepi dan ketebalan */
            padding: 5px;
            /* Ruang di dalam garis tepi dan teks */
            display: block
        }
    </style>
</head>

<body>
    {{-- <div class="container d-flex justify-content-center"> --}}
    <H1 class="judul text-center text-light">KLINIK UTAMA KESEHATAN PARU MASYARAKAT</H1>
    {{-- </div> --}}
    {{-- <div class="container d-flex justify-content-center"> --}}
    <h2 class="ket text-center text-light"> Silahkan Tekan Tombol Sesuai Jenis Layanan Anda Untuk Mendapatkan Nomor
        Antrian
    </h2>
    {{-- </div> --}}
    <input type="text" id="noAntrian" class="form-control" placeholder="Nomor Antrian" hidden>
    <input type="text" id="jenis" class="form-control" placeholder="jenis Layanan" hidden>
    <button onclick="cetakNoAntrian()">cetak</button>

    <div class="container d-flex justify-content-center">
        <button id="umum" class="btn btn-primary ambil" onclick="noUmum()">UMUM</button>
    </div>
    <div class="container d-flex justify-content-center mt-5">
        <button id="bpjs" class="btn btn-primary ambil" onclick="noBpjs()">BPJS</button>
    </div>


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
    <!-- AdminLTE App -->
    <script src="{{ asset('vendor/dist/js/adminlte.min.js') }}"></script>

    <!-- Costum -->

    <script src="{{ asset('js/noAntri.js') }}"></script>
</body>

</html>
