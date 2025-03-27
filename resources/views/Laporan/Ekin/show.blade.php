<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Laporan Kinerja</title>
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
            border-top: 1.2px solid black;
            padding: 0 5px 0 5px;
        }

        .table-borTL th,
        .table-borTL td {
            border: 1.2px solid black;
            border-top: none !important;
            padding: 0 5px 0 5px;
        }

        /* Targeting table inside td */
        .table-borTL td table {
            border-top: 1.2px solid black !important;
            /* Memastikan border-top diterapkan */
        }

        @media print {
            .table-bor td {
                border: 1.2px solid #000000;
                border-top: 1.2px solid black;
                color: #000000;
            }

            .table-borTL td {
                border: 1.2px solid #000000;
                border-top: none !important;
                color: #000000;
            }

            /* Apply border top in print */
            .table-borTL td table {
                border-top: 1.2px solid black !important;
            }
        }

        /* buat semua td align to */
        td {
            vertical-align: top;
        }

        .table-noborder td {
            border: none !important;
            padding: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 5px;
        }

        .center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .border {
            border: 1px solid #000;
            padding: 8px;
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
    <div class="container-fluid" style="height: 14in;">
        <table class="table table-borderless  " width="100%" style="color: black;">
            <tbody>
                <tr>
                    <td colspan="2" width="20%"
                        style="text-align: center; padding-top: 10px; padding-bottom: 10px">
                        <img src="{{ asset('img/banyumas.png') }}" style="width: 100px;">
                    </td>
                    <td colspan="2" width="60%">
                        <p style="font-size: 17px; margin-bottom: -5px; text-align: center; margin-top: 0px;">
                            PEMERINTAH KABUPATEN BANYUMAS
                        </p>
                        <p style="font-size: 20px; margin-bottom: -5px; text-align: center; margin-top: 0px;">
                            DINAS KESEHATAN
                        </p>
                        <p
                            style="font-size: 17px; margin-bottom: -5px; text-align: center; margin-top: 0px; font-weight: bold;">
                            KLINIK UTAMA KESEHATAN PARU MASYARAKAT KELAS A
                        </p>
                        <p style="font-size:12px; margin-bottom: -5px; text-align: center; margin-top: 0px;">
                            Jln. A. Yani Nomor 33 Purwokerto Timur, Banyumas, Jawa Tengah
                        </p>
                        <p style="font-size:12px; margin-bottom: -5px; text-align: center; margin-top: 0px;">
                            Kode Pos 53111, Telepon (0281) 635658, Pos-el bkpm_purwokerto@yahoo.com
                        </p>
                    </td>
                    <td colspan="2" width="20%"
                        style="text-align: left;font-size:16px; padding-top: 10px; padding-bottom: 10px;">
                        <img src="{{ asset('img/LOGO_KKPM.png') }}" style="width: 100px;">
                    </td>
                </tr>
                <tr>
                    <td colspan="6" class="pt-0">
                        <hr style="margin-top: 2px; margin-bottom: 0px; border: 2px solid black">
                        <hr style="margin-top: 3px; margin-bottom: 0px; border: 0.5px solid black">
                    </td>
                </tr>
                <tr>
                    <td colspan="6" class="p-0">
                        <p
                            style="font-size: 20px; margin-bottom: -5px; text-align: center; padding:0;margin-top: 0px; font-weight: bold;">
                            Laporan Jumlah Pelayanan Bulan Tahun {{-- $bulan-- }} Tahun {{-- $tahun --}}
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>
        <table class="mb-4" width="100%">
            <tbody>
                <tr>
                    <td width="20%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">Nama</td>
                    <td width="50%" class="my-0 py-0" style=" text-align: left;">:
                        {{-- {{ $resumePasien->pasien_no_rm }} / {{ $kunjungan }} --}}
                    </td>
                </tr>
                <tr>
                    <td width="25%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">NIP</td>
                    <td width="30%" class="my-0 py-0" style=" text-align: left;">:
                        {{-- {{ $resumePasien->pasien_no_rm }} / {{ $kunjungan }} --}}
                    </td>
                </tr>
                <tr>
                    <td width="25%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">Nama</td>
                    <td width="30%" class="my-0 py-0" style=" text-align: left;">:
                        {{-- {{ $resumePasien->pasien_no_rm }} / {{ $kunjungan }} --}}
                    </td>
                </tr>
                <tr>
                    <td width="25%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">Pangkat/Gol.
                        Ruang</td>
                    <td width="30%" class="my-0 py-0" style=" text-align: left;">:
                        {{-- {{ $resumePasien->pasien_no_rm }} / {{ $kunjungan }} --}}
                    </td>
                </tr>
            </tbody>
        </table>
        <table class="table-bor mb-0" width="100%">
            <thead>
                <tr>
                    <th width="15%" class="my-0 py-0" style=" font-weight: bold; text-align: center;">No</th>
                    <th width="60%" class="my-0 py-0" style=" text-align: center;">Jenis Pelayanan</th>
                    <th width="25%" class="my-0 py-0" style=" font-weight: bold; text-align: center;">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="my-0 py-0" style=" font-weight: bold; text-align: center;" colspan="3">
                        Pengkajian Keperawatan</td>
                </tr>
                <tr>
                    <td class="my-0 py-0" style="text-align: left;">1.</td>
                    <td class="my-0 py-0" style="text-align: left;">Anamnesa pasien baru</td>
                    <td class="my-0 py-0" style="text-align: center;">{{ $pasienBaru ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="my-0 py-0" style="text-align: left;">2.</td>
                    <td class="my-0 py-0" style="text-align: left;">Anamnesa pasien lama</td>
                    <td class="my-0 py-0" style="text-align: center;">{{ $pasienLama ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="my-0 py-0" style="text-align: left;">3.</td>
                    <td class="my-0 py-0" style="text-align: left;">Timbang tensi</td>
                    <td class="my-0 py-0" style="text-align: center;">{{ $anamnesa ?? '-' }}</td>
                </tr>

                <tr>
                    <td class="my-0 py-0" style="font-weight: bold; text-align: center;" colspan="3">
                        Implementasi Keperawatan</td>
                </tr>
                <tr>
                    <td class="my-0 py-0" style="text-align: left;">1.</td>
                    <td class="my-0 py-0" style="text-align: left;">Oksigenasi</td>
                    <td class="my-0 py-0" style="text-align: center;">{{ $oksigen ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="my-0 py-0" style="text-align: left;">2.</td>
                    <td class="my-0 py-0" style="text-align: left;">Nebulasi</td>
                    <td class="my-0 py-0" style="text-align: center;">{{ $nebulasi ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="my-0 py-0" style="text-align: left;">3.</td>
                    <td class="my-0 py-0" style="text-align: left;">Tes mantoux</td>
                    <td class="my-0 py-0" style="text-align: center;">{{ $mantoux ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="my-0 py-0" style="text-align: left;">4.</td>
                    <td class="my-0 py-0" style="text-align: left;">Injeksi</td>
                    <td class="my-0 py-0" style="text-align: center;">{{ $injeksi ?? '-' }}</td>
                </tr>
                </tr>
                <tr>
                    <td class="my-0 py-0" style="text-align: left;">5.</td>
                    <td class="my-0 py-0" style="text-align: left;">Infus</td>
                    <td class="my-0 py-0" style="text-align: center;">{{ $injeksi ?? '-' }}</td>
                <tr>
                    <td class="my-0 py-0" style="text-align: left;">6.</td>
                    <td class="my-0 py-0" style="text-align: left;">Observasi infus</td>
                    <td class="my-0 py-0" style="text-align: center;">{{ $injeksi ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="my-0 py-0" style="text-align: left;">7.</td>
                    <td class="my-0 py-0" style="text-align: left;">Penanganan pasien hemaptoe</td>
                    <td class="my-0 py-0" style="text-align: center;">{{ $hemaptoe ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="my-0 py-0" style="text-align: left;">8.EKG</td>
                    <td class="my-0 py-0" style="text-align: left;"></td>
                    <td class="my-0 py-0" style="text-align: center;">{{ $ekg ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="my-0 py-0" style="text-align: left;">9.</td>
                    <td class="my-0 py-0" style="text-align: left;">Asisten dokter</td>
                    <td class="my-0 py-0" style="text-align: center;">{{ $ruangPoli ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="my-0 py-0" style="text-align: left;">10.</td>
                    <td class="my-0 py-0" style="text-align: left;">Asisten pungsi</td>
                    <td class="my-0 py-0" style="text-align: center;">{{ $pungsi ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="my-0 py-0" style="text-align: left;">11.</td>
                    <td class="my-0 py-0" style="text-align: left;">Asisten biopsi</td>
                    <td class="my-0 py-0" style="text-align: center;">{{ $biopsi ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="my-0 py-0" style="text-align: left;">12.</td>
                    <td class="my-0 py-0" style="text-align: left;">Asisten WSD</td>
                    <td class="my-0 py-0" style="text-align: center;">{{ $wsd ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="my-0 py-0" style="text-align: left;">13.</td>
                    <td class="my-0 py-0" style="text-align: left;">Konseling PITC</td>
                    <td class="my-0 py-0" style="text-align: center;">{{ $konselingPITC ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="my-0 py-0" style="text-align: left;">14.</td>
                    <td class="my-0 py-0" style="text-align: left;">Melayani pasien baru di DOTS Center</td>
                    <td class="my-0 py-0" style="text-align: center;">{{ $injeksi ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="my-0 py-0" style="text-align: left;">15.</td>
                    <td class="my-0 py-0" style="text-align: left;">Melayani pasien lama di DOTS Center</td>
                    <td class="my-0 py-0" style="text-align: center;">{{ $injeksi ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="my-0 py-0" style="font-weight: bold; text-align: center;" colspan="3">
                        Pendokumentasian Keperawatan</td>
                </tr>
                <tr>
                    <td class="my-0 py-0" style="text-align: left;">1.</td>
                    <td class="my-0 py-0" style="text-align: left;">Input data anamnesa pasien di RME</td>
                    <td class="my-0 py-0" style=" text-align: center;">{{ $poinTensi ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="my-0 py-0" style="text-align: left;">2.</td>
                    <td class="my-0 py-0" style="text-align: left;">Anamnesa pasien lama</td>
                    <td class="my-0 py-0" style="text-align: center;">
                        {{-- {{ $resumePasien->pasien_nama }} / {{ $resumePasien->jenis_kelamin_nama }} --}}
                    </td>
                </tr>
                <tr>
                    <td class="my-0 py-0" style="text-align: left;">3.</td>
                    <td class="my-0 py-0" style="text-align: left;">Input data PITC SIHA</td>
                    <td class="my-0 py-0" style="text-align: center;"></td>
                </tr>
                <tr>
                    <td class="my-0 py-0" style="text-align: left;">3.</td>
                    <td class="my-0 py-0" style="text-align: left;">Input data TCM/BTA SITB</td>
                    <td class="my-0 py-0" style="text-align: center;"></td>
                </tr>

            </tbody>
        </table>

        <div style="font-size: 115%;">

            <table class="table table-bor" width="100%">
                <tbody>
                    <tr>
                        <td colspan="4" class="py-2" style="text-align: right;">
                            <div
                                style="font-weight: bold; display: flex; flex-direction: column; align-items: flex-end; text-align: center; margin-top: 10px; margin-bottom: 10px; margin-right: 100px;">
                                Dokter,
                                <br>
                                <br>
                                <br>
                                <br>
                                {{-- @if ($resumePasien->dokter_nama == 'dr. AGIL DANANJAYA, Sp.P')
                                    {{ $resumePasien->dokter_nama }}
                                    <br>
                                    SIP. 3302/53127/03/449.1/100/DS/B/IV/2023
                                @elseif ($resumePasien->dokter_nama == 'dr. Cempaka Nova Intani, Sp.P, FISR., MM.')
                                    {{ $resumePasien->dokter_nama }}
                                    <br>
                                    SIP. 3302/53127/01/449.1/292/DS/P/XI/2022
                                @else
                                    dr. AGIL DANANJAYA, Sp.P
                                    <br>
                                    SIP. 3302/53127/03/449.1/100/DS/B/IV/2023
                                @endif --}}
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>



    <script>
        //bagaimana untuk mengecek umur dan no_sampel jika cetak dari tombol CTRL + P dan browser
        document.addEventListener("keydown", function(event) {
            if (event.key === "Enter") {
                window.print();
                // window.onafterprint = function() {
                window.close();
                // }
            }
        })
    </script>
</body>

</html>
