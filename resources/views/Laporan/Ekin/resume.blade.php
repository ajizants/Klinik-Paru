<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Resume | {{ $resumePasien->pasien_no_rm }} ( {{ $resumePasien->pasien_nama }} )</title>
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
                        <hr style="margin-top: 3px; margin-bottom: 0px; border: 0.5px solid black">
                        <hr style="margin-top: 2px; margin-bottom: 0px; border: 2px solid black">
                    </td>
                </tr>
                <tr>
                    <td colspan="6" class="p-0">
                        <p
                            style="font-size: 20px; margin-bottom: -5px; text-align: center; padding:0;margin-top: 0px; font-weight: bold;">
                            RESUME MEDIS PASIEN RAWAT JALAN
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>
        <table class="table-bor mb-0" width="100%">
            <tbody>
                <tr>
                    <td width="15%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">
                        No
                        Rekam Medis</td>
                    <td width="30%" class="my-0 py-0" style=" text-align: left;">
                        {{ $resumePasien->pasien_no_rm }} / {{ $kunjungan }}
                    </td>
                    <td width="15%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">
                        Umur
                    </td>
                    <td width="35%" class="my-0 py-0" style=" text-align: left;">
                        @php
                            $umur = $resumePasien->umur;
                            preg_match('/(\d+)th/', $umur, $matches);
                            if (isset($matches[1])) {
                                $tahun = $matches[1] . ' th';
                            } else {
                                $tahun = 'N/A';
                            }
                        @endphp
                        <span>{{ $tahun }}</span>
                    </td>

                </tr>
                <tr>
                    <td width="15%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">
                        Nama
                        Pasien / JK</td>
                    <td width="35%" class="my-0 py-0" style=" text-align: left;">
                        {{ $resumePasien->pasien_nama }} / {{ $resumePasien->jenis_kelamin_nama }}
                    </td>
                    <td width="15%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">
                        Tanggal</td>
                    <td width="36%" class="my-0 py-0" style=" text-align: left;">
                        {{ Carbon\Carbon::parse($resumePasien->tanggal)->locale('id')->isoFormat('DD MMMM Y') }}
                    </td>
                </tr>
                <tr>
                    <td width="15%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">
                        Alamat</td>
                    <td class="my-0 py-0" style=" text-align: left;">
                        {{ $alamat }}
                    </td>
                    <td width="15%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">
                        Jam</td>
                    <td width="25%" class="my-0 py-0" style="text-align: left;">
                        {{ Carbon\Carbon::parse($resumePasien->cppt_created_at)->format('H:i') }}
                        WIB
                    </td>
                </tr>
            </tbody>
        </table>
        <table class="table-borTL mb-0" width="100%">
            <tbody>
                <tr>
                    <td width="15%" class="my-0 py-0" style="font-weight: bold; text-align: left;">
                        <div
                            style="min-height:60px;  font-weight: bold; text-align: left; text-decoration: underline; margin-bottom: 5px;">
                            Data Subjektif (S)</div>
                    </td>
                    <td colspan="2" class="my-0 py-0" style="width: 50%; text-align: left;">
                        {{ $resumePasien->subjek }}
                    </td>
                    <td rowspan="2" style="padding-left: 10px; text-align: left;">
                        <div
                            style=" font-weight: bold; text-align: left; text-decoration: underline; margin-bottom: 5px;">
                            Tanda-Tanda Vital</div>
                        <div class="resume-container"
                            style="padding: 10px; display: flex; justify-content: space-between; align-items: flex-start;">
                            <div style="flex: 1; text-align: left;">
                                <li>TD: {{ $resumePasien->objek_tekanan_darah }} mmHg</li>
                                <li>Suhu: {{ $resumePasien->objek_suhu }} °C</li>
                                <li>BB: {{ $resumePasien->objek_bb }} kg</li>
                            </div>

                            <div style="flex: 1; text-align: left;">
                                <li>Nadi: {{ $resumePasien->objek_nadi }} x/mnt</li>
                                <li>RR: {{ $resumePasien->objek_rr }} x/mnt</li>
                            </div>
                        </div>

                    </td>
                </tr>
                <tr>
                    <td width="15%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">
                        <div
                            style="min-height:60px; font-weight: bold; text-align: left; text-decoration: underline; margin-bottom: 5px;">
                            Data Objektif (O)
                        </div>
                    </td>
                    <td colspan="2" class="my-0 py-0" style="width: 50%; text-align: left;">
                        {{ $resumePasien->objek_data_objektif }}
                    </td>
                </tr>
            </tbody>
        </table>

        <table class="table-borTL mb-0" width="100%" style="border-top: 0cm solid #000000; !important;">
            <tbody>
                @if (count($lab) > 8)
                    <tr>
                        <td class="my-0 py-0" colspan="4" style="text-align: left;">
                            <div
                                style=" font-weight: bold; text-align: left; text-decoration: underline; margin-bottom: 5px;">
                                Penunjang Laboratorium</div>

                            @if ($lab == null || $lab == '' || $lab == '[]')
                                <div style="min-height:60px; margin-left: 38px;">
                                    Tidak ada penunjang laboratorium
                                </div>
                            @else
                                @php
                                    $labChunks = array_chunk($lab, 8);
                                @endphp
                                <div style="margin-left: 15px; display: flex; justify-content: space-between;">
                                    @foreach ($labChunks as $labChunk)
                                        <table class="table-bor"
                                            style="margin-left: 10px; margin-right: 10px; margin-bottom: 5px"
                                            width="50%">
                                            <thead>
                                                <tr>
                                                    <td class="font-weight-bold py-1"> Pemeriksaan</td>
                                                    <td class="text-center font-weight-bold py-1"> Hasil</td>
                                                    <td class="text-center font-weight-bold py-1"> Nilai Normal</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($labChunk as $item)
                                                    <tr>
                                                        <td>{{ $item['pemeriksaan'] }}</td>
                                                        <td style="text-align: center">{{ $item['hasil'] }}
                                                            {{ $item['satuan'] }}</td>
                                                        <td width="60%">
                                                            @php
                                                                $normal = $item['normal'];
                                                                $normal = str_replace(';', ';<br>', $normal);
                                                            @endphp
                                                            {!! $normal !!}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <br>
                                    @endforeach
                                </div>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="my-0 py-0" style="width: 50%; text-align: left;">
                            <div
                                style=" font-weight: bold; text-align: left; text-decoration: underline; margin-bottom: 5px;">
                                Penunjang Radiologi</div>
                            <div style="min-height:60px; margin-left: 38px;">
                                @if ($ro == null || $ro == '' || $ro == '[]')
                                    Tidak ada penunjang radiologi
                                @else
                                    Jensi Foto: {{ $ro['jenisFoto'] }}, Proyeksi: {{ $ro['proyeksi'] }}
                                @endif
                            </div>
                        </td>
                        <td class="my-0 py-0" style="width: 50%; text-align: left;">
                            <div style="padding: 10px; margin-left: 38px">
                                <img src="{{ asset('img/paru_resume.jpg') }}" alt="QR Code"
                                    style="max-width: 100%;">
                            </div>
                        </td>
                    </tr>
                @else
                    <tr>
                        <td class="my-0 py-0" style="text-align: left;">
                            <div
                                style=" font-weight: bold; text-align: left; text-decoration: underline; margin-bottom: 5px;">
                                Penunjang Laboratorium</div>
                            @if ($lab == null || $lab == '' || $lab == '[]')
                                <div style="min-height:60px; margin-left: 38px;">
                                    Tidak ada penunjang laboratorium
                                </div>
                            @else
                                @php
                                    $labChunks = array_chunk($lab, 8);
                                @endphp
                                <div style="margin-left: 15px; display: flex; justify-content: space-between;">
                                    @foreach ($labChunks as $labChunk)
                                        <table class="table-bor"
                                            style="margin-left: 10px; margin-right: 10px; margin-bottom: 5px; width:100%;">
                                            <thead>
                                                <tr>
                                                    <td class="font-weight-bold py-1"> Pemeriksaan</td>
                                                    <td class="text-center font-weight-bold py-1"> Hasil</td>
                                                    <td class="text-center font-weight-bold py-1"> Nilai Normal</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($labChunk as $item)
                                                    <tr>
                                                        <td>{{ $item['pemeriksaan'] }}</td>
                                                        <td style="text-align: center">{{ $item['hasil'] }}
                                                            {{ $item['satuan'] }}</td>
                                                        <td width="60%">
                                                            @php
                                                                $normal = $item['normal'];
                                                                $normal = str_replace(';', ';<br>', $normal);
                                                            @endphp
                                                            {!! $normal !!}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <br>
                                    @endforeach
                                </div>
                            @endif
                        </td>

                        <td class="my-0 py-0" style="width: 50%; text-align: left;">
                            <div
                                style=" font-weight: bold; text-align: left; text-decoration: underline; margin-bottom: 5px;">
                                Penunjang Radiologi</div>
                            <div style="min-height:60px; margin-left: 38px;">
                                @if ($ro == null || $ro == '' || $ro == '[]')
                                    Tidak ada penunjang radiologi
                                @else
                                    Jensi Foto: {{ $ro['jenisFoto'] }}, <br>
                                    Proyeksi: {{ $ro['proyeksi'] }}
                                @endif
                            </div>
                            <div style="padding: 10px; margin-left: 38px">
                                <img src="{{ asset('img/paru_resume.jpg') }}" alt="QR Code"
                                    style="margin-left: 100px; width: 100px;">
                            </div>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
        <table class="table-borTL mb-0" width="100%" style="border-top: 0cm solid #000000; !important;">
            <tbody>
                <tr>
                    <td class="my-0 py-2" colspan="2" style="text-align: left; width: 50%;">
                        <div
                            style=" font-weight: bold; text-align: left; text-decoration: underline; margin-bottom: 5px;">
                            Tindakan Medis</div>
                        @if ($tindakan == null || $tindakan == '' || $tindakan == '[]')
                            <div style="min-height:60px; margin-left: 38px;">
                                Tidak ada tindakan medis
                            </div>
                        @else
                            @php
                                $tindakanChunks = array_chunk($tindakan, 5);
                            @endphp
                            <div style="margin-left: 15px; display: flex; justify-content: space-between;">
                                @foreach ($tindakanChunks as $tindakanChunk)
                                    <table class="table-bor" style="margin-left: 10px; margin-right: 10px"
                                        width="100%">
                                        <thead>
                                            <tr>
                                                <td class="font-weight-bold py-1">Tindakan</td>
                                                <td class="text-center font-weight-bold py-1">BMHP/Obat</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($tindakanChunk as $item)
                                                <tr>
                                                    <td>{{ $item['tindakan'] }}</td>
                                                    <td width="70%">
                                                        @foreach ($item['bmhp'] as $item)
                                                            {{ $item['nmBmhp'] }} :
                                                            {{ $item['jumlah'] }}
                                                            {{ $item['sediaan'] }},
                                                        @endforeach
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endforeach
                            </div>
                        @endif
                    </td>
                    <td class="my-0 py-2" colspan="2" style="text-align: left; width: 50%;">
                        <div
                            style="font-weight: bold; text-align: left; text-decoration: underline; margin-bottom: 5px;">
                            Terapi / Obat
                        </div>
                        @if ($obats == null || $obats == '' || $obats == '[]')
                            <div style="margin-left: 38px;">
                                Tidak ada terapi / obat
                            </div>
                        @else
                            @php
                                $obatsChunks = array_chunk($obats, 10);
                            @endphp
                            <div style="margin-left: 30px; display: flex; justify-content: space-between;">
                                @foreach ($obatsChunks as $obatsChunk)
                                    <table class="table-bor" style="margin-left: 10px; margin-right: 10px"
                                        width="100%">
                                        <thead>
                                            <tr>
                                                <td class="font-weight-bold py-1">R/</td>
                                                <td class="font-weight-bold py-1">Nama Obat</td>
                                                <td class="text-center font-weight-bold py-1">Aturan Pakai</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($obatsChunk as $item)
                                                <tr>
                                                    <td>{{ $item['no_resep'] }}</td>
                                                    <td>
                                                        <ul style="padding-left: 20px;">
                                                            @foreach ($item['nm_obat'] as $obat)
                                                                <li>{{ $obat['nama_obat'] }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </td>
                                                    <td>{{ $item['aturan'] }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endforeach
                            </div>
                        @endif
                    </td>
                </tr>
                @if ($dxs[0]['kode_diagnosa'] == 'Z09.8')
                    <tr>
                        <td style="font-weight: bold; padding-bottom:10px; text-align: left; width: 20%;">
                            <div
                                style="min-height:50px; font-weight: bold; text-align: left; text-decoration: underline; margin-bottom: 5px;">
                                Diagnosa Primer (A)
                            </div>
                        </td>
                        <td style="padding-bottom:10px; text-align: left;">
                            @if (empty($dxs) || count($dxs) == 0)
                                -
                            @else
                                <li>{{ $dxs[1]['nmDx'] }}</li>
                            @endif
                        </td>
                        <td style="font-weight: bold; padding-bottom:10px; text-align: left; width: 20%;">
                            <div
                                style="min-height:50px; font-weight: bold; text-align: left; text-decoration: underline; margin-bottom: 5px;">
                                Diagnosa Sekunder (A)
                            </div>
                        </td>
                        <td style="padding-bottom:10px; text-align: left;">
                            @if (count($dxs) < 2)
                                -
                            @else
                                <ul>
                                    @foreach ($dxs as $index => $diagnosa)
                                        @if ($index > 1)
                                            <li>{{ $diagnosa['nmDx'] }}</li>
                                        @endif
                                    @endforeach
                                </ul>
                            @endif
                        </td>
                    </tr>
                @else
                    <tr>
                        <td style="font-weight: bold; padding-bottom:10px; text-align: left; width: 20%;">
                            <div
                                style="min-height:50px; font-weight: bold; text-align: left; text-decoration: underline; margin-bottom: 5px;">
                                Diagnosa Primer (A)
                            </div>
                        </td>
                        <td style="padding-bottom:10px; text-align: left;">
                            @if (empty($dxs) || count($dxs) == 0)
                                -
                            @else
                                <li>{{ $dxs[0]['nmDx'] }}</li>
                            @endif
                        </td>
                        <td style="font-weight: bold; padding-bottom:10px; text-align: left; width: 20%;">
                            <div
                                style="min-height:50px; font-weight: bold; text-align: left; text-decoration: underline; margin-bottom: 5px;">
                                Diagnosa Sekunder (A)
                            </div>
                        </td>
                        <td style="padding-bottom:10px; text-align: left;">
                            @if (count($dxs) < 2)
                                -
                            @else
                                <ul>
                                    @foreach ($dxs as $index => $diagnosa)
                                        @if ($index > 0)
                                            <li>{{ $diagnosa['nmDx'] }}</li>
                                        @endif
                                    @endforeach
                                </ul>
                            @endif
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>

        <table class="table-borTL mb-0" width="100%" style="border-top: 0cm solid #000000; !important;">
            <tbody>
                <tr>
                    <td colspan="4" style="padding-bottom: 10px; text-align: left;">
                        <div
                            style=" font-weight: bold; text-align: left; text-decoration: underline; margin-bottom: 5px;">
                            Rencana Tindak Lanjut (P)</div>
                        <div class="d-flex justify-between">
                            <div class="col text-center">
                                <li style="margin-left: 38px; text-align: justify;">
                                    {{ $resumePasien->status_pasien_pulang }},
                                </li>
                                <li style="margin-left: 38px; text-align: justify;">
                                    {{ $resumePasien->ket_status_pasien_pulang }}
                                </li>
                            </div>
                            <div class="col text-center">
                                @if ($resumePasien->rencana_tindak_lanjut != '-')
                                    <li style="margin-left: 38px; text-align: justify;">
                                        {{ $resumePasien->rencana_tindak_lanjut }}
                                    </li>
                                @endif
                            </div>
                            <div class="col text-center">
                            </div>
                        </div>
                    </td>
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
                                @if ($resumePasien->dokter_nama == 'dr. AGIL DANANJAYA, Sp.P')
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
                                @endif
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>


    <div class="container-fluid p-4 border border-dark" style="height: 7in;page-break-before: always;">
        <div class="mb-3" style="display: flex; align-items: center; gap: 10px;">
            <img src="{{ asset('img/BPJS_Kesehatan.png') }}" alt="bpjslogo" style="height: 70px;">
            <div class="mx-3">
                <h3 style="margin: 0;">SURAT ELEGIBILITAS PESERTA</h3>
                <h4 style="margin: 0;">KKPM PURWOKERTO</h4>
            </div>
        </div>
        <table>
            <tr>
                <td class="bold my-0 py-0">No. SEP</td>
                <td class="my-0 py-0">: {{ $detailSEP['noSep'] }}</td>
            </tr>
            <tr>
                <td class="bold my-0 py-0">Tgl. SEP</td>
                <td class="my-0 py-0">:
                    {{ Carbon\Carbon::parse($detailSEP['tglSep'])->locale('id')->isoFormat('DD MMMM Y') }}</td>
                <td class="bold my-0 py-0">Peserta</td>
                <td class="my-0 py-0">: {{ $detailSEP['peserta']['jnsPeserta'] }}</td>
            </tr>
            <tr>
                <td class="bold my-0 py-0">No. Kartu</td>
                <td class="my-0 py-0">: {{ $detailSEP['peserta']['noKartu'] }}
                    (MR.{{ $detailSEP['peserta']['noMr'] }})</td>
            </tr>
            <tr>
                <td class="bold my-0 py-0">Nama Peserta</td>
                <td class="my-0 py-0">: {{ $detailSEP['peserta']['nama'] }}</td>
                <td class="bold my-0 py-0">Jns. Rawat</td>
                <td class="my-0 py-0">: {{ $detailSEP['jnsPelayanan'] }}</td>
            </tr>
            <tr>
                <td class="bold my-0 py-0">Tgl. Lahir</td>
                <td class="my-0 py-0">: {{ $detailSEP['peserta']['tglLahir'] }} Kelamin:
                    {{ $detailSEP['peserta']['kelamin'] }}</td>
                <td class="bold my-0 py-0">Jns. Kunjungan</td>
                <td class="my-0 py-0">: - {{ $detailSEP['tujuanKunj']['nama'] }}</td>
            </tr>
            <tr>
                <td class="bold my-0 py-0">No. Telepon</td>
                <td class="my-0 py-0">: {{ $detailSEP['peserta']['no_telepon'] ?? '-' }}</td>
                <td class="bold my-0 py-0"></td>
                <td class="my-0 py-0">: - {{ $detailSEP['flagProcedure']['nama'] }}</td>
            </tr>
            <tr>
                <td class="bold my-0 py-0">Sub/Spesialis</td>
                <td class="my-0 py-0">: {{ $detailSEP['poli'] }}</td>
                <td class="bold my-0 py-0">Poli Perujuk</td>
                <td class="my-0 py-0">: -</td>
            </tr>
            <tr>
                <td class="bold my-0 py-0">Dokter</td>
                <td class="my-0 py-0">: {{ $detailSEP['dpjp']['nmDPJP'] }}</td>
                <td class="bold my-0 py-0">Kls. Hak</td>
                <td class="my-0 py-0">: {{ $detailSEP['kelasRawat'] }}</td>
            </tr>
            <tr>
                <td class="bold my-0 py-0">Faskes Perujuk</td>
                <td class="my-0 py-0">: -</td>
                <td class="bold my-0 py-0">Kls. Rawat</td>
                <td class="my-0 py-0">: {{ $detailSEP['kelasRawat'] }}</td>
            </tr>
            <tr>
                <td class="bold my-0 py-0">Diagnosa Awal</td>
                <td class="my-0 py-0">: {{ $detailSEP['diagnosa'] }}</td>
                <td class="bold my-0 py-0">Penjamin</td>
                <td class="my-0 py-0">: {{ $detailSEP['penjamin'] ?? '-' }}</td>
            </tr>
        </table>

        <p class="mb-0"><strong>Catatan:</strong></p>
        <div class="row">
            <div class="col-7">
                <div style="font-size: 11px;">
                    <p class="mb-0">*Saya menyetujui BPJS Kesehatan untuk:</p>
                    <ul class="mb-0">
                        <li>membuka dan atau menggunakan informasi medis Pasien untuk keperluan administrasi, pembayaran
                            asuransi
                            atau
                            jaminan pembiayaan kesehatan
                        </li>
                        <li>memberikan akses informasi medis atau riwayat pelayanan kepada dokter/tenaga medis pada KKPM
                            PURWOKERTO
                            untuk kepentingan pemeliharaan kesehatan, pengobatan, penyembuhan, dan perawatan Pasien
                        </li>
                    </ul>

                    <p class="mb-0">*Saya mengetahui dan memahami:</p>
                    <ul class="mb-0">
                        <li>Rumah Sakit dapat melakukan koordinasi dengan PT Jasa Raharja / PT Taspen / PT ASABRI / BPJS
                            Ketenagakerjaan atau
                            Penjamin lainnya, jika Peserta merupakan pasien yang mengalami kecelakaan lalulintas dan /
                            atau
                            kecelakaan kerja
                        </li>
                        <li>SEP bukan sebagai bukti penjaminan peserta</li>
                    </ul>
                    <p class="mb-0">**Dengan tampilnya luaran SEP elektronik ini merupakan hasil validasi terhadap
                        eligibilitas Pasien
                        secara
                        elektronik
                        (validasi finger print atau biometrik / sistem validasi lain)
                        dan selanjutnya Pasien dapat mengakses pelayanan kesehatan rujukan sesuai ketentuan berlaku.
                        Kebenaran dan keaslian atas informasi data Pasien menjadi tanggung jawab penuh FKRTL</p>
                </div>
            </div>
            <div class="col-1">
            </div>
            <div class="col-4">
                <h6>Persetujuan</h6>
                <h6>Pasien/Keluarga Pasien</h6>
                {!! $qrCode !!}
                <p>{{ $detailSEP['peserta']['nama'] }}</p>
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
