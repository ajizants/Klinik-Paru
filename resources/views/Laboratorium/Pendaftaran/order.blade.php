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

            body {
                margin: 0;
            }
        }
    </style>
    <!-- Script -->
    <script src="{{ asset('vendor/plugins/jquery/jquery.min.js') }}"></script>
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
                            Kode Pos 52111, Telepon (0281) 635658, Pos-el bkpm_purwokerto@yahoo.com
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
        <table class="table table-borderless mt-3" width="100%">
            @php
                $umur = $dataCppt['umur'];

                // Use regular expression to capture the year (digits followed by 'th')
                preg_match('/(\d+)th/', $umur, $matches);

                // If a match is found, format the output
                if (isset($matches[1])) {
                    $tahun = (int) $matches[1]; // Convert to integer for comparison
                    $tahunDisplay = $matches[1] . ' th'; // Add a space between the year and "th" for display
                } else {
                    $tahun = 0; // Default value for comparison
                    $tahunDisplay = 'N/A'; // Fallback if no match
                }

                if ($tahun <= 14) {
                    $sebutan = 'An. ';
                } elseif ($tahun > 14 && $tahun <= 30) {
                    if ($dataCppt['jenis_kelamin_nama'] == 'L') {
                        $sebutan = 'Sdr. ';
                    } else {
                        $sebutan = 'Nn. ';
                    }
                } elseif ($tahun > 30) {
                    if ($dataCppt['jenis_kelamin_nama'] == 'L') {
                        $sebutan = 'Tn. ';
                    } else {
                        $sebutan = 'Ny. ';
                    }
                }
            @endphp
            <tbody>
                <tr>
                    <td width="10%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">
                        Tanggal</td>
                    <td width="26%" class="my-0 py-0" style=" text-align: left;">
                        :
                        {{ Carbon\Carbon::parse($dataCppt['tanggal'])->locale('id')->isoFormat('DD MMMM Y') }}
                    </td>

                    <td width="10%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">
                        No.
                        Sampel</td>
                    <td width="36%" class="my-0 py-0 border-0" style=" font-weight: bold; text-align: left;">
                        : {{ $noSampel ?? '-' }}

                    </td>
                </tr>
                <tr>

                    <td width="10%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">
                        No
                        RM</td>
                    <td width="26%" class="my-0 py-0" style=" text-align: left;">
                        : {{ $dataCppt['pasien_no_rm'] }} / {{ $tglLahir }} /
                        {{ $dataCppt['penjamin_nama'] }}
                    </td>

                    <td width="11%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">
                        Dokter</td>
                    <td width="36%" class="my-0 py-0" style=" text-align: left;">
                        : {{ $dokter }}
                    </td>
                </tr>
                <tr>
                    <td width="11%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">
                        Nama
                    </td>
                    <td width="26%" class="my-0 py-0" style="text-align: left;">
                        : {{ $sebutan }}{{ ucwords(strtolower($dataCppt['pasien_nama'])) }} /
                        {{ $dataCppt['jenis_kelamin_nama'] }} / {{ $tahun }} thn
                    </td>

                    <td width="11%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">
                        Alamat</td>
                    <td width="36%" class="my-0 py-0" style=" text-align: left;">
                        @php
                            $alamat =
                                $dataCppt['kelurahan_nama'] .
                                ', ' .
                                $dataCppt['pasien_rt'] .
                                '/' .
                                $dataCppt['pasien_rw'] .
                                ', ' .
                                $dataCppt['kecamatan_nama'] .
                                ', ' .
                                $dataCppt['kabupaten_nama'];
                            $alamat = ucwords(strtolower($alamat));
                            $alamatTampil = strlen($alamat) > 50 ? substr($alamat, 0, 50) . '...' : $alamat;
                        @endphp

                        : {{ $alamatTampil }}

                    </td>

                </tr>
            </tbody>
        </table>
        @if ($tcm == true)
            @php
                $permintaan = collect($permintaan);

                // Data yang tidak mengandung "TCM"
                $permintaanNonTcm = $permintaan->filter(function ($item) {
                    return stripos($item['layanan'], 'TCM') === false;
                });

                // Data yang mengandung "TCM"
                $permintaanTcm = $permintaan
                    ->filter(function ($item) {
                        return stripos($item['layanan'], 'TCM') !== false;
                    })
                    ->values(); // values() untuk reset index

                if (count($permintaanNonTcm) > 0) {
                    $widht = '30%';
                } else {
                    $widht = '100%';
                }
            @endphp

            <div style=" margin-top: 15px;">
                <table width="100%">
                    <thead>
                        @foreach ($permintaanTcm as $item)
                            @if ($loop->first || ($loop->iteration - 1) % 4 == 0)
                                <tr>
                            @endif

                            <td class="font-weight-bold py-2" width="{{ $widht }}">
                                <i class="fas fa-check-circle text-success mr-1"></i>
                                <span title="{{ $item['layanan'] }}">
                                    {{ \Illuminate\Support\Str::limit($item['layanan'], 20) }}
                                </span>
                                @if (!empty($item['keterangan']))
                                    - ({{ $item['keterangan'] }})
                                @endif

                                @if ($item['layanan'] === 'TCM XDR (Xpert)' || $item['layanan'] === 'TCM MTB Rif (Xpert)')
                                    <table class="table table-bor border-dark border mt- 1 text-sm"
                                        style="margin-left: 25px; width: 90%;">
                                        <tr>
                                            <td class="px-2 py-0 text-center" style="width: 33.3%;">No.Reg.Lab</td>
                                            <td class="px-2 py-0 text-center" style="width: 33.3%;">No.Sediaan</td>
                                            <td class="px-2 py-0 text-center" style="width: 33.3%;">Hasil</td>
                                        </tr>
                                        <tr>
                                            <td class="p-2 h-10" style="height: 52px;"></td>
                                            <td class="p-2 h-10" style="height: 52px;"></td>
                                            <td class="p-2 h-10" style="height: 52px;"></td>
                                        </tr>
                                    </table>
                                @elseif (!empty($item['keterangan']))
                                    - ({{ $item['keterangan'] }})
                                @endif
                            </td>

                            @if ($loop->iteration % 4 == 0 || $loop->last)
                                @php
                                    // Hitung sisa kolom jika di akhir dan belum genap 4
                                    $sisa = 4 - ($loop->iteration % 4);
                                @endphp

                                @if ($loop->last && $loop->iteration % 4 != 0)
                                    {{-- @for ($i = 0; $i < $sisa; $i++) --}}
                                    <td class="pt-3" width="65%">
                                        <table class="table table-bor border-dark border" width="100%"
                                            style="border-size: 2px; color: black;">
                                            <thead>
                                                {{-- ambil permintaan selain tcm --}}
                                                @foreach ($permintaanNonTcm as $item)
                                                    @if ($loop->first || ($loop->iteration - 1) % 3 == 0)
                                                        <tr>
                                                    @endif

                                                    <td class="font-weight-bold py-2" width="33.3%">
                                                        <i class="fas fa-check-circle text-success mr-1"></i>
                                                        <span title="{{ $item['layanan'] }}">
                                                            {{ \Illuminate\Support\Str::limit($item['layanan'], 20) }}
                                                        </span>

                                                        @if (!empty($item['keterangan']))
                                                            - ({{ $item['keterangan'] }})
                                                        @endif
                                                    </td>

                                                    @if ($loop->iteration % 3 == 0 || $loop->last)
                                                        @php
                                                            // Hitung sisa kolom jika di akhir dan belum genap 4
                                                            $sisa = 3 - ($loop->iteration % 3);
                                                        @endphp

                                                        @if ($loop->last && $loop->iteration % 3 != 0)
                                                            @for ($i = 0; $i < $sisa; $i++)
                                                                <td width="33.3%"></td>
                                                            @endfor
                                                        @endif

                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </thead>
                                        </table>
                                    </td>
                                    {{-- @endfor --}}
                                @endif
                                </tr>
                            @endif
                        @endforeach
                    </thead>
                </table>
            </div>
        @else
            <div style=" margin-top: 15px;">
                <table class="table table-bor border-dark border" width="100%"
                    style="border-size: 2px; color: black;">
                    <thead>
                        @foreach ($permintaan as $item)
                            @if ($loop->first || ($loop->iteration - 1) % 4 == 0)
                                <tr>
                            @endif

                            <td class="font-weight-bold py-2" width="25%">
                                <i class="fas fa-check-circle text-success mr-1"></i>
                                {{ $item['layanan'] }}

                                @if ($item['layanan'] === 'TCM XDR (Xpert)' || $item['layanan'] === 'TCM MTB Rif (Xpert)')
                                    <table class="mt- 1 text-sm" style="margin-left: 25px;">
                                        <tr>
                                            <td class="px-2 py-0 text-center" style="width: 108px;">No.Reg.Lab</td>
                                            <td class="px-2 py-0 text-center" style="width: 108px;">No.Sediaan</td>
                                            <td class="px-2 py-0 text-center" style="width: 108px;">Hasil</td>
                                        </tr>
                                        <tr>
                                            <td class="p-2 h-10" style="height: 52px;"></td>
                                            <td class="p-2 h-10" style="height: 52px;"></td>
                                            <td class="p-2 h-10" style="height: 52px;"></td>
                                        </tr>
                                    </table>
                                @elseif (!empty($item['keterangan']))
                                    - ({{ $item['keterangan'] }})
                                @endif
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
        @endif
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
