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
    <div class="conatiner m-3 pt-2 w-[22cm] h-[33cm]">
        <h1 class="text-center font-bold text-sm">DAFTAR REKAPITULASI PENERIMAAN DAN PENYETORAN </h1>
        <h1 class="text-center font-bold text-sm">BLUD PELAYANAN KESEHATAN</h1>
        <h1 class="text-center font-bold text-sm">TAHUN {{ $tahun }}</h1>
        <h2 class="text-center font-bold text-xs my-6">Klinik Utama Kesehatan Paru Masyarakat Kelas A</h2>
        <div class="my-4 ml-10">
            <label for="target" class="font-bold text-sm">Usulan target tahun {{ $tahun }}:</label>
            <input type="text" id="target" name="target" value="{{ $target }}" class="font-bold text-sm"
                oninput="formatCurrency(this)" />
            <script type="text/javascript">
                function formatCurrency(input) {
                    // Ambil nilai tanpa format
                    let value = input.value.replace(/[^\d]/g, "");

                    // Ubah ke format ribuan
                    value = new Intl.NumberFormat("id-ID", {
                        style: "currency",
                        currency: "IDR",
                        minimumFractionDigits: 2
                    }).format(value / 100);

                    // Tampilkan kembali
                    input.value = value.replace("Rp", "").trim(); // Hilangkan label "Rp"
                }
            </script>
        </div>

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
                        <td class="px-1 border border-black text-right">{{ $item['penerimaanRp'] }}</td>
                        <td class="px-1 border border-black text-right">{{ $item['setoranRp'] }}</td>
                        <td class="px-1 border border-black text-right">
                            @if ($item['sisa'] < 0)
                                {{ 'Rp 0,00' }}
                            @else
                                {{ $item['sisaRp'] }}
                            @endif
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td class="px-1 border border-black text-center font-bold" colspan="2">Jumlah</td>
                    <td class="px-1 border border-black text-right font-bold">
                        {{ $totalPendapatanRp }}
                    </td>
                    <td class="px-1 border border-black text-right font-bold">
                        {{ $totalSetoranRp }}
                    </td>
                    <td class="px-1 border border-black text-right font-bold">
                        {{ $totalSaldoRp }}
                    </td>
                </tr>
            </tbody>
        </table>
        <!-- Tanda Tangan -->
        @include('Laporan.Kasir.ttd')
    </div>
</body>

</html>
