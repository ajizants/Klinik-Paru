<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cetak</title>
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
        .table-bor th,
        .table-bor td {
            border: 1.2px solid black;
            /* Mengatur ketebalan border */
        }

        @media print {
            .table-bor td {
                border: 1.2px solid #000000;
                /* Hitam dalam format hex */
                color: #000000;
                /* Hitam untuk teks */
            }
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

    <div class="container">
        <div class="container-fluid">
            <table class="table  table-borderless " width="100%" style="color: black;">
                <tbody>
                    <tr>
                        <td colspan="2" width="20%"
                            style="text-align: center; padding-top: 10px; padding-bottom: 10px">
                            <img src="{{ asset('img/banyumas.png') }}" style="width: 100px;">
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
                            <img src="{{ asset('img/LOGO_KKPM.png') }}" style="width: 100px;">
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
                        <td width="26%" class="my-0 py-0" style=" text-align: left;">
                            {{ $lab->norm }}
                        </td>
                        <td width="15%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">
                            Tanggal</td>
                        <td width="5%" class="my-0 py-0" style=" font-weight: bold; text-align: center;">
                            :
                        </td>
                        <td width="36%" class="my-0 py-0" style=" text-align: left;">
                            {{ Carbon\Carbon::parse($lab->created_at)->locale('id')->isoFormat('DD MMMM Y') }} ,
                            {{ Carbon\Carbon::parse($lab->created_at)->format('H:i') }} WIB
                        </td>
                    </tr>
                    <tr>
                        <td width="15%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">
                            Nama
                            Pasien</td>
                        <td width="5%" class="my-0 py-0" style=" font-weight: bold; text-align: center;">
                            :
                        </td>
                        <td width="26%" class="my-0 py-0" style=" text-align: left;">
                            {{ $lab->nama }}
                        </td>
                        <td width="15%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">
                            No.
                            Sampel</td>
                        <td width="5%" class="my-0 py-0" style=" font-weight: bold; text-align: center;">
                            :
                        </td>
                        <td width="36%" class="my-0 py-0 border-0" style=" font-weight: bold; text-align: left;">
                            <input type="text" name="no_sampel" id="no_sampel" style="border: none; outline: none;"
                                value="" class="bg-warning" oninput="removeBgWarning('no_sampel')" />
                        </td>
                    </tr>
                    <tr>
                        <td width="15%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">
                            Umur
                        </td>
                        <td width="5%" class="my-0 py-0" style=" font-weight: bold; text-align: center;">
                            :
                        </td>
                        <td width="26%" class="my-0 py-0" style=" text-align: left;">
                            <input type="text" name="umur" id="umur" style="border: none; outline: none;"
                                value="" class="bg-warning col-3" oninput="removeBgWarning('umur')" /> thn
                        </td>
                        <td width="15%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">
                            Dokter</td>
                        <td width="5%" class="my-0 py-0" style=" font-weight: bold; text-align: center;">
                            :
                        </td>
                        <td width="36%" class="my-0 py-0" style=" text-align: left;">
                            {{ $dokter }}
                        </td>
                    </tr>

                    <tr>
                        <td width="15%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">
                            Alamat</td>
                        <td width="5%" class="my-0 py-0" style=" font-weight: bold; text-align: center;">
                            :
                        </td>
                        <td width="26%" class="my-0 py-0" style=" text-align: left;">
                            {{ $lab->alamat }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6" class="pb-0 font-weight-bold" style="font-size: 20px;">Kimia Darah</td>
                    </tr>
                </tbody>
            </table>
            <div style="font-size:125% ">
                <table class="table table-bor border-dark border" width="100%"
                    style="border-size: 2px; color: black;">
                    <thead class="">
                        <tr>
                            <td class="font-weight-bold py-2">
                                PEMERIKSAAN</td>
                            <td class="text-center font-weight-bold py-2">
                                HASIL</td>
                            <td class="text-center font-weight-bold py-2">
                                SATUAN</td>
                            <td class="font-weight-bold py-2">
                                NILAI NORMAL</td>
                        </tr>

                    </thead>
                    <tbody>
                        <tr>
                            <td class="py-2">SGPT</td>
                            <td class="py-2 text-center">
                                @php
                                    $hasil = '-';
                                    foreach ($lab->pemeriksaan as $item) {
                                        if ($item['idLayanan'] == '100') {
                                            $hasil = $item['hasil'];
                                            break;
                                        }
                                    }
                                @endphp
                                {{ $hasil }}
                            </td>
                            <td class="py-2 text-center">u/l</td>
                            <td class="py-2">Pria: ≤ 45; Wanita: ≤ 34</td>
                        </tr>
                        <tr>
                            <td class="py-2">SGOT</td>
                            <td class="py-2 text-center">
                                @php
                                    $hasil = '-';
                                    foreach ($lab->pemeriksaan as $item) {
                                        if ($item['idLayanan'] == '101') {
                                            $hasil = $item['hasil'];
                                            break;
                                        }
                                    }
                                @endphp
                                {{ $hasil }}
                            </td>
                            <td class="py-2 text-center">u/l</td>
                            <td class="py-2">Pria: ≤ 37; Wanita: ≤ 31</td>
                        </tr>
                        <tr>
                            <td class="py-2">CHOLESTEROL</td>
                            <td class="py-2 text-center">
                                @php
                                    $hasil = '-';
                                    foreach ($lab->pemeriksaan as $item) {
                                        if ($item['idLayanan'] == '218' || $item['idLayanan'] == '111') {
                                            $hasil = $item['hasil'];
                                            break;
                                        }
                                    }
                                @endphp
                                {{ $hasil }}
                            </td>
                            <td class="py-2 text-center">mg/dl</td>
                            <td class="py-2">s/d 200 </td>
                        </tr>
                        <tr>
                            <td class="py-2">GULA DARAH</td>
                            <td class="py-2 text-center">
                                @php
                                    $hasil = '-';
                                    foreach ($lab->pemeriksaan as $item) {
                                        if ($item['idLayanan'] == '217' || $item['idLayanan'] == '108') {
                                            $hasil = $item['hasil'];
                                            break;
                                        }
                                    }
                                @endphp
                                {{ $hasil }}
                            </td>
                            <td class="py-2 text-center">u/l</td>
                            <td class="py-2">Puasa: 70 - 115; Sewaktu: 70 - 180</td>
                        </tr>
                        <tr>
                            <td class="py-2">TRIGLISERID</td>
                            <td class="py-2 text-center">
                                @php
                                    $hasil = '-';
                                    foreach ($lab->pemeriksaan as $item) {
                                        if ($item['idLayanan'] == '110') {
                                            $hasil = $item['hasil'];
                                            break;
                                        }
                                    }
                                @endphp
                                {{ $hasil }}
                            </td>
                            <td class="py-2 text-center">u/l</td>
                            <td class="py-2">s/d 150</td>
                        </tr>
                        <tr>
                            <td class="py-2">ASAM URAT</td>
                            <td class="py-2 text-center">
                                @php
                                    $hasil = '-';
                                    foreach ($lab->pemeriksaan as $item) {
                                        if ($item['idLayanan'] == '99' || $item['idLayanan'] == '216') {
                                            $hasil = $item['hasil'];
                                            break;
                                        }
                                    }
                                @endphp
                                {{ $hasil }}
                            </td>
                            <td class="py-2 text-center">u/l</td>
                            <td class="py-2">Pria: 3,4 - 7,0; Wanita: 2,4 - 5,7</td>
                        </tr>
                        <tr>
                            <td rowspan="3" class="py-2">UREUM</td>
                            <td rowspan="3" class="py-2 text-center">
                                @php
                                    $hasil = '-';
                                    foreach ($lab->pemeriksaan as $item) {
                                        if ($item['idLayanan'] == '97') {
                                            $hasil = $item['hasil'];
                                            break;
                                        }
                                    }
                                @endphp
                                {{ $hasil }}
                            </td>
                            <td rowspan="3" class="py-2 text-center">u/l</td>
                            <td class="py-2">Bayi: ≤ 42</td>
                        </tr>
                        <tr>
                            <td class="py-2">Anak ≤ 6 bln: < 42; Anak> 6 bln: < 48</td>
                        </tr>
                        <tr>
                            <td class="py-2">Dewasa ≤ 65 thn: < 50; Dewasa> 65 thn: < 70</td>
                        </tr>
                        <tr>
                            <td rowspan="3" class="py-2">CREATININ</td>
                            <td rowspan="3" class="py-2 text-center">
                                @php
                                    $hasil = '-';
                                    foreach ($lab->pemeriksaan as $item) {
                                        if ($item['idLayanan'] == '98') {
                                            $hasil = $item['hasil'];
                                            break;
                                        }
                                    }
                                @endphp
                                {{ $hasil }}
                            </td>
                            <td rowspan="3" class="py-2 text-center">u/l</td>
                            <td class="py-2">Bayi: < 12</td>
                        </tr>
                        <tr>
                            <td class="py-2">Anak 2 - 12 bln: < 0,9; Anak> 1 thn: < 1,0</td>
                        </tr>
                        <tr>
                            <td class="py-2">Dewasa Pria: 0,6 - 1,4; Dewasa Wanita: 0,6 - 1,2</td>
                        </tr>
                    </tbody>

                </table>
                <table class="table table-borderless" width="100%"">
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
                            <td class="py-2">{{ $analis }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="container" style="height: 100px">
    </div>

    <script>
        Swal.fire({
            icon: 'info',
            title: 'Untuk mencetak hasil lab, silahkan klik tombol \n "ENTER"   atau   "SPASI" \n pada tombol keyboard.\n\n' +
                'Jangan Lupa Mengisikan Umur Paien dan No Sample. Terima Kasih.',
        })

        //buatkan fungsi cek, apakah umur dan no_sampel sudah diisi saat sebelum cetak
        function cetak() {
            var umur = document.getElementById("umur").value;
            var no_sampel = document.getElementById("no_sampel").value;
            if (umur == "" || no_sampel == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Umur dan No Sample harus diisi terlebih dahulu!',
                }).then(() => {
                    if (document.getElementById("no_sampel").value == "") {
                        document.getElementById("no_sampel").focus();
                    } else {
                        document.getElementById("umur").focus();
                    }
                    return false;
                })
                //fokuskan pada umur dan no_sampel
            } else {
                window.print();
                // window.onafterprint = function() {
                window.close();
                // }
            }
        }
        document.getElementById("no_sampel").focus();

        function removeBgWarning(id) {
            const input = document.getElementById(id);
            if (input.value) {
                input.classList.remove('bg-warning');
            } else {
                input.classList.add('bg-warning');
            }
        }

        //bagaimana untuk mengecek umur dan no_sampel jika cetak dari tombol CTRL + P dan browser
        document.addEventListener("keydown", function(event) {
            if (event.key === " " || event.key === "Enter") {
                cetak();
            }
        })
    </script>
</body>

</html>
