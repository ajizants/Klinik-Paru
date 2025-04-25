<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Optional: You can use this to adjust the layout if needed */
        /* .wrapper {
            width: 21cm;
        } */
    </style>
    <script>
        // window.print();
        window.addEventListener('afterprint', () => {
            window.close(); // ini akan berhasil kalau dibuka dari window.open()
        });
    </script>
</head>

<body class="text-black">
    <div class="wrapper m-3 pt-2">
        <table class="w-full table-auto border border-black mb-8">
            <tbody>
                <!-- Header -->
                <tr>
                    <td class="w-1/6 py-2 border-b border-black scale-60">
                        <img src="{{ asset('img/banyumas.png') }}" class="w-14 mx-2 justify-self-center" alt="banyumas" />
                    </td>
                    <td class="w-4/6 text-center border-b border-black scale-55">
                        <p class="text-md mb-0">PEMERINTAH KABUPATEN BANYUMAS</p>
                        <p class="text-md font-semibold mb-0">DINAS KESEHATAN</p>
                        <p class="text-md font-bold mb-0">KLINIK UTAMA KESEHATAN PARU MASYARAKAT KELAS A</p>
                        <p class="text-xs">Jln. A. Yani Nomor 33 Purwokerto Timur, Banyumas, Jawa Tengah</p>
                        <p class="text-xs">Kode Pos 53111, Telepon (0281) 635658, Pos-el bkpm_purwokerto@yahoo.com</p>
                    </td>
                    <td class="w-1/6 py-2 border-b border-black scale-60">
                        <img src="{{ asset('img/LOGO_KKPM.png') }}" class="w-14 mx-2 justify-self-center"
                            alt="kkpm" />
                    </td>
                </tr>
                <!-- Identitas -->
                <tr>
                    <td colspan="3">
                        <h2 class="text-lg font-bold text-center">Obat dan BMHP</h2>
                        <table class="table-auto w-full mb-4 text-sm">
                            <tbody>
                                <tr>
                                    <td class="px-2">No RM</td>
                                    <td class="px-2">:</td>
                                    <td class="px-2">{{ $cppt['pasien_no_rm'] }} / {{ $cppt['penjamin_nama'] }} /
                                        {{ $cppt['antrean_nomor'] }} @if ($noSep != null)
                                            / {{ $noSep }}
                                        @endif
                                    </td>
                                    <td class="px-2"></td>
                                    <td class="px-2">Tanggal</td>
                                    <td class="px-2">:</td>
                                    <td class="px-2">{{ $cppt['tanggal'] }}, <span
                                            class="ml-4">{{ $cppt['ket_status_pasien_pulang'] }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-2">Nama</td>
                                    <td class="px-2">:</td>
                                    <td class="px-2">{{ $cppt['pasien_nama'] }} / {{ $cppt['umur'] }}</td>
                                    <td class="px-2"></td>
                                    <td class="px-2">BB</td>
                                    <td class="px-2">:</td>
                                    <td class="px-2">{{ $cppt['objek_bb'] }} Kg</td>
                                </tr>
                                <tr>
                                    <td class="px-2">Alamat</td>
                                    <td class="px-2">:</td>
                                    <td class="px-2">{{ $cppt['kelurahan_nama'] }},
                                        {{ $cppt['pasien_rt'] }}/{{ $cppt['pasien_rw'] }},
                                        {{ $cppt['kecamatan_nama'] }}</td>
                                    <td class="px-2"></td>
                                    <td class="px-2">Dokter</td>
                                    <td class="px-2">:</td>
                                    <td class="px-2">{{ $cppt['dokter_nama'] }}</td>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <!-- Data Obat -->
                <tr>
                    <td colspan="3" class="px-4">
                        <table class="table-auto w-full border border-black mb-4 text-sm mx-auto table-layout-fixed">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="border px-2 text-left">R/</th>
                                    <th class="border px-2 text-left">Nama Obat</th>
                                    <th class="border px-2 text-left">Jumlah</th>
                                    <th class="border px-2 text-left">Aturan Pakai</th>
                                    <th class="border px-2 text-left">Jumlah Racikan</th>
                                    <th class="border px-2 text-left">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $rowspanData = [];
                                    foreach ($obats as $obat) {
                                        $rowspanData[$obat['no_resep']] = count($obat['resep_obat_detail']);
                                    }
                                @endphp

                                @foreach ($obats as $obat)
                                    @php $firstRow = true; @endphp
                                    @foreach ($obat['resep_obat_detail'] as $detail)
                                        <tr>
                                            @if ($firstRow)
                                                <td class="border px-2 align-top"
                                                    rowspan="{{ $rowspanData[$obat['no_resep']] }}">
                                                    {{ $obat['no_resep'] }}
                                                </td>
                                            @endif
                                            <td class="border px-2">{{ $detail['nama_obat'] }}</td>
                                            <td class="border px-2">{{ $detail['jumlah_obat'] }}</td>
                                            @if ($firstRow)
                                                <td class="border px-2 align-top"
                                                    rowspan="{{ $rowspanData[$obat['no_resep']] }}">
                                                    {{ $obat['signa'] }}
                                                </td>
                                                <td class="border px-2 align-top"
                                                    rowspan="{{ $rowspanData[$obat['no_resep']] }}">
                                                    {{ $obat['jumlah_puyer'] ?? '-' }}
                                                </td>
                                                <td class="border px-2 align-top"
                                                    rowspan="{{ $rowspanData[$obat['no_resep']] }}">
                                                    {{ $obat['ket'] ?? '-' }}
                                                </td>
                                            @endif
                                        </tr>
                                        @php $firstRow = false; @endphp
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr>
                <!-- Data Tindakan -->
                <tr>
                    <td colspan="3" class="px-4">
                        <table class="table-auto w-full border border-black mb-4 text-sm mx-auto table-layout-fixed">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="border px-2">Norm</th>
                                    <th class="border px-2">Tindakan</th>
                                    <th class="border px-2">BMHP</th>
                                    <th class="border px-2">Jmlh</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($tindakanList == null)
                                    <tr>
                                        <td colspan="4" class="border px-2 text-center">Tidak ada tindakan</td>
                                    </tr>
                                @endif
                                @foreach ($tindakanList as $tindakan)
                                    @foreach ($tindakan['bmhps'] as $index => $bmhp)
                                        <tr>
                                            @if ($index == 0)
                                                <td rowspan="{{ count($tindakan['bmhps']) }}" class="border px-2">
                                                    {{ $tindakan['norm'] }}
                                                </td>
                                                <td rowspan="{{ count($tindakan['bmhps']) }}" class="border px-2">
                                                    {{ $tindakan['tindakan'] }}
                                                </td>
                                            @endif
                                            <td class="border px-2">{{ $bmhp['bmhp'] }}</td>
                                            <td class="border px-2">{{ $bmhp['qty'] }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>

                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
