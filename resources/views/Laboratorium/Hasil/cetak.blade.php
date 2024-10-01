<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cetak Hasil Lab</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@300;700;900&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('vendor/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('vendor/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/dist/css/adminlte.min.css') }}">
    <style>
        /* CSS untuk mempertebal border pada tabel */
        .table-bordered th,
        .table-bordered td {
            border: 3px solid black;
            /* Mengatur ketebalan border */
        }
    </style>
    <!-- Script -->
    <script src="{{ asset('vendor/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('vendor/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('vendor/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- InputMask -->
    <script src="{{ asset('vendor/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('vendor/plugins/inputmask/jquery.inputmask.min.js') }}"></script>
    <!-- QR CODE -->
    <script src="https://unpkg.com/html5-qrcode@2.2.1/minified/html5-qrcode.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>

    <!-- AdminLTE App -->
    <script src="{{ asset('vendor/dist/js/adminlte.min.js') }}"></script>
</head>

<body>
    <div class="container-fluid">
        <table class="table  table-borderless " width="100%" style="color: black;">
            <tbody>
                <tr>
                    <td colspan="2" width="20%"
                        style="text-align: center; padding-top: 10px; padding-bottom: 10px">
                        <img src="{{ asset('img/banyumas.png') }}" style="width: 30%;">
                    </td>
                    <td colspan="2" width="60%">
                        <p style="font-size: 20px; margin-bottom: -5px; text-align: center; margin-top: 0px;">
                            PEMERINTAH KABUPATEN BANYUMAS
                        </p>
                        <p style="font-size: 20px; margin-bottom: -5px; text-align: center; margin-top: 0px;">
                            DINAS KESEHATAN
                        </p>
                        <p
                            style="font-size: 20px; margin-bottom: -5px; text-align: center; margin-top: 0px; font-weight: bold;">
                            KLINIK UTAMA KESEHATAN PARU MASYARAKAT KELAS A
                        </p>
                        <p style="margin-bottom: -5px; text-align: center; margin-top: 0px;">
                            Jalan A. Yani Nomor 33 Purwokerto Timur, Banyumas, Jawa Tengah
                        </p>
                        <p style="margin-bottom: -5px; text-align: center; margin-top: 0px;">
                            Kode Pos 53111, Telepon (0281) 635658, Pos-el bkpm_purwokerto@yahoo.com
                        </p>
                    </td>
                    <td colspan="2" width="20%"
                        style="text-align: center; padding-top: 10px; padding-bottom: 10px">
                        <img src="{{ asset('img/LOGO_KKPM.png') }}" style="width: 40%;">
                    </td>
                </tr>
                <tr>
                    <td colspan="6" class="pt-0">
                        <hr style="margin-top: 3px; margin-bottom: 0px; border: 0.5px solid black">
                        <hr style="margin-top: 2px; margin-bottom: 0px; border: 2px solid black">
                    </td>
                </tr>
                <tr>
                    <td colspan="6" class="p-0">
                        <p
                            style="font-size: 20px; margin-bottom: -5px; text-align: center; padding:0;margin-top: 0px; font-weight: bold;">
                            HASIL LABORATORIUM
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>
        <table class="table table-borderless mb-0" width="100%">
            <tbody>
                <tr>
                    <td width="15%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">
                        No
                        RM</td>
                    <td width="5%" class="my-0 py-0" style=" font-weight: bold; text-align: center;">
                        :
                    </td>
                    <td width="35%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">
                        {{-- {{ $pasien }} --}}
                    </td>
                    <td width="15%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">
                        Tanggal</td>
                    <td width="5%" class="my-0 py-0" style=" font-weight: bold; text-align: center;">
                        :
                    </td>
                    <td width="25%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">
                        {{-- {{ $tgl }} --}}
                    </td>
                </tr>
                <tr>
                    <td width="15%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">
                        Nama
                        Pasien</td>
                    <td width="5%" class="my-0 py-0" style=" font-weight: bold; text-align: center;">
                        :
                    </td>
                    <td width="35%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">
                        {{-- {{ $pasien }} --}}
                    </td>
                    <td width="15%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">
                        No.
                        Sampel</td>
                    <td width="5%" class="my-0 py-0" style=" font-weight: bold; text-align: center;">
                        :
                    </td>
                    <td width="25%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">
                        {{-- {{ $tgl }} --}}
                    </td>
                </tr>
                <tr>
                    <td width="15%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">
                        Umur
                    </td>
                    <td width="5%" class="my-0 py-0" style=" font-weight: bold; text-align: center;">
                        :
                    </td>
                    <td width="35%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">
                        {{-- {{ $pasien }} --}}
                    </td>
                    <td width="15%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">
                        Dokter</td>
                    <td width="5%" class="my-0 py-0" style=" font-weight: bold; text-align: center;">
                        :
                    </td>
                    <td width="25%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">
                        {{-- {{ $tgl }} --}}
                    </td>
                </tr>
                <tr>
                    <td width="15%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">
                        Alamat</td>
                    <td width="5%" class="my-0 py-0" style=" font-weight: bold; text-align: center;">
                        :
                    </td>
                    <td width="35%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">
                        {{-- {{ $pasien }} --}}
                    </td>
                    <td width="15%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">
                        Tanggal</td>
                    <td width="5%" class="my-0 py-0" style=" font-weight: bold; text-align: center;">
                        :
                    </td>
                    <td width="25%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">
                        {{-- {{ $tgl }} --}}
                    </td>
                </tr>
                <tr>
                    <td colspan="6" class="pb-0 font-weight-bold" style="font-size: 20px;">KIMIA DARAH</td>
                </tr>
            </tbody>
        </table>
        <table class="table table-bordered border-dark border" width="100%"
            style="border-size: 2px; color: black;">
            <thead>
                <tr>
                    <td class="text-center font-weight-bold py-2">
                        PEMERIKSAAN</td>
                    <td class="text-center font-weight-bold py-2">
                        HASIL</td>
                    <td class="text-center font-weight-bold py-2">
                        SATUAN</td>
                    <td class="text-center font-weight-bold py-2">
                        NILAI NORMAL</td>
                </tr>

            </thead>
            <tbody>
                <tr>
                    <td class="py-2">SGPT</td>
                    <td class="py-2 text-center"> hasil lab
                        {{-- {{ $kimiadarah->hasil }} --}}
                    </td>
                    <td class="py-2 text-center">u/l</td>
                    <td class="py-2">Pria: ≤ 45; Wanita: ≤ 34</td>
                </tr>
                <tr>
                    <td class="py-2">SGOT</td>
                    <td class="py-2 text-center"> hasil lab
                        {{-- {{ $kimiadarah->hasil }} --}}
                    </td>
                    <td class="py-2 text-center">u/l</td>
                    <td class="py-2">Pria: ≤ 45; Wanita: ≤ 34</td>
                </tr>
                <tr>
                    <td class="py-2">CHOLESTEROL</td>
                    <td class="py-2 text-center"> hasil lab
                        {{-- {{ $kimiadarah->hasil }} --}}
                    </td>
                    <td class="py-2 text-center">u/l</td>
                    <td class="py-2">Pria: ≤ 45; Wanita: ≤ 34</td>
                </tr>
                <tr>
                    <td class="py-2">GULA DARAH</td>
                    <td class="py-2 text-center"> hasil lab
                        {{-- {{ $kimiadarah->hasil }} --}}
                    </td>
                    <td class="py-2 text-center">u/l</td>
                    <td class="py-2">Pria: ≤ 45; Wanita: ≤ 34</td>
                </tr>
                <tr>
                    <td class="py-2">TRIGLISERID</td>
                    <td class="py-2 text-center"> hasil lab
                        {{-- {{ $kimiadarah->hasil }} --}}
                    </td>
                    <td class="py-2 text-center">u/l</td>
                    <td class="py-2">Pria: ≤ 45; Wanita: ≤ 34</td>
                </tr>
                <tr>
                    <td class="py-2">ASAM URAT</td>
                    <td class="py-2 text-center"> hasil lab
                        {{-- {{ $kimiadarah->hasil }} --}}
                    </td>
                    <td class="py-2 text-center">u/l</td>
                    <td class="py-2">Pria: ≤ 45; Wanita: ≤ 34</td>
                </tr>
                <tr>
                    <td rowspan="3" class="py-2">UREUM</td>
                    <td rowspan="3" class="py-2 text-center"> hasil lab
                        {{-- {{ $kimiadarah->hasil }} --}}
                    </td>
                    <td rowspan="3" class="py-2 text-center">u/l</td>
                    <td class="py-2">Bayi: ≤ 45; Wanita: ≤ 34</td>
                </tr>
                <tr>
                    <td class="py-2">Anak: ≤ 45; Wanita: ≤ 34</td>
                </tr>
                <tr>
                    <td class="py-2">Dewasa: ≤ 45; Wanita: ≤ 34</td>
                </tr>
                <tr>
                    <td rowspan="3" class="py-2">CREATININ</td>
                    <td rowspan="3" class="py-2 text-center"> hasil lab
                        {{-- {{ $kimiadarah->hasil }} --}}
                    </td>
                    <td rowspan="3" class="py-2 text-center">u/l</td>
                    <td class="py-2">Bayi: ≤ 45; Wanita: ≤ 34</td>
                </tr>
                <tr>
                    <td class="py-2">Anak: ≤ 45; Wanita: ≤ 34</td>
                </tr>
                <tr>
                    <td class="py-2">Dewasa: ≤ 45; Wanita: ≤ 34</td>
                </tr>
            </tbody>
        </table>
        <table class="table table-borderedless" width="100%" style="border-size: 2px; color: black;">
            <tbody>
                <tr>
                    <td width="70%" colspan="3" class="py-6 mt-6"></td>
                    <td class="py-2">Petugas Pemeriksa,</td>
                </tr>
                <tr>
                    <td colspan="4" class="py-2 " height="100px"></td>
                </tr>
                <tr>
                    <td width="70%" colspan="3" class="py-2 "></td>
                    <td class="py-2">Nama Petugas</td>
                </tr>
            </tbody>
        </table>


</body>

</html>
