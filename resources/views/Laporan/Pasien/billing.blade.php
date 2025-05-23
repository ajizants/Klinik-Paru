<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SEP: {{ $detailSEP['peserta']['nama'] }}</title>

    <style>
        .pembungkus {
            padding: 1rem;
            border: 1px solid black;
            box-sizing: border-box;
            /* width: 95.5%; */
        }

        .pembungkus2 {
            padding: 1rem;
            border: 1px solid black;
            box-sizing: border-box;
            /* width: 95.5%; */
        }

        .flex {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .mb-3 {
            margin-bottom: 0.75rem;
        }

        .text-lg {
            font-size: 1.125rem;
        }

        .text-base {
            font-size: 1rem;
        }

        .font-semibold {
            font-weight: 600;
        }

        .text-sm {
            font-size: 0.875rem;
        }

        .text-xs {
            font-size: 0.75rem;
        }

        .w-full {
            width: 100%;
        }

        table {
            width: 100%;
            font-size: 0.875rem;
            border-collapse: collapse;
        }

        td {
            padding: 0;
            margin: 0;
            vertical-align: top;
        }

        .list-disc {
            list-style-type: disc;
            margin-bottom: 0 !important;
        }

        .ml-5 {
            margin-left: 1.25rem;
        }

        .mt-2 {
            margin-top: 0.5rem;
        }

        .mt-4 {
            margin-top: 1rem;
        }

        .mb-0 {
            margin-bottom: 0;
        }

        .mt-0 {
            margin-top: 0;
        }

        .my-0 {
            margin-bottom: 0;
            margin-top: 0;
        }

        .w-7\/12 {
            width: 58.333333%;
        }

        .w-1\/12 {
            width: 8.333333%;
        }

        .w-4\/12 {
            width: 33.333333%;
        }

        .text-left {
            text-align: left;
        }

        img.h-70px {
            height: 50px;
        }

        h3,
        h4,
        h6 {
            margin: 0;
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
                zoom: 0.9;
                /* bisa ganti ke 0.85-0.95 sesuai selera */
            }

            @page {
                .pembungkus {
                    padding: 1rem;
                    border: 1px solid black;
                    box-sizing: border-box;
                    width: 22cm;
                    height: 13.8cm;
                    margin: 0.2cm;
                }

                .pembungkus2 {
                    padding: 1rem;
                    border: 1px solid black;
                    box-sizing: border-box;
                    width: 22cm;
                    height: 25cm;
                    /* lebih panjang */
                    margin: 0.2cm;
                }
            }



        }
    </style>
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="flex justify-center">
    <div class="kertas">
        <div class="pembungkus2 mt-3">
            <div class="relative w-full border-b border-black flex items-center">
                <!-- Gambar -->
                <div class="absolute w-[10%] flex justify-center items-center">
                    <img src="{{ asset('img/banyumas.png') }}" class="w-14" alt="banyumas" />
                </div>
                <!-- Teks di tengah -->
                <div class="w-[100%] text-center scale-[0.9]">
                    <p class="text-sm mb-0">PEMERINTAH KABUPATEN BANYUMAS</p>
                    <p class="text-sm font-semibold mb-0">DINAS KESEHATAN</p>
                    <p class="text-sm font-bold mb-0">KLINIK UTAMA KESEHATAN PARU MASYARAKAT KELAS A</p>
                    <p class="text-xs">Jln. A. Yani Nomor 33 Purwokerto Timur, Banyumas, Jawa Tengah</p>
                    <p class="text-xs">Kode Pos 53111, Telepon (0281) 635658, Pos-el bkpm_purwokerto@yahoo.com</p>
                </div>
            </div>
            <table class="w-full table-auto">
                <tbody>
                    <tr>
                        <td class="text-left pt-2">
                            Tanggal :
                            {{ Carbon\Carbon::parse($detailSEP['tglSep'])->locale('id')->isoFormat('DD MMMM Y') }}
                        </td>
                        <td class="text-right pt-2">
                            Code RS : 3302040
                        </td>
                    </tr>
                    <tr>
                        <td class="text-left" colspan="2">
                            No. SEP :
                            {{ $detailSEP['noSep'] }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center font-bold pb-2" colspan="2">RINCIAN BUKTI PELAYANAN PASIEN BPJS</td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div class="flex flex-wrap gap-y-1 text-sm">
                                <div class="flex w-full">
                                    <!-- No. RM -->
                                    <div class="w-[10%]">No. RM</div>
                                    <div class="w-[5%] text-center">:</div>
                                    <div class="w-[20%]">{{ $detailSEP['peserta']['noMr'] }}</div>

                                    <!-- Nama -->
                                    <div class="w-[10%]">Nama</div>
                                    <div class="w-[5%] text-center">:</div>
                                    <div class="w-[50%]">{{ $detailSEP['peserta']['nama'] }}</div>
                                </div>

                                <div class="flex w-full">
                                    <!-- Tgl. Lahir -->
                                    <div class="w-[10%]">Tgl. Lahir</div>
                                    <div class="w-[5%] text-center">:</div>
                                    <div class="w-[20%]">{{ $detailSEP['peserta']['tglLahir'] }}</div>

                                    <!-- Alamat -->
                                    <div class="w-[10%]">Alamat</div>
                                    <div class="w-[5%] text-center">:</div>
                                    <div class="w-[50%]">{{ $dataTagihan['alamat'] ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            @php
                $no = 1;
            @endphp
            <table class="w-full table-auto mt-2">
                <tbody>
                    {{-- <tr class="border-b border-black">
                        <td class="text-left"> {{ $no++ }}. </td>
                        <td class="text-left">Biaya Rekam Medis Rawat Jalan</td>
                        <td class="text-center">:</td>
                        <td class="text-center">Rp.</td>
                        <td class="text-right">15.000,-</td>
                    </tr> --}}
                    <tr class="border-b border-black">
                        <td class="text-left">
                            {{ $no++ }}.
                        </td>
                        <td class="text-left">Pemeriksaan Dokter Spesialis</td>
                        <td class="text-center">:</td>
                        <td class="text-center">Rp.</td>
                        <td class="text-right">50.000,-</td>
                    </tr>
                    <tr class="border-b border-black">
                        <td class="text-left"> {{ $no++ }}. </td>
                        <td class="text-left pr-4"> Laboratorium
                            @if ($lab != null)
                                <table class="w-full table-auto">
                                    <tbody>
                                        @foreach ($lab as $item)
                                            <tr>
                                                <td class="text-left w-5/6">{{ $item['layanan']['nmLayanan'] }}</td>
                                                <td class="text-left">Rp.</td>
                                                <td class="text-right">
                                                    {{ number_format($item['totalHarga'], 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </td>
                        <td class="text-center align-bottom">:</td>
                        <td class="text-center align-bottom">Rp.</td>
                        <td class="text-right align-bottom">
                            {{ number_format($totalLab, 0, ',', '.') . ',-' }}
                        </td>
                    </tr>
                    <tr class="border-b border-black">
                        <td class="text-left"> {{ $no++ }}. </td>
                        <td class="text-left pr-4"> Radiologi
                            @if ($ro != null)
                                <table class="w-full table-auto mr-2">
                                    <tbody>
                                        @foreach ($ro as $item)
                                            <tr>
                                                <td class="text-left w-5/6">{{ $item['layanan']['nmLayanan'] }}</td>
                                                <td class="text-left">Rp.</td>
                                                <td class="text-right">
                                                    {{ number_format($item['totalHarga'], 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </td>
                        <td class="text-center align-bottom">
                            :
                        </td>
                        <td class="text-center align-bottom">
                            Rp.
                        </td>
                        <td class="text-right align-bottom">
                            {{ number_format($totalRo, 0, ',', '.') . ',-' }}
                        </td>
                    </tr>
                    <tr class="border-b border-black">
                        <td class="text-left"> {{ $no++ }}. </td>
                        <td class="text-left pr-4"> Tindakan
                            @if ($tindakan != null)
                                <table class="w-full table-auto mr-2">
                                    <tbody>
                                        @foreach ($tindakan as $item)
                                            <tr>
                                                <td class="text-left w-5/6">{{ $item['layanan']['nmLayanan'] }}</td>
                                                <td class="text-left">Rp.</td>
                                                <td class="text-right">
                                                    {{ number_format($item['totalHarga'], 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </td>
                        <td class="text-center align-bottom">:</td>
                        <td class="text-center align-bottom">Rp.</td>
                        <td class="text-right align-bottom">
                            {{ number_format($totalTindakan, 0, ',', '.') . ',-' }}
                        </td>
                    </tr>
                    <tr class="border-b border-black">
                        <td class="text-left"> {{ $no++ }}. </td>
                        <td class="text-left">Obat</td>
                        <td class="text-center">:</td>
                        <td class="text-center">Rp.</td>
                        <td class="text-right"> {{ number_format($totalObat, 0, ',', '.') . ',-' }}</td>
                    </tr>
                    <tr class="border-b border-black">
                        <td class="text-left"> {{ $no++ }}. </td>
                        <td class="text-left">Obat Kronis</td>
                        <td class="text-center">:</td>
                        <td class="text-center">Rp.</td>
                        <td class="text-right"> {{ number_format($totalObatKronis, 0, ',', '.') . ',-' }}</td>
                    </tr>
                    <tr class="border-b border-black">
                        <td class="text-left"> {{ $no++ }}. </td>
                        <td class="text-left">Bahan habis Pakai</td>
                        <td class="text-center">:</td>
                        <td class="text-center">Rp.</td>
                        @php
                            $totalTagihan =
                                15000 + 50000 + $totalLab + $totalRo + $totalTindakan + $totalObat + $totalObat;
                        @endphp
                        <td class="text-right"> {{ number_format($totalbmhp, 0, ',', '.') . ',-' }}</td>
                    </tr>
                    <tr class="font-bold">
                        <td class="text-right pr-4" colspan="2">Total Tagihan</td>
                        <td class="text-center">:</td>
                        <td class="text-center">Rp.</td>
                        @php
                            $totalTagihan =
                                15000 + 50000 + $totalLab + $totalRo + $totalTindakan + $totalObat + $totalObat;
                        @endphp
                        <td class="text-right"> {{ number_format($totalTagihan, 0, ',', '.') . ',-' }}</td>
                    </tr>
                </tbody>
            </table>
            <div class="flex justify-end mx-20 mt-4">
                <div>
                    <div>
                        Penerima
                    </div>
                    <div class="mt-20">
                        TTD
                    </div>
                </div>
            </div>
        </div>

    </div>
</body>

</html>
