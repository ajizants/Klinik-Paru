<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cetak Permintaan Laboratorium</title>

    <style>
        .pembungkus {
            padding: 1rem;
            /* border: 1px solid black; */
            box-sizing: border-box;
            /* width: 95.5%; */
        }

        .pembungkus2 {
            padding: 1rem;
            /* border: 1px solid black; */
            box-sizing: border-box;
            /* width: 95.5%; */
        }

        .kertas {
            width: 22cm;
            /* atau bisa coba: 29.7cm 21cm */
            margin: 0.2cm 0.2cm 0.2cm 0.2cm;
            /* scale: 0.8; */
            /* border: 1px solid black; */
        }

        @media print {
            body {
                zoom: 0.8;
                /* bisa ganti ke 0.85-0.95 sesuai selera */
                margin: 0;
            }

            @page {
                .pembungkus {
                    padding: 1rem;
                    /* border: 1px solid black; */
                    box-sizing: border-box;
                    width: 22cm;
                    height: 13.8cm;
                    margin: 0.2cm;
                }

                .pembungkus2 {
                    padding: 1rem;
                    /* border: 1px solid black; */
                    box-sizing: border-box;
                    width: 22cm;
                    height: 25cm;
                    /* lebih panjang */
                    margin: 0.2cm;
                }
            }

        }
    </style>

    <link type="text/css" rel="stylesheet" href="{{ asset('css/mystyle.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('vendor/plugins/fontawesome-free/css/all.min.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="flex justify-center">
    <div class="kertas">
        <div class="pembungkus2">
            {{-- <div class="container-fluid"> --}}
            <table class="table  table-borderless " width="100%" style="color: black;">
                <tbody>
                    <tr>
                        <td colspan="6">
                            <div class="relative w-full border-b-2 border-black flex items-center">
                                <div class="w-[10%] flex justify-center items-center">
                                    <img src="{{ asset('img/banyumas.png') }}" class="w-20" alt="banyumas" />
                                </div>
                                <!-- Teks di tengah -->
                                <div class="w-[100%] text-center mb-1">
                                    <p class="text-md mb-0">PEMERINTAH KABUPATEN BANYUMAS</p>
                                    <p class="text-md font-semibold mb-0">DINAS KESEHATAN</p>
                                    <p class="text-md font-bold mb-0">KLINIK UTAMA KESEHATAN PARU MASYARAKAT KELAS A</p>
                                    <p class="text-xs">Jln. A. Yani Nomor 33 Purwokerto Timur, Banyumas, Jawa Tengah</p>
                                    <p class="text-xs">Kode Pos 53111, Telepon (0281) 635658, Pos-el
                                        bkpm_purwokerto@yahoo.com
                                    </p>
                                </div>
                                <div class="w-[10%] flex justify-center items-center">
                                    <img src="{{ asset('img/LOGO_KKPM.png') }}" class="w-20" alt="banyumas" />
                                </div>
                            </div>
                            <!-- Garis bawah tebal -->
                            <div class="w-full border-t-4 border-black mt-[2px]"></div>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="6" class="p-0">
                            <p class="mb-4"
                                style="font-size: 20px; margin-bottom: -5px; text-align: center; padding:0;margin-top: 0px; font-weight: bold;">
                                <u>PERMINTAAN LABORATORIUM</u>
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
                                $alamatTampil = strlen($alamat) > 42 ? substr($alamat, 0, 42) . '...' : $alamat;
                            @endphp

                            : {{ $alamatTampil }}

                        </td>

                    </tr>
                </tbody>
            </table>
            {{-- <div class="border border-gray-500 p-2" style="margin-top: 15px;">
                @php
                    $permintaan = collect($permintaan)
                        ->sortByDesc(function ($item) {
                            return strlen($item['layanan']);
                        })
                        ->values();
                @endphp

                <div class="flex flex-wrap gap-4 text-sm text-gray-700">
                    @foreach ($permintaan as $item)
                        <div class="flex items-start space-x-2">
                            <i class="fas fa-check-circle text-green-500 mt-1"></i>
                            <span>{{ $item['layanan'] }}</span>
                            <i class="fas fa-check-circle text-gray-200 mt-1"></i>
                        </div>
                    @endforeach
                </div>
            </div> --}}
            <div class="border border-gray-500 p-2 " style="margin-top: 15px;">
                @php
                    // Ambil hanya kolom 'layanan' dari permintaan
                    $daftarPermintaan = collect($permintaan)->pluck('layanan');

                    // Daftar layanan yang ingin ditampilkan
                    $semuaLayanan = [
                        'Hematologi Analizer 5 DIFF',
                        'Laju Endap Darah ( LED )',
                        'Golongan Darah',
                        'Anti HIV Metode Rapid',
                        'Anti HCV Elisa',
                        'Anti HIV Elisa',
                        'HIV',
                        'Sifilis (Sipilis)',
                        'Rapid Covid Antibody',
                        'Asam urat darah',
                        'Creatinin darah',
                        'Calcium darah',
                        'Glukosa darah',
                        'Natrium darah',
                        'Cholesterol',
                        'Trigliserid',
                        'Ureum darah',
                        'HbA1C',
                        'SGOT',
                        'SGPT',
                        'TCM MTB Rif (Xpert)',
                        'TCM XDR (Xpert)',
                        'BTA',
                        'Pem. Kultur Darah/Cairan Tubuh',
                        'Pem. Sensitivitas OAT Lini 2',
                        'Pem. Sensitivitas OAT Lini 1',
                    ];
                    $hematologi = ['Hematologi Analizer 5 DIFF', 'Laju Endap Darah ( LED )', 'Golongan Darah'];
                    $imunologi = [
                        'Anti HIV Metode Rapid',
                        'Anti HCV Elisa',
                        'Anti HIV Elisa',
                        'HIV',
                        'Sifilis (Sipilis)',
                        'Rapid Covid Antibody',
                    ];
                    $kimia = [
                        'Asam urat darah',
                        'Creatinin darah',
                        'Calcium darah',
                        'Glukosa darah',
                        'Natrium darah',
                        'Cholesterol',
                        'Trigliserid',
                        'Ureum darah',
                        'HbA1C',
                        'SGOT',
                        'SGPT',
                    ];
                    $bakteriologi = [
                        'TCM MTB Rif (Xpert)',
                        'TCM XDR (Xpert)',
                        'BTA',
                        'Pem. Kultur Darah/Cairan Tubuh',
                        'Pem. Sensitivitas OAT Lini 2',
                        'Pem. Sensitivitas OAT Lini 1',
                    ];
                @endphp

                <h3 class="font-semibold py-1 ml-4 underline">Hematologi</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-2 text-sm text-gray-700">
                    @foreach ($hematologi as $layanan)
                        <div class="flex items-start space-x-2">
                            @if ($daftarPermintaan->contains($layanan))
                                <i class="fas fa-check-circle text-green-500 mt-1"></i>
                            @else
                                <i class="fas fa-check-circle text-gray-200 mt-1"></i>
                                {{-- <span class="w-4"></span> Placeholder agar teks tetap sejajar --}}
                            @endif
                            <span>{{ $layanan }}</span>
                        </div>
                    @endforeach
                </div>

                <h3 class="font-semibold py-1 ml-4 underline mt-2">Imunologi</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-2 text-sm text-gray-700">
                    @foreach ($imunologi as $layanan)
                        <div class="flex items-start space-x-2">
                            @if ($daftarPermintaan->contains($layanan))
                                <i class="fas fa-check-circle text-green-500 mt-1"></i>
                            @else
                                <i class="fas fa-check-circle text-gray-200 mt-1"></i>
                                {{-- <span class="w-4"></span> Placeholder agar teks tetap sejajar --}}
                            @endif
                            <span>{{ $layanan }}</span>
                        </div>
                    @endforeach
                </div>
                <h3 class="font-semibold py-1 ml-4 underline mt-2">Bakteriologi</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-2 text-sm text-gray-700">
                    @foreach ($bakteriologi as $layanan)
                        <div class="flex items-start space-x-2">
                            @if ($daftarPermintaan->contains($layanan))
                                <i class="fas fa-check-circle text-green-500 mt-1"></i>
                            @else
                                <i class="fas fa-check-circle text-gray-200 mt-1"></i>
                                {{-- <span class="w-4"></span> Placeholder agar teks tetap sejajar --}}
                            @endif
                            <span>{{ $layanan }}</span>
                        </div>
                    @endforeach
                </div>
                <h3 class="font-semibold py-1 ml-4 underline mt-2">Kimia Darah</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2 text-sm text-gray-700">
                    @foreach ($kimia as $layanan)
                        <div class="flex items-start space-x-2">
                            @if ($daftarPermintaan->contains($layanan))
                                <i class="fas fa-check-circle text-green-500 mt-1"></i>
                            @else
                                <i class="fas fa-check-circle text-gray-200 mt-1"></i>
                                {{-- <span class="w-4"></span> Placeholder agar teks tetap sejajar --}}
                            @endif
                            <span>{{ $layanan }}</span>
                        </div>
                    @endforeach
                </div>

            </div>

        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            //window.print();
            window.addEventListener('afterprint', () => {
                window.close(); // ini akan berhasil kalau dibuka dari window.open()
            });
        });
    </script>

</body>

</html>
