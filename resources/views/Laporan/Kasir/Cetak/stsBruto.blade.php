<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- @vite('resources/css/app.css') --}}
</head>

<body class="text-black flex justify-center">
    <div class="wrapper m-3 pt-2 w-[22cm] h-[33cm]">
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
                @foreach ($data as $item)
                    <tr>
                        <td class="px-1 border border-black text-center">{{ $loop->iteration }}</td>
                        <td class="px-1 border border-black text-center">{{ $item['nomor'] }}</td>
                        <td class="px-1 border border-black text-center">
                            {{ \Carbon\Carbon::parse($item['tanggal'])->locale('id')->isoFormat('DD MMMM YYYY') }}</td>
                        <td class="px-1 border border-black text-center">
                            {{ $item['asal_pendapatan'] === '3.003.25581.5' ? '3.003.25581.5' : $item['asal_pendapatan'] }}
                        </td>
                        <td class="px-1 border border-black text-center"></td>
                        <td class="px-1 border border-black text-right">
                            {{ 'Rp ' . number_format($item['setoran'], 0, ',', '.') . ',00' }}</td>
                        <td class="px-1 border border-black text-center">Nasirin</td>
                        <td class="px-1 border border-black text-center">BPD</td>
                    </tr>
                @endforeach
                <tr>
                    <td class="px-1 border border-black text-center font-bold" colspan="5">Jumlah</td>
                    <td class="px-1 border border-black text-right font-bold">
                        {{ 'Rp ' . number_format($totalPendapatan, 0, ',', '.') . ',00' }}
                    </td>
                </tr>
            </tbody>
        </table>
        <!-- Tanda Tangan -->
        @include('Laporan.Kasir.Cetak.ttd')
    </div>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            alert(
                "Sebelum mencetak, jangan melakukan koreksi data terlebih dahulu."
            );
        })
    </script>
</body>

</html>
