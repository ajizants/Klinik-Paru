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
            padding: 0 5px 0 5px;
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

        /* buat semua td align to */
        td {
            vertical-align: top;
        }

        .table-noborder td {
            border: none;
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

    {{-- <div class="container"> --}}
    <div class="container-fluid">
        <table class="table   " width="100%" style="color: black;">
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
                    <td width="5%" class="my-0 py-0" style=" font-weight: bold; text-align: center;">
                        :
                    </td>
                    <td width="35%" class="my-0 py-0" style=" text-align: left;">
                        {{ $resumePasien->pasien_no_rm }}
                    </td>
                    <td width="15%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">
                        Tanggal</td>
                    <td width="5%" class="my-0 py-0" style=" font-weight: bold; text-align: center;">
                        :
                    </td>
                    <td width="25%" class="my-0 py-0" style=" text-align: left;">
                        {{ Carbon\Carbon::parse($resumePasien->tanggal)->locale('id')->isoFormat('DD MMMM Y') }} ,
                    </td>
                </tr>
                <tr>
                    <td width="15%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">
                        Nama
                        Pasien / JK</td>
                    <td width="5%" class="my-0 py-0" style=" font-weight: bold; text-align: center;">
                        :
                    </td>
                    <td width="35%" class="my-0 py-0" style=" text-align: left;">
                        {{ $resumePasien->pasien_nama }} / {{ $resumePasien->jenis_kelamin_nama }}
                    </td>
                    <td width="15%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">
                        Jam</td>
                    <td width="5%" class="my-0 py-0" style=" font-weight: bold; text-align: center;">
                        :
                    </td>
                    <td width="25%" class="my-0 py-0" style="text-align: left;">
                        {{-- {{ Carbon\Carbon::parse($resumePasien->created_at)->format('H:i') }} --}}
                        WIB
                    </td>
                </tr>
                <tr>
                    <td width="15%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">
                        Umur
                    </td>
                    <td width="5%" class="my-0 py-0" style=" font-weight: bold; text-align: center;">
                        :
                    </td>
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
                    <td width="15%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">
                        Dokter</td>
                    <td width="5%" class="my-0 py-0" style=" font-weight: bold; text-align: center;">
                        :
                    </td>
                    <td width="25%" class="my-0 py-0" style=" text-align: left;">
                        {{ $resumePasien->dokter_nama }}
                    </td>
                </tr>

                <tr>
                    <td width="15%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">
                        Alamat</td>
                    <td width="5%" class="my-0 py-0" style=" font-weight: bold; text-align: center;">
                        :
                    </td>
                    <td colspan="4" class="my-0 py-0" style=" text-align: left;">
                        {{ $alamat }}
                    </td>
                </tr>
                <tr style="height: 20px"></tr>
                <tr>
                    <td width="15%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">
                        Data Subjektif</td>
                    <td width="5%" class="my-0 py-0" style=" font-weight: bold; text-align: center;">
                        :
                    </td>
                    <td colspan="2" class="my-0 py-0" style=" text-align: left;">
                        {{-- {{ $resumePasien->data_subjektif }} --}}
                        Lorem ipsum dolor sit, amet consectetur adipisicing elit. Dignissimos reiciendis eius ipsam
                        eveniet culpa, quod quae, ea consequuntur tempore nihil excepturi ex fuga tenetur dolore atque
                        reprehenderit tempora! Doloremque, minus?
                    </td>
                    <td colspan="2" rowspan="2" class="my-0 py-0"
                        style="padding-left: 10px; text-align: left;">
                        <table class="table-noborder">
                            <tr>
                                <td class="my-0 py-0" style=" text-align: left;">
                                    <li>TD :
                                        {{-- {{ $resumePasien->td || '-' }} --}}
                                        mmHg
                                    </li>
                                </td>
                                <td class="my-0 py-0" style=" text-align: left;">
                                    <li> Nadi :
                                        {{-- {{ $resumePasien->nadi || '-' }}  --}}
                                        x/mnt
                                    </li>
                                </td>
                                <td rowspan="3" width="20%"
                                    style=" padding-top:10px;padding-bottom:10px; font-weight: bold; text-align: center;">
                                    <img src="{{ asset('img/paru_resume.jpg') }}" alt="QR Code">
                                </td>

                            </tr>
                            <tr>
                                <td class="my-0 py-0" style=" text-align: left;">
                                    <li>Suhu :
                                        {{-- {{ $resumePasien->suhu || '-' }} --}}
                                        °C
                                    </li>
                                </td>
                                <td class="my-0 py-0" style=" text-align: left;">
                                    <li> RR :
                                        {{-- {{ $resumePasien->rr || '-' }} --}}
                                        x/mnt
                                    </li>
                                </td>

                            </tr>
                            <tr>
                                <td class="my-0 py-0" style=" text-align: left;">
                                    <li> BB :
                                        {{-- {{ $resumePasien->rr || '-' }} --}}
                                        kg
                                    </li>
                                </td>
                            </tr>
                        </table>
                    </td>

                </tr>
                <tr>
                    <td width="15%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">
                        Data Objektif</td>
                    <td width="5%" class="my-0 py-0" style=" font-weight: bold; text-align: center;">
                        :
                    </td>
                    <td colspan="2" class="my-0 py-0" style=" text-align: left;">
                        {{-- {{ $resumePasien->data_objektif }} --}}
                        Lorem ipsum dolor sit, amet consectetur adipisicing elit. Dignissimos reiciendis eius ipsam
                        eveniet culpa, quod quae, ea consequuntur tempore nihil excepturi ex fuga tenetur dolore atque
                        reprehenderit tempora! Doloremque, minus?
                    </td>

                </tr>
                <tr>
                    <td class="my-0 py-0" colspan="6" style=" font-weight: bold; text-align: left;">
                        Penunjang Laboratorium</td>
                </tr>
                <tr>
                    <td class="my-0 py-2" colspan="6" style="text-align: left;">

                        @if ($lab == null || $lab == '' || $lab == '[]')
                            <div style="margin-left: 38px;">
                                Tidak ada penunjang laboratorium
                            </div>
                        @else
                            @php
                                // Membagi data lab menjadi bagian dengan maksimal 8 item per bagian
                                $labChunks = array_chunk($lab, 8);
                            @endphp
                            <div style="margin-left: 30px; display: flex; justify-content: space-between;">
                                @foreach ($labChunks as $labChunk)
                                    <table style="margin-left: 10px; margin-right: 10px" width="100%">
                                        <thead>
                                            <tr>
                                                <td class="font-weight-bold py-2"> Pemeriksaan</td>
                                                <td class="text-center font-weight-bold py-2"> Hasil</td>
                                                <td class="text-center font-weight-bold py-2"> Satuan</td>
                                                <td class="text-center font-weight-bold py-2"> Nilai Normal</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($labChunk as $item)
                                                <tr>
                                                    <td>{{ $item['pemeriksaan'] }}</td>
                                                    <td style="padding-left: 20px">{{ $item['hasil'] }}</td>
                                                    <td style="text-align: center">{{ $item['satuan'] }}</td>
                                                    <td>{{ $item['normal'] }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <br> <!-- Untuk memberikan jarak antara tabel -->
                                @endforeach
                            </div>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="my-0 py-0" colspan="6" style=" font-weight: bold; text-align: left;">
                        Penunjang Radiologi</td>
                </tr>
                <tr>
                    <td class="my-0 py-0" colspan="6" style="text-align: left;">
                        <div style="margin-left: 38px;">
                            @if ($ro == null || $ro == '' || $ro == '[]')
                                Tidak ada penunjang radiologi
                            @else
                                Jensi Foto: {{ $ro['jenisFoto'] }}, Proyeksi: {{ $ro['proyeksi'] }}
                            @endif
                        </div>
                    </td>
                </tr>

                <tr>
                    <td class="my-0 py-0" colspan="3" style=" font-weight: bold; text-align: left;">
                        Tindakan Medis</td>
                    <td class="my-0 py-0" colspan="3" style=" font-weight: bold; text-align: left;">
                        Terapi / Obat</td>
                </tr>
                <tr>
                    <td class="my-0 py-2" colspan="3" style="text-align: left;">
                        @if ($tindakan == null || $tindakan == '' || $tindakan == '[]')
                            <div style="margin-left: 38px;">
                                Tidak ada tindakan medis
                            </div>
                        @else
                            @php
                                // Membagi data lab menjadi bagian dengan maksimal 8 item per bagian
                                $tindakanChunks = array_chunk($tindakan, 5);
                            @endphp
                            <div style="margin-left: 30px; display: flex; justify-content: space-between;">
                                @foreach ($tindakanChunks as $tindakanChunk)
                                    <table style="margin-left: 10px; margin-right: 10px" width="100%">
                                        <thead>
                                            <tr>
                                                <td class="font-weight-bold py-2">Tindakan</td>
                                                <td class="text-center font-weight-bold py-2">BMHP/Obat</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($tindakanChunk as $item)
                                                <tr>
                                                    <td>{{ $item['tindakan'] }}</td>
                                                    <td>
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
                                    <br> <!-- Untuk memberikan jarak antara tabel -->
                                @endforeach
                            </div>
                        @endif
                    </td>

                    <td class="my-0 py-2" colspan="3" style="text-align: left;">
                        @if ($tindakan == null || $tindakan == '' || $tindakan == '[]')
                            <div style="margin-left: 38px;">
                                Tidak ada terapi / obat
                            </div>
                        @else
                            @php
                                // Membagi data lab menjadi bagian dengan maksimal 8 item per bagian
                                $tindakanChunks = array_chunk($tindakan, 5);
                            @endphp
                            <div style="margin-left: 30px; display: flex; justify-content: space-between;">
                                @foreach ($tindakanChunks as $tindakanChunk)
                                    <table style="margin-left: 10px; margin-right: 10px" width="100%">
                                        <thead>
                                            <tr>
                                                <td class="font-weight-bold py-2">Nama Obat</td>
                                                <td class="text-center font-weight-bold py-2">Dosis</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{-- @foreach ($tindakanChunk as $item)
                                                <tr>
                                                    <td>{{ $item['tindakan'] }}</td>
                                                    <td>
                                                        @foreach ($item['bmhp'] as $item)
                                                            {{ $item['nmBmhp'] }} :
                                                            {{ $item['jumlah'] }}
                                                            {{ $item['sediaan'] }},
                                                        @endforeach
                                                    </td>
                                                </tr>
                                            @endforeach --}}
                                            <tr>
                                                <td>obat</td>
                                                <td>dosis</td>
                                            </tr>
                                            <tr>
                                                <td>obat</td>
                                                <td>dosis</td>
                                            </tr>
                                            <tr>
                                                <td>obat</td>
                                                <td>dosis</td>
                                            </tr>
                                            <tr>
                                                <td>obat</td>
                                                <td>dosis</td>
                                            </tr>
                                            <tr>
                                                <td>obat</td>
                                                <td>dosis</td>
                                            </tr>
                                            <tr>
                                                <td>obat</td>
                                                <td>dosis</td>
                                            </tr>
                                            <tr>
                                                <td>obat</td>
                                                <td>dosis</td>
                                            </tr>
                                            <tr>
                                                <td>obat</td>
                                                <td>dosis</td>
                                            </tr>
                                            <tr>
                                                <td>obat</td>
                                                <td>dosis</td>
                                            </tr>
                                            <tr>
                                                <td>obat</td>
                                                <td>dosis</td>
                                            </tr>
                                            <tr>
                                                <td>obat</td>
                                                <td>dosis</td>
                                            </tr>
                                            <tr>
                                                <td>obat</td>
                                                <td>dosis</td>
                                            </tr>
                                            <tr>
                                                <td>obat</td>
                                                <td>dosis</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <br> <!-- Untuk memberikan jarak antara tabel -->
                                @endforeach
                            </div>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="my-0 py-0" colspan="2" style="font-weight: bold; text-align: left;">
                        Diagnosa Primer
                    </td>
                    <td class="my-0 py-0" colspan="4" style=" text-align: left;">
                        {{ $resumePasien->diagnosa[0]['nama_diagnosa'] ?? '-' }}
                    </td>
                </tr>
                <tr>
                    <td class="my-0 py-0" colspan="2" style="font-weight: bold; text-align: left;">
                        Diagnosa Sekunder
                    </td>
                    <td class="my-0 py-0" colspan="4" style=" text-align: left;">
                        {{ $resumePasien->diagnosa[1]['nama_diagnosa'] ?? '-' }} <br>
                        {{ $resumePasien->diagnosa[2]['nama_diagnosa'] ?? '' }}
                    </td>
                </tr>
            </tbody>
        </table>
        <div style="font-size: 115%;">

            {{-- Petugas --}}
            <table class="table table-borderless" width="100%"">
                <tbody>
                    <tr>
                        <td width="70%" colspan="3" class="py-6 mt-6"></td>
                        <td class="py-2" style="text-align: center;">Dokter,</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="py-2 " height="60px"></td>
                    </tr>
                    <tr>
                        <td width="70%" colspan="3" class="py-2 "></td>
                        <td class="py-2" style="text-align: center;">{{ $resumePasien->dokter_nama }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- <script>
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
    </script> --}}
</body>

</html>
