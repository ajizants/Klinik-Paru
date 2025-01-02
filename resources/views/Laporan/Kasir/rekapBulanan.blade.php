<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    @vite('resources/css/app.css')
</head>

<body class="text-black">
    <div class="conatiner m-3 pt-2">
        <h1 class="text-center font-bold text-sm">DAFTAR REKAPITULASI PENERIMAAN DAN PENYETORAN </h1>
        <h1 class="text-center font-bold text-sm">BLUD PELAYANAN KESEHATAN</h1>
        <h1 class="text-center font-bold text-sm">TAHUN {{ $tahun }}</h1>
        <h2 class="text-center font-bold text-xs my-6">Klinik Utama Kesehatan Paru Masyarakat Kelas A</h2>
        <h3 class="my-4 ml-10 font-bold text-sm">Usulan target tahun {{ $tahun }}:
            {{ 'Rp. ' . number_format($target, 0, ',', '.') . ',00' }}</h3>
        <table class="w-full text-xs table-auto border border-black mb-8">
            <thead>
                <tr class="bg-gray-100">
                    <th class="py-1 px-2 border border-black text-center" rowspan="2">NO</th>
                    <th class="py-1 px-2 border border-black text-center" rowspan="2">BULAN</th>
                    <th class="py-1 px-2 border border-black text-center"colspan="2">JUMLAH PENDAPATAN</th>
                    <th class="py-1 px-2 border border-black text-center"rowspan="2">SALDO</th>
                </tr>
                <tr class="bg-gray-100">
                    <th class="py-1 px-2 border border-black text-center">PENERIMAAN</th>
                    <th class="py-1 px-2 border border-black text-center">SETORAN</th>
                </tr>
                <tr class="bg-gray-100">
                    <th class="py-1 px-2 border border-black text-center">(1)</th>
                    <th class="py-1 px-2 border border-black text-center">(2)</th>
                    <th class="py-1 px-2 border border-black text-center">(3)</th>
                    <th class="py-1 px-2 border border-black text-center">(4)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($doc as $item)
                    <tr>
                        <td class="px-1 border border-black text-center">{{ $loop->iteration }}</td>
                        <td class="px-1 border border-black text-center">{{ $item['bulan'] }}</td>
                        <td class="px-1 border border-black text-right">{{ $item['penerimaan'] }}</td>
                        <td class="px-1 border border-black text-right">{{ $item['setoran'] }}</td>
                        <td class="px-1 border border-black text-right">{{ $item['saldo'] }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td class="px-1 border border-black text-center font-bold" colspan="2">Jumlah</td>
                    <td class="px-1 border border-black text-right font-bold">
                        {{ $totalPendapatanFormatted }}
                    </td>
                    <td class="px-1 border border-black text-right font-bold">
                        {{ $totalSetoranFormatted }}
                    </td>
                    <td class="px-1 border border-black text-right font-bold">
                        {{ $totalSaldoFormatted }}
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
                <p>Purwokerto, @php
                    $date = Carbon\Carbon::now();
                    $tglAkhir = \Carbon\Carbon::create($date)->lastOfMonth()->locale('id')->isoFormat('DD MMMM YYYY');
                @endphp
                    {{ $tglAkhir }}</p>

                <p>Bendahara Penerimaan / Kasir</p>
                <div class="h-16"></div>
                <p><u>NASIRIN</u></p>
                <p>NIP: 196906022007011039</p>
            </div>
        </div>
    </div>
</body>

</html>
