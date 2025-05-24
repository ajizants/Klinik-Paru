<div class="p-4 w-full">
    <div class="flex items-center justify-between align-top">
        <!-- Logo -->
        <img src="{{ asset('img/BPJS_Kesehatan.png') }}" alt="bpjslogo" style="height: 40px;">

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
            <td class="mt-2" colspan="2">Demikian atas bantuanya, diucapkan banyak terima kasih.</td>
        </tr>
        <tr>
            <td colspan="2" class="mt-3 w-1/6 font-semibold">
                * Masa Berlaku Rujukan :
                {{ \Carbon\Carbon::parse($detailSuratKontrol['sep']['provPerujuk']['tglRujukan'])->addDays(85)->locale('id')->isoFormat('DD MMMM Y') }}
            </td>
        </tr>
        <tr>
            <td colspan="2" class="mt-3 w-1/6 font-semibold">
                * Rencana Saat Kontrol :
                {{ $cppt['rencana_tindak_lanjut'] }}
            </td>
        </tr>

    </table>
    <div class="flex items-center justify-between align-top">
        <div>
            <br>
            <br>
            <br>
            <br>
            <p style="font-size: 10px">Tgl.Entri: {{ $detailSuratKontrol['tglTerbit'] }} | Tgl.Cetak:
                {{ \Carbon\Carbon::now() }} | Tgl.Rujukan:
                {{ $detailSuratKontrol['sep']['provPerujuk']['tglRujukan'] }}</p>
        </div>
        <div>
            <h6>Mengetahui DPJP,</h6>
            <br>
            <br>
            <br>
            <p>{{ $detailSuratKontrol['namaDokterPembuat'] }}</p>
        </div>
    </div>
</div>
