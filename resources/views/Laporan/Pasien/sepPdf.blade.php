<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SEP: {{ $detailSEP['peserta']['nama'] }}</title>

    <style>
        @page {
            size: 22cm 13.8cm;
            /* atau bisa coba: 29.7cm 21cm */
            margin: 0.5cm 0.5cm 0.5cm 0.5cm;
        }

        .pembungkus {
            padding: 1rem;
            border: 1px solid black;
            width: 95.5%;
            box-sizing: border-box;
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
            height: 55px;
        }

        h3,
        h4,
        h6 {
            margin: 0;
        }
    </style>
</head>

<body>
    <div class="pembungkus">
        <table>
            <tr>
                <td width="40%"><img src="/public/img/BPJS_Kesehatan.png" alt="bpjslogo" class="h-70px"></td>
                <td>
                    <div class="mx-3" style="margin-top: 10px;">
                        <h3 class="text-lg font-semibold">SURAT ELEGIBILITAS PESERTA</h3>
                        <h4 class="text-base">KKPM PURWOKERTO</h4>
                    </div>
                </td>
            </tr>
        </table>
        <table class="text-sm" style="margin-top: 10px;">
            <tr>
                <td class="font-bold">No. SEP</td>
                <td>: {{ $detailSEP['noSep'] }}</td>
            </tr>
            <tr>
                <td class="font-bold">Tgl. SEP</td>
                <td>: {{ Carbon\Carbon::parse($detailSEP['tglSep'])->locale('id')->isoFormat('DD MMMM Y') }}</td>
                <td class="font-bold">Peserta</td>
                <td>: {{ $detailSEP['peserta']['jnsPeserta'] }}</td>
            </tr>
            <tr>
                <td class="font-bold">No. Kartu</td>
                <td>: {{ $detailSEP['peserta']['noKartu'] }} (MR.{{ $detailSEP['peserta']['noMr'] }})</td>
            </tr>
            <tr>
                <td class="font-bold">Nama Peserta</td>
                <td>: {{ $detailSEP['peserta']['nama'] }}</td>
                <td class="font-bold">Jns. Rawat</td>
                <td>: {{ $detailSEP['jnsPelayanan'] }}</td>
            </tr>
            <tr>
                <td class="font-bold">Tgl. Lahir</td>
                <td>: {{ $detailSEP['peserta']['tglLahir'] }} Kelamin: {{ $detailSEP['peserta']['kelamin'] }}</td>
                <td class="font-bold">Jns. Kunjungan</td>
                <td>: - {{ $detailSEP['tujuanKunj']['nama'] }}</td>
            </tr>
            <tr>
                <td class="font-bold">No. Telepon</td>
                <td>: {{ $detailSEP['peserta']['no_telepon'] ?? '-' }}</td>
                <td class="font-bold"></td>
                <td>: - {{ $detailSEP['flagProcedure']['nama'] }}</td>
            </tr>
            <tr>
                <td class="font-bold">Sub/Spesialis</td>
                <td>: {{ $detailSEP['poli'] }}</td>
                <td class="font-bold">Poli Perujuk</td>
                <td>: -</td>
            </tr>
            <tr>
                <td class="font-bold">Dokter</td>
                <td>: {{ $detailSEP['dpjp']['nmDPJP'] }}</td>
                <td class="font-bold">Kls. Hak</td>
                <td>: {{ $detailSEP['kelasRawat'] }}</td>
            </tr>
            <tr>
                <td class="font-bold">Faskes Perujuk</td>
                <td>: -</td>
                <td class="font-bold">Kls. Rawat</td>
                <td>: {{ $detailSEP['kelasRawat'] }}</td>
            </tr>
            <tr>
                <td class="font-bold">Diagnosa Awal</td>
                <td>: {{ $detailSEP['diagnosa'] }}</td>
                <td class="font-bold">Penjamin</td>
                <td>: {{ $detailSEP['penjamin'] ?? '-' }}</td>
            </tr>
        </table>
        <p class="mb-0 font-semibold mt-3 text-xs">Catatan:</p>
        <table style="margin-top: 0px">
            <tr>
                <td width="55%">
                    <div style="font-size: 7pt">
                        <p class="my-0">*Saya menyetujui BPJS Kesehatan untuk:</p>
                        <ul class="list-disc mt-0">
                            <li>membuka dan atau menggunakan informasi medis Pasien untuk keperluan administrasi,
                                pembayaran
                                asuransi atau jaminan pembiayaan kesehatan</li>
                            <li>memberikan akses informasi medis atau riwayat pelayanan kepada dokter/tenaga medis pada
                                KKPM
                                PURWOKERTO untuk kepentingan pemeliharaan kesehatan, pengobatan, penyembuhan, dan
                                perawatan
                                Pasien</li>
                        </ul>

                        <p class="mb-0">*Saya mengetahui dan memahami:</p>
                        <ul class="list-disc mt-0">
                            <li>Rumah Sakit dapat melakukan koordinasi dengan PT Jasa Raharja / PT Taspen / PT ASABRI /
                                BPJS
                                Ketenagakerjaan atau Penjamin lainnya, jika Peserta merupakan pasien yang mengalami
                                kecelakaan
                                lalulintas dan/atau kecelakaan kerja</li>
                            <li>SEP bukan sebagai bukti penjaminan peserta</li>
                        </ul>
                        <p class="my-0">**Dengan tampilnya luaran SEP elektronik ini merupakan hasil validasi terhadap
                            eligibilitas
                            Pasien
                            secara elektronik (validasi finger print atau biometrik / sistem validasi lain) dan
                            selanjutnya
                            Pasien dapat mengakses pelayanan kesehatan rujukan sesuai ketentuan berlaku. Kebenaran dan
                            keaslian
                            atas informasi data Pasien menjadi tanggung jawab penuh FKRTL</p>
                    </div>
                </td>
                <td width="10%"></td>
                <td>
                    <div class="text-left mt-4 text-sm">
                        <p class="font-semibold mb-0">Persetujuan</p>
                        <p class="font-semibold mt-0">Pasien/Keluarga Pasien</p>
                        {{-- {!! $qrCode !!} --}}
                        {{-- <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code" style="height: 100px;"> --}}

                        <img src="data:image/png;base64,{{ $qrCodeBase64 }}" alt="QR Code" width="90">

                        <p class="mt-2">{{ $detailSEP['peserta']['nama'] }}</p>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
