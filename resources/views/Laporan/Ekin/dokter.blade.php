<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $title }}</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@300;700;900&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
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

</head>

<body>
    <div class="container-fluid mx-5" style="height: 12in;">
        <table class="table table-borderless mb-7" width="100%" style="color: black;">
            <tbody>
                <tr>
                    <td colspan="2" width="20%">
                        <div>
                            <img src="{{ asset('img/banyumas.png') }}"
                                style="width: 90px; display: block; margin: auto;">
                        </div>
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
                    <td colspan="2" width="20%" class="text-center">
                        <div>
                            <img src="{{ asset('img/LOGO_KKPM.png') }}"
                                style="width: 90px; display: block; margin: auto;">
                        </div>
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
                        @php
                            $namaBulan = $tgl->locale('id')->translatedFormat('F'); // Contoh: "Maret"
                            $tahun = $tgl->year;
                        @endphp

                        <p
                            style="font-size: 20px; margin-bottom: -5px; text-align: center; padding:0;margin-top: 7px; font-weight: bold;">
                            Laporan Jumlah Pelayanan Bulan {{ $namaBulan }} Tahun {{ $tahun }}
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>
        <table class="mx-4" width="100%">
            <tbody>
                <tr>
                    <td width="20%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">Nama</td>
                    <td width="80%" class="my-0 py-0" style=" text-align: left;">:
                        <input type="text" name="nama" id="nama" value="{{ $biodata['nama'] ?? '-' }}"
                            style="border: none;width: 500px;">
                    </td>
                </tr>
                <tr>
                    <td width="20%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">NIP</td>
                    <td width="80%" class="my-0 py-0" style=" text-align: left;">:
                        <input type="text" name="nip" value="{{ $biodata['nip'] ?? '-' }}" id="nip"
                            style="border: none;width: 500px;">
                    </td>
                </tr>
                <tr>
                    <td width="20%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">Pangkat/Gol.
                        Ruang</td>
                    <td width="80%" class="my-0 py-0" style=" text-align: left;">:
                        <input type="text" name="pangkat" id="pangkat" value="{{ $biodata['pangkat'] ?? '-' }}"
                            style="border: none;width: 500px;">
                    </td>
                </tr>
                <tr>
                    <td width="20%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">Jabatan</td>
                    <td width="80%" class="my-0 py-0" style=" text-align: left;">:
                        <input type="text" name="jabatan" id="jabatan" value="{{ $biodata['jabatan'] ?? '-' }}"
                            style="border: none;width: 500px;">
                    </td>
                </tr>
            </tbody>
        </table>
        <table class="table-bor mb-0 mt-3" width="100%">
            <thead>
                <tr>
                    <th width="17%" class="my-0 py-0" style="text-align:center; font-weight: bold;">NO</th>
                    <th width="60%" class="my-0 py-0" style="text-align:center; font-weight: bold;">JENIS PELAYANAN
                    </th>
                    <th width="25%" class="my-0 py-0" style="text-align:center; font-weight: bold;">JUMLAH</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="my-0 py-0" style=" font-weight: bold; padding-left:10rem;" colspan="3">
                        A. Pengkajian</td>
                </tr>
                <tr>
                    <td class="my-0 py-0 px-5" style="padding-left:2rem; padding-right:2rem; text-align: left;">1.
                    </td>
                    <td class="my-0 py-0" style="text-align: left;">Anamnesa pasien</td>
                    <td class="my-0 py-0" style="text-align: center;">
                        <input type="text" name="injeksi" style="text-align: center; border: none"
                            value="{{ $poinKominfo['ruangpolidoktercppt'] ?? '-' }}">
                    </td>
                </tr>
                <tr>
                    <td class="my-0 py-0" style="font-weight: bold; padding-left:10rem;" colspan="3">
                        B. Implementasi</td>
                </tr>
                @php $no = 1; @endphp
                @if ($poinDots['jumlahBaru'] > 0 || $poinDots['jumlahLama'] > 0)
                    <tr>
                        <td class="my-0 py-0" px-5 style="padding-left:2rem; padding-right:2rem; text-align: left;">
                            {{ $no++ }}.
                        </td>
                        <td class="my-0 py-0" style="text-align: left;">Melayani pasien baru di DOTS Center</td>
                        <td class="my-0 py-0" style="text-align: center;">
                            @if ($poinDots['jumlahBaru'] == 0)
                                <input style="text-align: center; border: none" value="-">
                            @else
                                <input style="text-align: center; border: none"
                                    value="{{ $poinDots['jumlahBaru'] ?? '-' }}">
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="my-0 py-0" px-5 style="padding-left:2rem; padding-right:2rem; text-align: left;">
                            {{ $no++ }}.
                        </td>
                        <td class="my-0 py-0" style="text-align: left;">Melayani pasien lama di DOTS Center</td>
                        <td class="my-0 py-0" style="text-align: center;">
                            @if ($poinDots['jumlahLama'] == 0)
                                <input style="text-align: center; border: none" value="-">
                            @else
                                <input style="text-align: center; border: none"
                                    value="{{ $poinDots['jumlahLama'] ?? '-' }}">
                            @endif
                        </td>
                    </tr>
                @endif
                @php
                    $implementasi = [
                        'Oksigenasi' => $poinIgd['oksigenasiperjam'] ?? '-',
                        'Nebulasi' => $poinIgd['nebulasitanpahargaobat'] ?? '-',
                        'Spirometri' => $poinIgd['spirometri'] ?? '-',
                        'Tes mantoux' => $poinIgd['mantouxtest'] ?? '-',
                        'Injeksi' => $poinIgd['injeksi'] ?? '-',
                        'Infus' => $poinIgd['infus'] ?? '-',
                        'Observasi infus' => $poinIgd['infus'] ?? '-',
                        'EKG' => $poinIgd['ekg'] ?? '-',
                        'Tindakan Pungsi/Biopsi/WSD' =>
                            ($poinIgd['punctiepleura'] ?? 0) + ($poinIgd['biopsi'] ?? 0) + ($poinIgd['wsd'] ?? 0),
                        'Konseling PITC' => $poinIgd['konselingPITC'] ?? '-',
                        'Pemeriksaan Pasien' => $poinKominfo['ruangpolidoktercppt'] ?? '-',
                    ];
                @endphp
                @foreach ($implementasi as $label => $nilai)
                    <tr>
                        <td class="my-0 py-0 px-5" style="padding-left:2rem; padding-right:2rem; text-align: left;">
                            {{ $no++ }}.</td>
                        <td style="text-align: left;">{{ $label }}</td>
                        <td style="text-align: center;">
                            <input type="text" style="text-align: center; border: none"
                                value="{{ $nilai == 0 ? '-' : $nilai }}">
                        </td>
                    </tr>
                @endforeach
                @php
                    use Illuminate\Support\Str;
                @endphp

                @foreach ($poinLain as $item)
                    @if (!Str::contains($item['kegiatan'], 'Input TCM') && !Str::contains($item['kegiatan'], 'Input SITB'))
                        <tr>
                            <td class="my-0 py-0" style="padding-left:2rem; padding-right:2rem; text-align: left;">
                                {{ $no++ }}
                            </td>
                            <td class="my-0 py-0" style="text-align: left;">
                                {{ $item['kegiatan'] }}
                                @if (!empty($item['keterangan']))
                                    : {{ $item['keterangan'] }}
                                @endif
                            </td>
                            <td class="my-0 py-0" style="text-align: center;">
                                <input style="text-align: center; border: none"
                                    value="{{ $item['total_jumlah'] ?? '-' }}">
                            </td>
                        </tr>
                    @endif
                @endforeach

                <tr>
                    <td class="my-0 py-0" style="font-weight: bold; padding-left:10rem;" colspan="3">
                        C. Pendokumentasian</td>
                </tr>
                @php $no=1;  @endphp
                <tr>
                    <td class="my-0 py-0 px-5" style="padding-left:2rem; padding-right:2rem; text-align: left;">
                        {{ $no++ }}
                    </td>
                    <td class="my-0 py-0" style="text-align: left;">Input data Rekam Medis Pasien</td>
                    <td class="my-0 py-0" style="text-align: center;">
                        <input style="text-align: center; border: none"
                            value="{{ $poinKominfo['anamnesa'] ?: '-' }}">
                    </td>
                </tr>
                <tr>
                    <td class="my-0
                        py-0 px-5"
                        style="padding-left:2rem; padding-right:2rem; text-align: left;">{{ $no++ }}
                    </td>
                    <td class="my-0 py-0" style="text-align: left;">Input data PITC SIHA</td>
                    <td class="my-0 py-0" style="text-align: center;">
                        <input style="text-align: center; border: none" value="{{ $inputPitc ?? '-' }}">
                    </td>
                </tr>
                @foreach ($poinLain as $item)
                    @if (Str::contains($item['kegiatan'], 'Input TCM') || Str::contains($item['kegiatan'], 'Input SITB'))
                        <tr>
                            <td class="my-0 py-0" style="padding-left:2rem; padding-right:2rem; text-align: left;">
                                {{ $no++ }}
                            </td>
                            <td class="my-0 py-0" style="text-align: left;">
                                {{ $item['kegiatan'] }}
                                @if (!empty($item['keterangan']))
                                    : {{ $item['keterangan'] }}
                                @endif
                            </td>
                            <td class="my-0 py-0" style="text-align: center;">
                                <input style="text-align: center; border: none"
                                    value="{{ $item['total_jumlah'] ?? '-' }}">
                            </td>
                        </tr>
                    @endif
                @endforeach
                <tr>
                    <td class="my-0 py-0 px-5" colspan="3"
                        style="padding-left:2rem; padding-right:2rem; text-align: left; border: none">
                        <!-- Tanda Tangan -->
                        <div class="flex justify-between mt-20">

                            <div class="w-1/2 text-center">
                                <p>Purwokerto, {{ $tglAkhir }}</p>
                                <p>Pegawai yang Dinilai</p>
                                <div class="h-16"></div>
                                <p><u>{{ $biodata['nama'] ?? '-' }}</u></p>
                                <p>NIP: {{ $biodata['nip'] ?? '-' }}</p>
                            </div>
                            <div class="w-1/2 text-center">
                                <p>Mengetahui,</p>
                                <p>Plt. Kepala KKPM PURWOKERTO</p>
                                <div class="h-16"></div>
                                <p><u>dr. RENDI RETISSU</u></p>
                                <p>NIP: 19881016 201902 1 002</p>
                            </div>
                        </div>
                    </td>

                </tr>

            </tbody>
        </table>


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
