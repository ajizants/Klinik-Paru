<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SEP: {{ $detailSEP['peserta']['nama'] }}</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        @page {
            size: 22cm 14cm;
            /* lebar x tinggi */
            margin: 0.2cm;
            /* atur sesuai kebutuhan */
        }

        @media print {
            body {
                zoom: 0.9;
                /* bisa ganti ke 0.85-0.95 sesuai selera */
            }
        }
    </style>

</head>

<body>
    <div class="p-4 border border-black w-full">
        <div class="mb-3 flex items-center gap-4">
            <img src="{{ asset('img/BPJS_Kesehatan.png') }}" alt="bpjslogo" class="h-[70px]">
            <div class="mx-3">
                <h3 class="m-0 text-lg font-semibold">SURAT ELEGIBILITAS PESERTA</h3>
                <h4 class="m-0 text-base">KKPM PURWOKERTO</h4>
            </div>
        </div>
        <table class="w-full text-sm">
            <tr>
                <td class="font-bold py-0 my-0">No. SEP</td>
                <td class="py-0 my-0">: {{ $detailSEP['noSep'] }}</td>
            </tr>
            <tr>
                <td class="font-bold py-0 my-0">Tgl. SEP</td>
                <td class="py-0 my-0">:
                    {{ Carbon\Carbon::parse($detailSEP['tglSep'])->locale('id')->isoFormat('DD MMMM Y') }}</td>
                <td class="font-bold py-0 my-0">Peserta</td>
                <td class="py-0 my-0">: {{ $detailSEP['peserta']['jnsPeserta'] }}</td>
            </tr>
            <tr>
                <td class="font-bold py-0 my-0">No. Kartu</td>
                <td class="py-0 my-0">: {{ $detailSEP['peserta']['noKartu'] }} (MR.{{ $detailSEP['peserta']['noMr'] }})
                </td>
            </tr>
            <tr>
                <td class="font-bold py-0 my-0">Nama Peserta</td>
                <td class="py-0 my-0">: {{ $detailSEP['peserta']['nama'] }}</td>
                <td class="font-bold py-0 my-0">Jns. Rawat</td>
                <td class="py-0 my-0">: {{ $detailSEP['jnsPelayanan'] }}</td>
            </tr>
            <tr>
                <td class="font-bold py-0 my-0">Tgl. Lahir</td>
                <td class="py-0 my-0">: {{ $detailSEP['peserta']['tglLahir'] }} Kelamin:
                    {{ $detailSEP['peserta']['kelamin'] }}</td>
                <td class="font-bold py-0 my-0">Jns. Kunjungan</td>
                <td class="py-0 my-0">: - {{ $detailSEP['tujuanKunj']['nama'] }}</td>
            </tr>
            <tr>
                <td class="font-bold py-0 my-0">No. Telepon</td>
                <td class="py-0 my-0">: {{ $detailSEP['peserta']['no_telepon'] ?? '-' }}</td>
                <td class="font-bold py-0 my-0"></td>
                <td class="py-0 my-0">: - {{ $detailSEP['flagProcedure']['nama'] }}</td>
            </tr>
            <tr>
                <td class="font-bold py-0 my-0">Sub/Spesialis</td>
                <td class="py-0 my-0">: {{ $detailSEP['poli'] }}</td>
                <td class="font-bold py-0 my-0">Poli Perujuk</td>
                <td class="py-0 my-0">: -</td>
            </tr>
            <tr>
                <td class="font-bold py-0 my-0">Dokter</td>
                <td class="py-0 my-0">: {{ $detailSEP['dpjp']['nmDPJP'] }}</td>
                <td class="font-bold py-0 my-0">Kls. Hak</td>
                <td class="py-0 my-0">: {{ $detailSEP['kelasRawat'] }}</td>
            </tr>
            <tr>
                <td class="font-bold py-0 my-0">Faskes Perujuk</td>
                <td class="py-0 my-0">: -</td>
                <td class="font-bold py-0 my-0">Kls. Rawat</td>
                <td class="py-0 my-0">: {{ $detailSEP['kelasRawat'] }}</td>
            </tr>
            <tr>
                <td class="font-bold py-0 my-0">Diagnosa Awal</td>
                <td class="py-0 my-0">: {{ $detailSEP['diagnosa'] }}</td>
                <td class="font-bold py-0 my-0">Penjamin</td>
                <td class="py-0 my-0">: {{ $detailSEP['penjamin'] ?? '-' }}</td>
            </tr>
        </table>

        <p class="mb-0 font-semibold mt-4 text-xs">Catatan:</p>
        <div class="flex flex-wrap mt-2">
            <div class="w-7/12" style="font-size: 7pt">
                <p class="mb-0">*Saya menyetujui BPJS Kesehatan untuk:</p>
                <ul class="list-disc ml-5">
                    <li>membuka dan atau menggunakan informasi medis Pasien untuk keperluan administrasi, pembayaran
                        asuransi atau jaminan pembiayaan kesehatan</li>
                    <li>memberikan akses informasi medis atau riwayat pelayanan kepada dokter/tenaga medis pada KKPM
                        PURWOKERTO untuk kepentingan pemeliharaan kesehatan, pengobatan, penyembuhan, dan perawatan
                        Pasien</li>
                </ul>

                <p>*Saya mengetahui dan memahami:</p>
                <ul class="list-disc ml-5">
                    <li>Rumah Sakit dapat melakukan koordinasi dengan PT Jasa Raharja / PT Taspen / PT ASABRI / BPJS
                        Ketenagakerjaan atau Penjamin lainnya, jika Peserta merupakan pasien yang mengalami kecelakaan
                        lalulintas dan/atau kecelakaan kerja</li>
                    <li>SEP bukan sebagai bukti penjaminan peserta</li>
                </ul>
                <p>**Dengan tampilnya luaran SEP elektronik ini merupakan hasil validasi terhadap
                    eligibilitas Pasien secara elektronik (validasi finger print atau biometrik / sistem validasi lain)
                    dan selanjutnya Pasien dapat mengakses pelayanan kesehatan rujukan sesuai ketentuan berlaku.
                    Kebenaran dan keaslian atas informasi data Pasien menjadi tanggung jawab penuh FKRTL</p>
            </div>
            <div class="w-1/12"></div>
            <div class="w-4/12 text-left mt-4 md:mt-0">
                <h6 class="font-semibold">Persetujuan</h6>
                <h6 class="font-semibold">Pasien/Keluarga Pasien</h6>
                {!! $qrCode !!}
                <p class="mt-2">{{ $detailSEP['peserta']['nama'] }}</p>
            </div>
        </div>
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
