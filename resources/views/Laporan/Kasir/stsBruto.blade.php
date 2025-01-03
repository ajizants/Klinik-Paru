<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- @vite('resources/css/app.css') --}}
</head>

<body class="text-black">
    <div class="wrapper m-3 pt-2">
        <h1 class="text-center font-bold text-sm">REGISTER SURAT TANDA SETORAN ( STS ) </h1>
        <h1 class="text-center font-bold text-sm">PELAYANAN DI KLINIK UTAMA KESEHATAN PARU MASYARAKAT KELAS A KAB.
            BANYUMAS </h1>
        <br>
        <p class="font-bold text-xs">Bulan : {{ $blnTahun }}</p>
        <table class="w-full text-xs table-auto border border-black mb-8">
            <thead>
                <tr class="bg-gray-100">
                    <th class="py-1 px-2 border border-black text-center" colspan="4">SURAT TANDA BUKTI SETORAN (SBS)
                    </th>
                    <th class="py-1 px-2 border border-black text-center"colspan="2">SBS</th>
                    <th class="py-1 px-2 border border-black text-center"rowspan="2">Penyetor</th>
                    <th class="py-1 px-2 border border-black text-center"rowspan="2">Bank</th>
                </tr>
                <tr class="bg-gray-100">
                    <th class="py-1 px-2 border border-black text-center">No</th>
                    <th class="py-1 px-2 border border-black text-center">Nomor</th>
                    <th class="py-1 px-2 border border-black text-center">Tanggal</th>
                    <th class="py-1 px-2 border border-black text-center">Kode Rek</th>
                    <th class="py-1 px-2 border border-black text-center">Nomor Seri</th>
                    <th class="py-1 px-2 border border-black text-center">Nilai</th>
                </tr>
                <tr class="bg-gray-100">
                    <th class="py-1 px-2 border border-black text-center">(1)</th>
                    <th class="py-1 px-2 border border-black text-center">(2)</th>
                    <th class="py-1 px-2 border border-black text-center">(3)</th>
                    <th class="py-1 px-2 border border-black text-center">(4)</th>
                    <th class="py-1 px-2 border border-black text-center">(5)</th>
                    <th class="py-1 px-2 border border-black text-center">(6)</th>
                    <th class="py-1 px-2 border border-black text-center">(7)</th>
                    <th class="py-1 px-2 border border-black text-center">(8)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($doc as $item)
                    <tr>
                        <td class="px-1 border border-black text-center">{{ $loop->iteration }}</td>
                        <td class="px-1 border border-black text-center">{{ $item['nomor_sts'] }}</td>
                        <td class="px-1 border border-black text-center">{{ $item['tgl'] }}</td>
                        <td class="px-1 border border-black text-center">
                            {{ $item['asal_pendapatan'] === '-' ? $item['kode_rek'] : $item['asal_pendapatan'] }}
                        </td>
                        <td class="px-1 border border-black text-center"></td>
                        <td class="px-1 border border-black text-right">{{ $item['pendapatan'] }}</td>
                        <td class="px-1 border border-black text-center">Nasirin</td>
                        <td class="px-1 border border-black text-center">{{ $item['bank'] }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td class="px-1 border border-black text-center font-bold" colspan="4">Jumlah</td>
                    <td class="px-1 border border-black text-right font-bold">
                        {{ 'Rp ' . number_format($totalPendapatan, 0, ',', '.') . ',00' }}
                    </td>
                </tr>
            </tbody>
        </table>
        <!-- Tanda Tangan -->
        <div class="text-xs flex justify-between">
            <div class="w-1/2 text-center">
                <p>Mengetahui,</p>
                <p>Plt. Kepala KKPM PURWOKERTO</p>
                <div class="h-16"></div>
                <p><u>dr. RENDI RETISSU</u></p>
                <p>NIP: 19881016 201902 1 002</p>
            </div>
            <div class="w-1/2 text-center">
                <p>Purwokerto, {{ $tglAkhir }}</p>
                <p>Bendahara Penerimaan / Kasir</p>
                <div class="h-16"></div>
                <p><u>NASIRIN</u></p>
                <p>NIP: 196906022007011039</p>
            </div>
        </div>
    </div>
</body>

</html>
