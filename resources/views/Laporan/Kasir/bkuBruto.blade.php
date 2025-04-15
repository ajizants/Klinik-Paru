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
        <h1 class="text-center font-bold text-sm">BUKU KAS UMUM PENERIMAAN
        </h1>
        <h1 class="text-center font-bold text-sm">{{ $blnTahun }}</h1>
        <br>
        <br>
        <div class="text-xs flex justify-start w-full">
            <div class="w-32 ">
                <p>UPT</p>
                <p>Pimpinan</p>
                <p>Bendahara</p>
            </div>
            <div class=" ">
                <p>: KLINIK UTAMA KESEHATAN PARU MASYARAKAT KELAS A KABUPATEN BANYUMAS</p>
                <p>: dr. RENDI RETISSU</p>
                <p>: Nasirin</p>
            </div>
        </div>
        <table class="w-full text-xs table-auto border border-black mb-8">
            <thead>
                <tr class="bg-gray-100">
                    <th class="py-1 px-2 border border-black text-center">Tanggal</th>
                    <th class="py-1 px-2 border border-black text-center">Uraian</th>
                    <th class="py-1 px-2 border border-black text-center">Penerimaan</th>
                    <th class="py-1 px-2 border border-black text-center">Pengeluaran</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                    @if (
                        $item['asal_pendapatan'] == 'Klaim BPJS' ||
                            $item['asal_pendapatan'] == 'SALDO' ||
                            $item['asal_pendapatan'] == 'Bunga' ||
                            $item['asal_pendapatan'] == 'Bunga Bank' ||
                            $item['asal_pendapatan'] == 'TCM')
                        @php
                            $pengepul = $item['asal_pendapatan'];
                        @endphp
                    @else
                        @php
                            $pengepul = '-';
                        @endphp
                    @endif
                    <tr>
                        <td class="px-1 border border-black text-center">
                            {{ \Carbon\Carbon::parse($item['tanggal'])->locale('id')->isoFormat('DD MMMM YYYY') }}</td>
                        <td class="px-1 border border-black text-center">
                            {{ $item['uraian'] }}<br>{{ $item['uraian2'] }}
                        </td>
                        <td class="px-1 border border-black text-right">
                            {{ 'Rp ' . number_format($item['pendapatan'], 0, ',', '.') . ',00' }}</td>
                        <td class="px-1 border border-black text-right">
                            {{ 'Rp ' . number_format($item['setoran'], 0, ',', '.') . ',00' }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td class="px-1 border border-black text-center font-bold" colspan="2">Jumlah</td>
                    <td class="px-1 border border-black text-right font-bold">
                        {{ 'Rp ' . number_format($totalPendapatan, 0, ',', '.') . ',00' }}
                    </td>
                    <td class="px-1 border border-black text-right font-bold">
                        {{ 'Rp ' . number_format($totalPengeluaran, 0, ',', '.') . ',00' }}
                    </td>
                </tr>
                <tr>
                    <td class="px-1 border border-black text-center font-bold" colspan="2">Jumlah bulan per tanggal
                        {{ $tglAkhir }}</td>
                    <td class="px-1 border border-black text-left font-bold">
                        {{ 'Rp ' . number_format($totalPendapatan, 0, ',', '.') . ',00' }}
                    </td>
                    <td class="px-1 border border-black text-left font-bold">
                        {{ 'Rp ' . number_format($totalPengeluaran, 0, ',', '.') . ',00' }}
                    </td>
                </tr>

                <tr>
                    <td class="px-1 border border-black text-center font-bold" colspan="2">Jumlah sanpai bulan lalu
                        per tanggal {{ $tglAkhirBlnLalu }}</td>
                    <td class="px-1 border border-black text-left font-bold">
                        {{ 'Rp ' . number_format($totalPendapatanSampaiBlnLalu, 0, ',', '.') . ',00' }}
                    </td>
                    <td class="px-1 border border-black text-left font-bold">
                        {{ 'Rp ' . number_format($totalPengeluaranSampaiBlnLalu, 0, ',', '.') . ',00' }}
                    </td>
                </tr>
                <tr>
                    <td class="px-1 border border-black text-center font-bold" colspan="2">Jumlah total per tanggal
                        {{ $tglAkhir }}</td>
                    <td class="px-1 border border-black text-left font-bold">
                        {{ 'Rp ' . number_format($totalPendapatan + $totalPendapatanSampaiBlnLalu, 0, ',', '.') . ',00' }}
                    </td>
                    <td class="px-1 border border-black text-left font-bold">
                        {{ 'Rp ' . number_format($totalPengeluaran + $totalPengeluaranSampaiBlnLalu, 0, ',', '.') . ',00' }}
                    </td>
                </tr>
            </tbody>
        </table>
        <p class="text-xs ">Sisa Kas</p>
        <div class="text-xs flex justify-start w-2/3">
            <div class=" w-1/2">
                <p><u><strong>Pada hari ini tanggal, {{ $tglAkhir }}</strong></u></p>
                <p>Oleh kami dalam kas Rp. ...............</p>
            </div>
            <div class=" ">
                @php
                    $totalPendapatanSekarang = $totalPendapatan + $totalPendapatanSampaiBlnLalu;
                    $totalPengeluaranSekarang = $totalPengeluaran + $totalPengeluaranSampaiBlnLalu;
                    $sisaKas = $totalPendapatanSekarang - $totalPengeluaranSekarang;
                    if ($sisaKas == 0) {
                        $sisaKas = '-';
                    }
                @endphp
                <p>Rp. <span><input type="text" name="sisa_kas" id="sisa_kas"
                            value={{ number_format($sisaKas, 0, ',', '.') . ',00' }}></span></p>
                <p>Rp. <span><input type="text" name="dalam_kas" id="dalam_kas"
                            value={{ number_format($sisaKas, 0, ',', '.') . ',00' }}></span>
                </p>
            </div>
        </div>
        <p class="text-xs ">Terdiri dari:</p>
        <div class="text-xs flex justify-start w-2/3">
            <div class=" w-1/2">
                <p>a. Tunai</p>
                <p>b. Saldo Bank</p>
                <p>c. Surat berharga</p>
            </div>
            <div class=" ">
                <p>Rp. <span><input type="text" name="tunai" id="tunai"
                            value={{ number_format($sisaKas, 0, ',', '.') . ',00' }}></span></span>
                </p>
                <p>Rp. <span><input type="text" name="saldo_bank" id="saldo_bank" value="-"></span></p>
                <p>Rp. <span><input type="text" name="surat_berharga" id="surat_berharga" value="-"></span></p>
            </div>
        </div>
        <br>
        <br>

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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            alert(
                "Sebelum mencetak, jangan lupa mengisi/memperbaharui data Sisa Kas serta melakukan koreksi data terlebih dahulu."
            );
        })
    </script>
</body>


</html>
