<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>S.Kontrol: {{ $detailSuratKontrol['sep']['peserta']['nama'] }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @page {
            size: 22cm 12cm;
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
        <div class="flex items-center justify-between align-top">
            <!-- Logo -->
            <img src="{{ asset('img/BPJS_Kesehatan.png') }}" alt="bpjslogo" style="height: 60px;">

            <!-- Judul Tengah -->
            <div class="flex-1 mx-5 text-left self-center">
                <h3 class="text-lg font-semibold">SURAT RENCANA KONTROL</h3>
                <h4 class="text-base font-medium">KKPM PURWOKERTO</h4>
            </div>

            <!-- Nomor Surat di Ujung Kanan -->
            <div class="text-right align-top">
                <h3 class="text-lg font-semibold">No. {{ $detailSuratKontrol['noSuratKontrol'] }}</h3>
                <h4 class="text-base font-medium text-white">.</h4>
            </div>
        </div>

        <table class="w-full table-auto m-6">
            <tr>
                <td class="w-1/6">Kepada Yth</td>
                <td class="my-0 py-0">
                    {{ $detailSuratKontrol['namaDokter'] }}
                </td>
            </tr>
            <tr>
                <td class="w-1/6"></td>
                <td class="my-0 py-0">
                    Sp./Sub. {{ $detailSuratKontrol['sep']['data_rujukan']['rujukan']['poliRujukan']['nama'] }}
                </td>
            </tr>
            <tr>
                <td class="w-1/6" colspan="2">Mohon Pemeriksaan dan Penanganan Lebih Lanjut :</td>
            </tr>
            <tr>
                <td class="w-1/6">No.Kartu</td>
                <td class="my-0 py-0">
                    : {{ $detailSuratKontrol['sep']['peserta']['noKartu'] }}
                </td>
            </tr>
            <tr>
                <td class="w-1/6">Nama Peserta</td>
                <td class="my-0 py-0">
                    : {{ $detailSuratKontrol['sep']['peserta']['nama'] }}
                </td>
            </tr>
            <tr>
                <td class="w-1/6">Tgl.Lahir</td>
                <td class="my-0 py-0">
                    :
                    {{ \Carbon\Carbon::parse($detailSuratKontrol['sep']['peserta']['tglLahir'])->locale('id')->isoFormat('DD MMMM Y') }}
                </td>
            </tr>
            <tr>
                <td class="w-1/6">Diagnosa</td>
                <td class="my-0 py-0">
                    : {{ $detailSuratKontrol['sep']['data_rujukan']['rujukan']['diagnosa']['kode'] }} -
                    {{ $detailSuratKontrol['sep']['data_rujukan']['rujukan']['diagnosa']['nama'] }}
                </td>
            </tr>
            <tr>
                <td class="w-1/6">Rencana Kontrol</td>
                <td class="my-0 py-0">
                    :
                    {{ \Carbon\Carbon::parse($detailSuratKontrol['tglRencanaKontrol'])->locale('id')->isoFormat('DD MMMM Y') }}
                </td>
            </tr>
            <tr>
                <td class="w-1/6">Masa Berlaku Rujukan</td>
                <td class="my-0 py-0">
                    :
                    {{ \Carbon\Carbon::parse($detailSuratKontrol['sep']['provPerujuk']['tglRujukan'])->addDays(90)->locale('id')->isoFormat('DD MMMM Y') }}
                </td>
            </tr>

            <tr>
                <td class="mt-2" colspan="2">Demikian atas bantuanya, diucapkan banyak terima kasih.</td>
            </tr>

        </table>
        <div class="flex items-center justify-between align-top">
            <div>
                <br>
                <br>
                <br>
                <br>
                <p class="text-xs">Tgl.Entri: {{ $detailSuratKontrol['tglTerbit'] }} | Tgl.Cetak:
                    {{ \Carbon\Carbon::now() }} | Tgl.Rujukan:
                    {{ $detailSuratKontrol['sep']['provPerujuk']['tglRujukan'] }}</p>
            </div>
            <div class="mx-24">
                <h6>Mengetahui DPJP,</h6>
                <br>
                <br>
                <br>
                <p>{{ $detailSuratKontrol['namaDokterPembuat'] }}</p>
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

        // load langsung cetak
        // document.addEventListener("DOMContentLoaded", function() {
        //     window.print();
        //     window.onafterprint = function() {
        //         window.close();
        //     }
        // })
    </script>
</body>

</html>
