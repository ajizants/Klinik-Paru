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
        {{-- identitas --}}
        <table class="table-bor mb-0" width="100%">
            <tbody>
                <tr>
                    <td width="15%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">
                        No
                        Rekam Medis</td>
                    {{-- <td width="2%" class="my-0 py-0" style=" font-weight: bold; text-align: center;">
                        :
                    </td> --}}
                    <td width="30%" class="my-0 py-0" style=" text-align: left;">
                        {{ $resumePasien->pasien_no_rm }} / {{ $kunjungan }}
                    </td>
                    <td width="15%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">
                        Umur
                    </td>
                    {{-- <td width="5%" class="my-0 py-0" style=" font-weight: bold; text-align: center;">
                        :
                    </td> --}}
                    <td width="35%" class="my-0 py-0" style=" text-align: left;">
                        {{-- {{ $resumePasien->umur }}  --}}
                        @php
                            // Assuming $resumePasien->umur contains "64th 11bln 10hr"
                            $umur = $resumePasien->umur;

                            // Use regular expression to capture the year (digits followed by 'th')
                            preg_match('/(\d+)th/', $umur, $matches);

                            // If a match is found, format the output
                            if (isset($matches[1])) {
                                $tahun = $matches[1] . ' th'; // Add a space between the year and "th"
                            } else {
                                $tahun = 'N/A'; // Fallback if no match
                            }
                        @endphp

                        <span>{{ $tahun }}</span>

                    </td>

                </tr>
                <tr>
                    <td width="15%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">
                        Nama
                        Pasien / JK</td>
                    {{-- <td width="5%" class="my-0 py-0" style=" font-weight: bold; text-align: center;">
                        :
                    </td> --}}
                    <td width="35%" class="my-0 py-0" style=" text-align: left;">
                        {{ $resumePasien->pasien_nama }} / {{ $resumePasien->jenis_kelamin_nama }}
                    </td>
                    <td width="15%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">
                        Tanggal</td>
                    {{-- <td width="2%" class="my-0 py-0" style=" font-weight: bold; text-align: center;">
                        :
                    </td> --}}
                    <td width="36%" class="my-0 py-0" style=" text-align: left;">
                        {{ Carbon\Carbon::parse($resumePasien->tanggal)->locale('id')->isoFormat('DD MMMM Y') }}
                    </td>
                </tr>
                <tr>
                    <td width="15%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">
                        Alamat</td>
                    {{-- <td width="5%" class="my-0 py-0" style=" font-weight: bold; text-align: center;">
                        :
                    </td> --}}
                    <td class="my-0 py-0" style=" text-align: left;">
                        {{ $alamat }}
                    </td>

                    <td width="15%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">
                        Jam</td>
                    {{-- <td width="5%" class="my-0 py-0" style=" font-weight: bold; text-align: center;">
                        :
                    </td> --}}
                    <td width="25%" class="my-0 py-0" style="text-align: left;">
                        {{ Carbon\Carbon::parse($resumePasien->cppt_created_at)->format('H:i') }}
                        WIB
                    </td>
                </tr>
            </tbody>
        </table>
        {{-- DS DO --}}
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
                                                        {{-- <td style="text-align: center"></td> --}}
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
                                            {{-- Dimulai dari indeks ke-1 untuk diagnosa sekunder --}}
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
                                            {{-- Dimulai dari indeks ke-1 untuk diagnosa sekunder --}}
                                            <li>{{ $diagnosa['nmDx'] }}</li>
                                        @endif
                                    @endforeach
                                </ul>
                            @endif
                        </td>
                    </tr>
                @endif
                {{-- @if ($resumePasien->diagnosa[0]['kode_diagnosa'] == 'Z09.8')
                    <tr>
                        <td style="font-weight: bold; padding-bottom:10px; text-align: left; width: 20%;">
                            <div
                                style="min-height:50px; font-weight: bold; text-align: left; text-decoration: underline; margin-bottom: 5px;">
                                Diagnosa Primer (A)
                            </div>
                        </td>
                        <td style="padding-bottom:10px; text-align: left;">
                            @if (empty($resumePasien->diagnosa) || count($resumePasien->diagnosa) == 0)
                                -
                            @else
                                <li>{{ $resumePasien->diagnosa[1]['nama_diagnosa'] }}</li>
                            @endif
                        </td>
                        <td style="font-weight: bold; padding-bottom:10px; text-align: left; width: 20%;">
                            <div
                                style="min-height:50px; font-weight: bold; text-align: left; text-decoration: underline; margin-bottom: 5px;">
                                Diagnosa Sekunder (A)
                            </div>
                        </td>
                        <td style="padding-bottom:10px; text-align: left;">
                            @if (count($resumePasien->diagnosa) < 2)
                                -
                            @else
                                <ul>
                                    @foreach ($resumePasien->diagnosa as $index => $diagnosa)
                                        @if ($index > 1)
                                            <li>{{ $diagnosa['nama_diagnosa'] }}</li>
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
                            @if (empty($resumePasien->diagnosa) || count($resumePasien->diagnosa) == 0)
                                -
                            @else
                                <li>{{ $resumePasien->diagnosa[0]['nama_diagnosa'] }}</li>
                            @endif
                        </td>
                        <td style="font-weight: bold; padding-bottom:10px; text-align: left; width: 20%;">
                            <div
                                style="min-height:50px; font-weight: bold; text-align: left; text-decoration: underline; margin-bottom: 5px;">
                                Diagnosa Sekunder (A)
                            </div>
                        </td>
                        <td style="padding-bottom:10px; text-align: left;">
                            @if (count($resumePasien->diagnosa) < 2)
                                -
                            @else
                                <ul>
                                    @foreach ($resumePasien->diagnosa as $index => $diagnosa)
                                        @if ($index > 0)
                                            <li>{{ $diagnosa['nama_diagnosa'] }}</li>
                                        @endif
                                    @endforeach
                                </ul>
                            @endif
                        </td>
                    </tr>
                @endif --}}
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
                                    {{-- {{ $resumePasien->ket_status_pasien_pulang }} --}}
                                </li>
                                <li style="margin-left: 38px; text-align: justify;">
                                    {{-- {{ $resumePasien->status_pasien_pulang }}, --}}
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

            {{-- Petugas --}}
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
                    {{-- QR Code --}}
                    {{-- <tr>
                        <td colspan="4" style="text-align: center;">
                            <img src="data:image/png;base64,{{ $ttd }}" alt="QR Code" />
                        </td>
                    </tr> --}}
                </tbody>
            </table>
        </div>
    </div>

    <script>
        //bagaimana untuk mengecek umur dan no_sampel jika cetak dari tombol CTRL + P dan browser
        document.addEventListener("keydown", function(event) {
            if (event.key === "Enter") {
                window.print();
                window.addEventListener('afterprint', () => {
                    window.close(); // ini akan berhasil kalau dibuka dari window.open()
                });
            }
        })
    </script>
</body>

</html>
