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

    {{-- <div class="container"> --}}
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
        {{-- identitas --}}
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
                    <td width="36%" class="my-0 py-0 px-1 border-0" style=" font-weight: bold; text-align: left;">
                        <input type="text" name="no_sampel" id="no_sampel" style="border: none; outline: none;"
                            class="px-2 {{ $lab->no_sampel == null || $lab->no_sampel == '' ? ' bg-warning' : '' }}"
                            oninput="removeBgWarning('no_sampel')" value="{{ $lab->no_sampel }}" />

                    </td>
                </tr>
                <tr>
                    <td width="15%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">
                        Umur
                    </td>
                    <td width="5%" class="my-0 py-0" style=" font-weight: bold; text-align: center;">
                        :
                    </td>
                    <td width="26%" class="my-0 py-0 px-1" style=" text-align: left;">
                        {{-- {{ $resumePasien->umur }}  --}}
                        @php
                            // Assuming $resumePasien->umur contains "64th 11bln 10hr"
                            $umur = $lab->umur;

                            // Use regular expression to capture the year (digits followed by 'th')
                            preg_match('/(\d+)th/', $umur, $matches);

                            // If a match is found, format the output
                            if (isset($matches[1])) {
                                $tahun = $matches[1] . ' th'; // Add a space between the year and "th"
                            } else {
                                $tahun = 'N/A'; // Fallback if no match
                            }
                        @endphp

                        {{-- <span>{{ $tahun }}</span> --}}
                        <input type="text" name="umur" id="umur" style="border: none; outline: none;"
                            class="px-2 {{ $lab->umur == null || $lab->umur == '' ? ' bg-warning' : '' }}"
                            value="{{ $tahun }}" oninput="removeBgWarning('umur')" />

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
            </tbody>
        </table>
        <div style="font-size: 115%;">
            {{-- Hematologi --}}
            <table class="table table-bor border-dark border" width="100%" style="border-size: 2px; color: black;">
                <thead class="">
                    <tr style=" font-weight: bold; text-align: left;"><strong>Hematologi</strong></tr>
                    <tr>
                        <td class="font-weight-bold
                        py-2">
                            PEMERIKSAAN</td>
                        <td class="text-center font-weight-bold py-2">
                            HASIL</td>
                        <td class="font-weight-bold py-2">
                            NILAI NORMAL</td>
                    </tr>

                </thead>
                <tbody>
                    <tr>
                        <td class="py-2">Hematologi Analizer 5 DIFF</td>
                        <td class="py-2 text-center">
                            @php
                                $hasil = '-';
                                foreach ($lab->pemeriksaan as $item) {
                                    if ($item['idLayanan'] == '160') {
                                        $hasil = $item['hasil'];
                                        break;
                                    }
                                }
                            @endphp
                            {{ $hasil }}
                        </td>
                        <td class="py-2">Terlampir</td>
                    </tr>
                    <tr>
                        <td class="py-2">Golongan Darah</td>
                        <td class="py-2 text-center">
                            @php
                                $hasil = '-';
                                foreach ($lab->pemeriksaan as $item) {
                                    if ($item['idLayanan'] == '85') {
                                        $hasil = $item['hasil'];
                                        break;
                                    }
                                }
                            @endphp
                            {{ $hasil }}
                        </td>
                        <td class="py-2">-</td>
                    </tr>
                    <tr>
                        <td class="py-2">Laju Endap Darah ( LED )</td>
                        <td class="py-2 text-center">
                            @php
                                $hasil = '-';
                                foreach ($lab->pemeriksaan as $item) {
                                    if ($item['idLayanan'] == '82') {
                                        $hasil = $item['hasil'];
                                        break;
                                    }
                                }
                            @endphp
                            {{ $hasil }}
                        </td>
                        <td class="py-2 col-7">
                            <div class="row">
                                <div class="col-3">
                                    <li class="p-0 m-0">Anak-anak: 0–10 mm/jam</li>
                                </div>
                                <div class="col">
                                    <li class="p-0 m-0">Pria di bawah 50 tahun: 0–15 mm/jam</li>
                                    <li class="p-0 m-0">Pria di atas 50 tahun: 0–20 mm/jam</li>
                                </div>
                                <div class="col">
                                    <li class="p-0 m-0">Wanita di bawah 50 tahun: 0–20 mm/jam</li>
                                    <li class="p-0 m-0">Wanita di atas 50 tahun: 0–30 mm/jam</li>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            {{-- Kimia Darah --}}
            <table class="table table-bor border-dark border" width="100%" style="border-size: 2px; color: black;">
                <thead class="">
                    <tr style=" font-weight: bold; text-align: left;"><strong>Kimia Darah</strong></tr>
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
                        <td class="py-1">SGPT</td>
                        <td class="py-1 text-center">
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
                        <td class="py-1 text-center">u/l</td>
                        <td class="py-1">Pria: ≤ 45; Wanita: ≤ 34</td>
                    </tr>
                    <tr>
                        <td class="py-1">SGOT</td>
                        <td class="py-1 text-center">
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
                        <td class="py-1 text-center">u/l</td>
                        <td class="py-1">Pria: ≤ 37; Wanita: ≤ 31</td>
                    </tr>
                    <tr>
                        <td class="py-1">CHOLESTEROL</td>
                        <td class="py-1 text-center">
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
                        <td class="py-1 text-center">mg/dl</td>
                        <td class="py-1">s/d 200 </td>
                    </tr>
                    <tr>
                        <td class="py-1">GULA DARAH</td>
                        <td class="py-1 text-center">
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
                        <td class="py-1 text-center">mg/dl</td>
                        <td class="py-1">Puasa: 70 - 115; Sewaktu: 70 - 180</td>
                    </tr>
                    <tr>
                        <td class="py-1">TRIGLISERID</td>
                        <td class="py-1 text-center">
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
                        <td class="py-1 text-center">mg/dl</td>
                        <td class="py-1">s/d 150</td>
                    </tr>
                    <tr>
                        <td class="py-1">ASAM URAT</td>
                        <td class="py-1 text-center">
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
                        <td class="py-1 text-center">mg/dl</td>
                        <td class="py-1">Pria: 3,4 - 7,0; Wanita: 2,4 - 5,7</td>
                    </tr>
                    <tr>
                        <td class="py-1">UREUM</td>
                        <td class="py-1 text-center">
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
                        <td class="py-1 text-center">mg/dl</td>
                        <td class="py-1 col-7">
                            <div class="row">
                                <div class="col-2">
                                    <li class="p-0 m-0">Bayi: ≤ 42</li>
                                </div>
                                <div class="col">
                                    <li class="p-0 m-0">Anak ≤ 6 bln: < 42; Anak> 6 bln: < 48</li>
                                </div>
                                <div class="col">
                                    <li class="p-0 m-0">Dewasa ≤ 65 thn: < 50; Dewasa> 65 thn: < 70</li>
                                </div>
                            </div>
                            {{-- Bayi: ≤ 42 --}}
                        </td>
                    </tr>
                    {{-- <tr>
                        <td class="py-1">Anak ≤ 6 bln: < 42; Anak> 6 bln: < 48</td>
                    </tr>
                    <tr>
                        <td class="py-1">Dewasa ≤ 65 thn: < 50; Dewasa> 65 thn: < 70</td>
                    </tr> --}}
                    <tr>
                        <td class="py-1">CREATININ</td>
                        <td class="py-1 text-center">
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
                        <td class="py-1 text-center">mg/dl</td>
                        <td class="py-1">
                            <div class="row">
                                <div class="col-2">
                                    <li class="p-0 m-0">Bayi: ≤ 12</li>
                                </div>
                                <div class="col">
                                    <li class="p-0 m-0">Anak 2 - 12 bln: < 0,9; Anak> 1 thn: < 1,0</li>
                                </div>
                                <div class="col">
                                    <li class="p-0 m-0">Dewasa Pria: 0,6 - 1,4; Dewasa Wanita: 0,6 - 1,2</li>
                                </div>
                            </div>
                            {{-- Bayi: < 12 --}}
                        </td>
                    </tr>
                    {{-- <tr>
                        <td class="py-1">Anak 2 - 12 bln: < 0,9; Anak> 1 thn: < 1,0</td>
                    </tr>
                    <tr>
                        <td class="py-1">Dewasa Pria: 0,6 - 1,4; Dewasa Wanita: 0,6 - 1,2</td>
                    </tr> --}}
                </tbody>

            </table>
            {{-- BTA & TCM --}}
            <table class="table table-bor border-dark border" width="100%" style="border-size: 2px; color: black;">
                <thead class="">
                    <tr style=" font-weight: bold; text-align: left;"><strong>Bakteriologi</strong></tr>
                    <tr>
                        <td class="font-weight-bold
                        py-2">
                            PEMERIKSAAN</td>
                        <td class="text-center font-weight-bold py-2">
                            HASIL</td>
                        <td class="font-weight-bold py-2">
                            NILAI NORMAL</td>
                    </tr>

                </thead>
                <tbody>
                    <tr>
                        <td class="py-1">BTA 1</td>
                        <td class="py-1 text-center">
                            @php
                                $hasil = '-';
                                foreach ($lab->pemeriksaan as $item) {
                                    if ($item['idLayanan'] == '130') {
                                        $hasil = $item['hasil'];
                                        break;
                                    }
                                }
                            @endphp
                            {{ $hasil }}
                        </td>
                        <td class="py-1 col-7">Negatif</td>
                    </tr>
                    <tr>
                        <td class="py-1">BTA 2</td>
                        <td class="py-1 text-center">
                            @php
                                $hasil = '-';
                                foreach ($lab->pemeriksaan as $item) {
                                    if ($item['idLayanan'] == '214') {
                                        $hasil = $item['hasil'];
                                        break;
                                    }
                                }
                            @endphp
                            {{ $hasil }}
                        </td>
                        <td class="py-1">Negatif</td>
                    </tr>
                    <tr>
                        <td class="py-1">TCM</td>
                        <td class="py-1 text-center">
                            @php
                                $hasil = '-';
                                foreach ($lab->pemeriksaan as $item) {
                                    if ($item['idLayanan'] == '218' || $item['idLayanan'] == '131') {
                                        $hasil = $item['hasil'];
                                        break;
                                    }
                                }
                            @endphp
                            {{ $hasil }}
                        </td>
                        <td class="py-1">Negatif</td>
                    </tr>
                </tbody>
            </table>
            {{-- HIV $ Syph --}}
            <table class="table table-bor border-dark border" width="100%" style="border-size: 2px; color: black;">
                <thead class="">
                    <tr style=" font-weight: bold; text-align: left;"><strong>Imunologi</strong></tr>
                    <tr>
                        <td class="font-weight-bold py-2"> PEMERIKSAAN</td>
                        <td class="font-weight-bold py-2"> NAMA REAGENSIA</td>
                        <td class="text-center font-weight-bold py-2"> HASIL PEMERIKSAAN</td>
                        <td class="text-center font-weight-bold py-2"> HASIL AKHIR</td>
                        <td class="font-weight-bold py-2"> CATATAN</td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="py-1" rowspan="3">HIV</td>
                        <td class="py-1">
                            <input type="text" name="reagen_1" id="reagen_1"
                                style="border: none; outline: none;" value="-" class="col bg-warning"
                                oninput="removeBgWarning('reagen_1')" />
                        </td>
                        <td class="py-1 text-center">
                            <input type="checkbox" id="hiv_nr_1" name="hiv_nr_1" style="transform: scale(2);">
                            <Label for="hiv_nr_1" class="ml-2 mr-5">Non Reaktif</Label>
                            <input type="checkbox" id="hiv_r_1" name="hiv_r_1" style="transform: scale(2);">
                            <Label for="hiv_r_1" class="ml-2">Reaktif</Label>
                        <td class="py-1 pl-3" rowspan="3">
                            @php
                                $hasil = '-';
                                foreach ($lab->pemeriksaan as $item) {
                                    if ($item['idLayanan'] == '129') {
                                        $hasil = $item['hasil']; // Dapatkan hasil
                                        break; // Keluar dari loop setelah menemukan item yang cocok
                                    }
                                }
                            @endphp
                            <div class="form-group">
                                <input type="checkbox" id="kes_nr" name="kes_nr" style="transform: scale(2);"
                                    @if ($hasil == 'NR') checked @endif>
                                <label for="kes_nr" class="ml-2 mr-5">NON REAKTIF</label>
                            </div>
                            <div class="form-group">
                                <input type="checkbox" id="kes_r" name="kes_r" style="transform: scale(2);"
                                    @if ($hasil == 'R') checked @endif>
                                <label for="kes_r" class="ml-2">REAKTIF</label>
                            </div>
                            <div class="form-group">
                                <input type="checkbox" id="kes_inkon" name="kes_inkon" style="transform: scale(2);"
                                    @if ($hasil == 'INK') checked @endif>
                                <label for="kes_inkon" class="ml-2">INKONKLUSIF</label>
                            </div>
                        </td>
                        <td class="py-1 col-2" style="font-size: 80%" rowspan="3">Hasil Tes Non Reaktif tidak
                            termasuk pemaparan terhadap
                            HIV yang terjadi
                            baru-baru ini. (Klien mungkin sedang dalam masa jendela dari infeksi HIV)
                        </td>
                    </tr>
                    <tr>
                        <td class="py-1">
                            <input type="text" name="reagen_2" id="reagen_2"
                                style="border: none; outline: none;" value="-" class="col bg-warning"
                                oninput="removeBgWarning('reagen_2')" />
                        </td>
                        <td class="py-1 text-center">
                            <input type="checkbox" id="hiv_nr_2" name="hiv_nr_2" style="transform: scale(2);">
                            <Label for="hiv_nr_2" class="ml-2 mr-5">Non Reaktif</Label>
                            <input type="checkbox" id="hiv_r_2" name="hiv_r_2" style="transform: scale(2);">
                            <Label for="hiv_r_2" class="ml-2">Reaktif</Label>
                        </td>
                    </tr>
                    <tr>
                        <td class="py-1">
                            <input type="text" name="reagen_3" id="reagen_3"
                                style="border: none; outline: none;" value="-" class="col bg-warning"
                                oninput="removeBgWarning('reagen_3')" />
                        </td>
                        <td class="py-1 text-center">
                            <input type="checkbox" id="hiv_nr_3" name="hiv_nr_3" style="transform: scale(2);">
                            <Label for="hiv_nr_3" class="ml-2 mr-5">Non Reaktif</Label>
                            <input type="checkbox" id="hiv_r_3" name="hiv_r_3" style="transform: scale(2);">
                            <Label for="hiv_r_3" class="ml-2">Reaktif</Label>
                        </td>
                    </tr>
                    <tr>
                        <td class="py-1">SIFILIS (SIPILIS) </td>
                        <td class="py-1">
                            <input type="text" name="reagen_4" id="reagen_4"
                                style="border: none; outline: none;" value="-" class="col bg-warning"
                                oninput="removeBgWarning('reagen_4')" />
                        </td>
                        <td class="py-1 text-center">
                            @php
                                $hasil = '-';
                                foreach ($lab->pemeriksaan as $item) {
                                    if ($item['idLayanan'] == '215') {
                                        $hasil = $item['hasil']; // Dapatkan hasil
                                        break; // Keluar dari loop setelah menemukan item yang cocok
                                    }
                                }
                            @endphp
                            {{-- <div class="form-group"> --}}
                            <input type="checkbox" id="syp_neg" name="syp_neg" style="transform: scale(2);"
                                @if ($hasil == 'Negatif') checked @endif>
                            <label for="syp_neg" class="ml-2 mr-5">Negatif</label>
                            {{-- </div>
                            <div class="form-group"> --}}
                            <input type="checkbox" id="syp_pos" name="syp_pos" style="transform: scale(2);"
                                @if ($hasil == 'Positif') checked @endif>
                            <label for="syp_pos" class="ml-2">Positif</label>
                            {{-- </div> --}}
                        </td>
                        <td class="py-1 text-center" colspan="2">Nilai Normal: Negatif</td>
                    </tr>
                </tbody>
            </table>

            {{-- Petugas --}}
            <table class="table table-borderless" width="100%"">
                <tbody>
                    <tr>
                        <td width="70%" colspan="3" class="py-6 mt-6"></td>
                        <td class="py-2">Petugas Pemeriksa,</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="py-2 " height="50px"></td>
                    </tr>
                    <tr>
                        <td width="70%" colspan="3" class="py-2 "></td>
                        {{-- <td class="py-2">{{ $analis }}</td> --}}
                        <td class="py-2">SUHARTANTI Amd.AK.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        Swal.fire({
            icon: 'info',
            title: 'Untuk mencetak hasil lab, silahkan klik tombol \n "ENTER" \n pada tombol keyboard.\n\n' +
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
            if (event.key === "Enter") {
                cetak();
            }
        })
    </script>
</body>

</html>
