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
    <div class="conatiner m-3 pt-2">
        <h1 class="text-center font-bold text-sm">DAFTAR REKAPITULASI PENERIMAAN DAN PENYETORAN </h1>
        <h1 class="text-center font-bold text-sm">Periode Tanggal: {{ $tglAwalIdn }} s.d. {{ $tglAkhirIdn }}</h1>
        <br>
        <table class="w-full text-xs table-auto border border-black mb-8">
            <thead>
                <tr class="bg-gray-100">
                    <th class="py-1 px-2 border border-black text-center" rowspan="2">URAIAN</th>
                    <th class="py-1 px-2 border border-black text-center" rowspan="2">KODE REKENING</th>
                    <th class="py-1 px-2 border border-black text-center" rowspan="2">TARGET PENDAPATAN</th>
                    <th class="py-1 px-2 border border-black text-center"colspan="3">PENERIMAAN</th>
                    <th class="py-1 px-2 border border-black text-center"colspan="3">PENYETORAN</th>
                    <th class="py-1 px-2 border border-black text-center"rowspan="2">SALDO</th>
                </tr>
                <tr class="bg-gray-100">
                    <th class="py-1 px-2 border border-black text-center">S.D. BULAN LALU</th>
                    <th class="py-1 px-2 border border-black text-center">BULAN INI</th>
                    <th class="py-1 px-2 border border-black text-center">S.D. BULAN INI</th>
                    <th class="py-1 px-2 border border-black text-center">S.D. BULAN LALU</th>
                    <th class="py-1 px-2 border border-black text-center">BULAN INI</th>
                    <th class="py-1 px-2 border border-black text-center">S.D. BULAN INI</th>
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
                    <th class="py-1 px-2 border border-black text-center">(9)</th>
                    <th class="py-1 px-2 border border-black text-center">(10)</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <tr>
                    <td class="py-1 px-2 border border-black text-center">Pendapatan Pelayanan Kesehatan</td>
                    <td class="py-1 px-2 border border-black" colspan="9">100 3000 100</td>
                </tr>
            </tbody>
        </table>
        <script>
            const dataPendapatanBlnIni = @json($res['dataPendapatanBlnIni']);
            const dataPendapatanBlnLalu = @json($res['dataPendapatanBlnLalu']);
            const dataSetoranBlnIni = @json($res['dataSetoranBlnIni']);
            const dataSetoranBlnLalu = @json($res['dataSetoranBlnLalu']);

            const tbody = document.getElementById("table-body");

            // Inisialisasi variabel total
            let totalPenerimaanBlnLalu = 0;
            let totalPenerimaanBlnIni = 0;
            let totalPenerimaanSdBlnIni = 0;
            let totalPenyetoranBlnLalu = 0;
            let totalPenyetoranBlnIni = 0;
            let totalPenyetoranSdBlnIni = 0;
            let totalSaldo = 0;

            for (const key in dataPendapatanBlnIni) {
                const penerimaanBlnIni = dataPendapatanBlnIni[key];
                const penerimaanBlnLalu = dataPendapatanBlnLalu[key];
                const penyetoranBlnIni = dataSetoranBlnIni[key];
                const penyetoranBlnLalu = dataSetoranBlnLalu[key];

                const penerimaanSdBlnIni = penerimaanBlnLalu + penerimaanBlnIni;
                const penyetoranSdBlnIni = penyetoranBlnLalu + penyetoranBlnIni;
                const saldo = penerimaanSdBlnIni - penyetoranSdBlnIni;

                // Tambahkan nilai ke total
                totalPenerimaanBlnLalu += penerimaanBlnLalu;
                totalPenerimaanBlnIni += penerimaanBlnIni;
                totalPenerimaanSdBlnIni += penerimaanSdBlnIni;
                totalPenyetoranBlnLalu += penyetoranBlnLalu;
                totalPenyetoranBlnIni += penyetoranBlnIni;
                totalPenyetoranSdBlnIni += penyetoranSdBlnIni;
                totalSaldo += saldo;

                // Tambahkan baris data
                const row = `
                    <tr>
                        <td class="py-1 px-2 border border-black">${key}</td>
                        <td class="py-1 px-2 border border-black">-</td>
                        <td class="py-1 px-2 border border-black text-right">-</td>
                        <td class="py-1 px-2 border border-black text-right">${penerimaanBlnLalu.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                        <td class="py-1 px-2 border border-black text-right">${penerimaanBlnIni.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                        <td class="py-1 px-2 border border-black text-right">${penerimaanSdBlnIni.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                        <td class="py-1 px-2 border border-black text-right">${penyetoranBlnLalu.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                        <td class="py-1 px-2 border border-black text-right">${penyetoranBlnIni.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                        <td class="py-1 px-2 border border-black text-right">${penyetoranSdBlnIni.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                        <td class="py-1 px-2 border border-black text-right">${saldo.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                    </tr>
                `;

                tbody.innerHTML += row;
            }

            // Tambahkan baris jumlah total
            const rowJumlah = `
                <tr>
                    <td class="py-1 px-2 border border-black font-bold text-center" colspan="3">Jumlah</td>
                    <td class="py-1 px-2 border border-black text-right">${totalPenerimaanBlnLalu.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                    <td class="py-1 px-2 border border-black text-right">${totalPenerimaanBlnIni.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                    <td class="py-1 px-2 border border-black text-right">${totalPenerimaanSdBlnIni.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                    <td class="py-1 px-2 border border-black text-right">${totalPenyetoranBlnLalu.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                    <td class="py-1 px-2 border border-black text-right">${totalPenyetoranBlnIni.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                    <td class="py-1 px-2 border border-black text-right">${totalPenyetoranSdBlnIni.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                    <td class="py-1 px-2 border border-black text-right">${totalSaldo.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                </tr>
            `;

            tbody.innerHTML += rowJumlah;
        </script>

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
                <p>Purwokerto,
                    {{ $tglAkhirIdn }}</p>

                <p>Bendahara Penerimaan / Kasir</p>
                <div class="h-16"></div>
                <p><u>NASIRIN</u></p>
                <p>NIP: 196906022007011039</p>
            </div>
        </div>
    </div>
</body>

</html>
