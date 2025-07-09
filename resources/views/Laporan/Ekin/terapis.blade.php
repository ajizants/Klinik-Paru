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
        @include('Laporan.Ekin.head')

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
                    <td class="my-0 py-0" style="text-align: left;">Anamnesa pasien baru</td>
                    <td class="my-0 py-0" style="text-align: center;">
                        <input type="text" name="injeksi" style="text-align: center; border: none"
                            value="{{ $poinKominfo['pasienBaru'] ?? '-' }}">
                    </td>
                </tr>
                <tr>
                    <td class="my-0 py-0 px-5" style="padding-left:2rem; padding-right:2rem; text-align: left;">2.
                    </td>
                    <td class="my-0 py-0" style="text-align: left;">Anamnesa pasien lama</td>
                    <td class="my-0 py-0" style="text-align: center;">
                        <input type="text" name="injeksi" style="text-align: center; border: none"
                            value="{{ $poinKominfo['pasienLama'] ?? '-' }}">
                    </td>
                </tr>
                <tr>
                    <td class="my-0 py-0 px-5" style="padding-left:2rem; padding-right:2rem; text-align: left;">3.
                    </td>
                    <td class="my-0 py-0" style="text-align: left;">Timbang tensi</td>
                    <td class="my-0 py-0" style="text-align: center;">
                        <input type="text" name="injeksi" style="text-align: center; border: none"
                            value="{{ $poinKominfo['anamnesa'] ?? '-' }}">
                    </td>
                </tr>

                <tr>
                    <td class="my-0 py-0" style="font-weight: bold; padding-left:10rem;" colspan="3">
                        B. Implementasi</td>
                </tr>
                <tr>
                    <td class="my-0 py-0 px-5" style="padding-left:2rem; padding-right:2rem; text-align: left;">1.
                    </td>
                    <td class="my-0 py-0" style="text-align: left;">Oksigenasi</td>
                    <td class="my-0 py-0" style="text-align: center;">
                        <input type="text" name="injeksi" style="text-align: center; border: none"
                            value="{{ $poinIgd['oksigenasiperjam'] ?? '-' }}">
                    </td>
                </tr>
                <tr>
                    <td class="my-0 py-0 px-5" style="padding-left:2rem; padding-right:2rem; text-align: left;">2.
                    </td>
                    <td class="my-0 py-0" style="text-align: left;">Nebulasi</td>
                    <td class="my-0 py-0" style="text-align: center;">
                        <input type="text" name="nebu" id="nebu" style="text-align: center; border: none"
                            value={{ $poinIgd['nebulasitanpahargaobat'] ?? '-' }}>
                    </td>
                </tr>
                <tr>
                    <td class="my-0 py-0 px-5" style="padding-left:2rem; padding-right:2rem; text-align: left;">3.
                    </td>
                    <td class="my-0 py-0" style="text-align: left;">Spirometri</td>
                    <td class="my-0 py-0" style="text-align: center;">
                        <input type="text" name="injeksi" style="text-align: center; border: none"
                            value="{{ $poinIgd['spirometri'] ?? '-' }}">
                    </td>
                </tr>

                <tr>
                    <td class="my-0 py-0" px-5 style="padding-left:2rem; padding-right:2rem; text-align: left;">
                        4.
                    </td>
                    <td class="my-0 py-0" style="text-align: left;">Konsultasi Pasien</td>
                    <td class="my-0 py-0" style="text-align: center;">
                        @php
                            $konseling =
                                ($poinIgd['konsultasikesehatanlainnya'] ?? 0) + ($poinIgd['konsultasigizi'] ?? 0);
                            if ($konseling == 0) {
                                $konseling = '-';
                            }
                        @endphp
                        <input style="text-align: center; border: none" value="{{ $konseling ?? '-' }}">
                    </td>
                </tr>
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
                        @include('Laporan.Ekin.ttd')
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
