<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cetak Permintaan Laboratorium</title>
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
                            PERMINTAAN LABORATORIUM
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>
        {{-- identitas --}}
        <table class="table table-borderless mb-0" width="100%">
            @php
                // Assuming $resumePasien->umur contains "64th 11bln 10hr"
                $umur = $dataCppt['umur'];

                // Use regular expression to capture the year (digits followed by 'th')
                preg_match('/(\d+)th/', $umur, $matches);

                // If a match is found, format the output
                if (isset($matches[1])) {
                    $tahun = $matches[1] . ' th'; // Add a space between the year and "th"
                } else {
                    $tahun = 'N/A'; // Fallback if no match
                }
            @endphp
            <tbody>
                <tr>
                    <td width="15%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">
                        No
                        RM</td>
                    <td width="5%" class="my-0 py-0" style=" font-weight: bold; text-align: center;">
                        :
                    </td>
                    <td width="26%" class="my-0 py-0" style=" text-align: left;">
                        {{ $dataCppt['pasien_no_rm'] }} / {{ $tahun }} / {{ $tglLahir }}
                    </td>
                    <td width="15%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">
                        Tanggal</td>
                    <td width="5%" class="my-0 py-0" style=" font-weight: bold; text-align: center;">
                        :
                    </td>
                    <td width="36%" class="my-0 py-0" style=" text-align: left;">
                        {{ Carbon\Carbon::parse($dataCppt['tanggal'])->locale('id')->isoFormat('DD MMMM Y') }}
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
                        {{ $dataCppt['pasien_nama'] }}
                    </td>
                    <td width="15%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">
                        No.
                        Sampel</td>
                    <td width="5%" class="my-0 py-0" style=" font-weight: bold; text-align: center;">
                        :
                    </td>
                    <td width="36%" class="my-0 py-0 px-1 border-0" style=" font-weight: bold; text-align: left;">
                        <input type="text" name="no_sampel" id="no_sampel" style="border: none; outline: none;"
                            class="px-2 {{ $noSampel == null || $noSampel == null || $noSampel == '' ? ' bg-warning' : '' }}"
                            oninput="removeBgWarning('no_sampel')" value="{{ $noSampel ?? '-' }}" />

                    </td>
                </tr>
                <tr>
                    <td width="15%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">
                        Alamat</td>
                    <td width="5%" class="my-0 py-0" style=" font-weight: bold; text-align: center;">
                        :
                    </td>
                    <td width="26%" class="my-0 py-0" style=" text-align: left;">
                        {{ $dataCppt['kelurahan_nama'] }}, {{ $dataCppt['pasien_rt'] }}/{{ $dataCppt['pasien_rw'] }},
                        {{ $dataCppt['kecamatan_nama'] }}, {{ $dataCppt['kabupaten_nama'] }}
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
            </tbody>
        </table>
        <div style=" margin-top: 15px;">
            {{-- Hematologi --}}
            <table class="table table-bor border-dark border" width="100%" style="border-size: 2px; color: black;">
                <thead>
                    @foreach ($permintaan as $item)
                        @if ($loop->first || ($loop->iteration - 1) % 4 == 0)
                            <tr>
                        @endif

                        <td class="font-weight-bold py-2" width="25%">
                            <i class="fas fa-check-circle text-success mr-1"></i>
                            {{ $item['layanan'] }}
                        </td>

                        @if ($loop->iteration % 4 == 0 || $loop->last)
                            @php
                                // Hitung sisa kolom jika di akhir dan belum genap 4
                                $sisa = 4 - ($loop->iteration % 4);
                            @endphp

                            @if ($loop->last && $loop->iteration % 4 != 0)
                                @for ($i = 0; $i < $sisa; $i++)
                                    <td width="25%"></td>
                                @endfor
                            @endif

                            </tr>
                        @endif
                    @endforeach
                </thead>
            </table>


        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            window.print();
            window.addEventListener('afterprint', () => {
                window.close(); // ini akan berhasil kalau dibuka dari window.open()
            });
        });
    </script>

</body>

</html>
