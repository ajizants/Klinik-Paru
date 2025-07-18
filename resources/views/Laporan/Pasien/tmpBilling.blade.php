<div class="relative w-full border-b-2 border-black flex items-center">
    <!-- Gambar -->
    <div class="absolute w-[10%] flex justify-center items-center">
        <img src="{{ asset('img/banyumas.png') }}" class="w-20" alt="banyumas" />
    </div>
    <!-- Teks di tengah -->
    <div class="w-[100%] text-center mb-1">
        <p class="text-xs mb-0">PEMERINTAH KABUPATEN BANYUMAS</p>
        <p class="text-xs font-semibold mb-0">DINAS KESEHATAN</p>
        <p class="text-xs font-bold mb-0">KLINIK UTAMA KESEHATAN PARU MASYARAKAT KELAS A</p>
        <p style="font-size: 7px;">Jln. A. Yani Nomor 33 Purwokerto Timur, Banyumas, Jawa Tengah</p>
        <p style="font-size: 7px;">Kode Pos 53111, Telepon (0281) 635658, Pos-el bkpm_purwokerto@yahoo.com</p>
    </div>
</div>
<!-- Garis bawah tebal -->
<div class="w-full border-t-4 border-black mt-[2px]"></div>
<table class="w-full table-auto text-sm">
    <tbody>
        <tr>
            <td class="text-left pt-2">
                Tanggal :
                {{ Carbon\Carbon::parse($detailSuratKontrol['sep']['tglSep'] ?? $detailSEP['tglSep'])->locale('id')->isoFormat('DD MMMM Y') }}
            </td>
            <td class="text-right pt-2">
                Code RS : 3302040
            </td>
        </tr>
        <tr>
            <td class="text-center font-bold pb-2" colspan="2">RINCIAN BUKTI PELAYANAN PASIEN BPJS</td>
        </tr>
        <tr>
            <td colspan="2">
                <div class="flex flex-wrap gap-y-1 text-sm">
                    <div class="flex w-full">
                        <div class="w-[20%]">No. RM</div>
                        <div class="w-[35%]">:
                            {{ $detailSuratKontrol['sep']['data_rujukan']['rujukan']['peserta']['mr']['noMR'] ?? $detailSEP['peserta']['noMr'] }}
                        </div>

                        <!-- Nama -->
                        <div class="w-[15%]">Nama</div>
                        <div class="w-[50%]">:
                            {{ $detailSuratKontrol['sep']['data_rujukan']['rujukan']['peserta']['nama'] ?? $detailSEP['peserta']['nama'] }}
                        </div>
                    </div>

                    <div class="flex w-full">
                        <!-- Tgl. Lahir -->
                        <div class="w-[20%]">Tgl. Lahir</div>
                        {{-- <div class="w-[5%] text-center">:</div> --}}
                        <div class="w-[35%]">:
                            {{ $detailSuratKontrol['sep']['data_rujukan']['rujukan']['peserta']['tglLahir'] ?? $detailSEP['peserta']['tglLahir'] }}
                        </div>

                        <!-- Alamat -->
                        <div class="w-[15%]">No. SEP</div>
                        {{-- <div class="w-[5%] text-center">:</div> --}}
                        <div class="w-[50%]">: {{ $detailSuratKontrol['sep']['noSep'] ?? $detailSEP['noSep'] }}</div>
                    </div>
                </div>
            </td>
        </tr>
    </tbody>
</table>
@php
    $no = 1;
@endphp
<table class="w-full table-auto mt-2 text-sm">
    <tbody>
        <tr class="border-b border-black">
            <td class="text-left">
                {{ $no++ }}.
            </td>
            <td class="text-left">Pemeriksaan Dokter Spesialis</td>
            <td class="text-center">:</td>
            <td class="text-center">Rp.</td>
            <td class="text-right">50.000,-</td>
        </tr>
        <tr class="border-b border-black">
            <td class="text-left"> {{ $no++ }}. </td>
            <td class="text-left pr-4"> Laboratorium
                @if ($lab != null)
                    <table class="w-full table-auto mx-2">
                        <tbody>
                            @foreach ($lab as $item)
                                <tr>
                                    <td class="text-left w-5/6">{{ $item['layanan']['nmLayanan'] }}</td>
                                    <td class="text-left">Rp.</td>
                                    <td class="text-right">
                                        {{ number_format($item['totalHarga'], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </td>
            <td class="text-center align-bottom">:</td>
            <td class="text-center align-bottom">Rp.</td>
            <td class="text-right align-bottom">
                {{ number_format($totalLab, 0, ',', '.') . ',-' }}
            </td>
        </tr>
        <tr class="border-b border-black">
            <td class="text-left"> {{ $no++ }}. </td>
            <td class="text-left pr-4"> Radiologi
                @if ($ro != null)
                    <table class="w-full table-auto mx-2">
                        <tbody>
                            @foreach ($ro as $item)
                                <tr>
                                    <td class="text-left w-5/6">{{ $item['layanan']['nmLayanan'] }}</td>
                                    <td class="text-left">Rp.</td>
                                    <td class="text-right">
                                        {{ number_format($item['totalHarga'], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </td>
            <td class="text-center align-bottom">
                :
            </td>
            <td class="text-center align-bottom">
                Rp.
            </td>
            <td class="text-right align-bottom">
                {{ number_format($totalRo, 0, ',', '.') . ',-' }}
            </td>
        </tr>
        <tr class="border-b border-black">
            <td class="text-left"> {{ $no++ }}. </td>
            <td class="text-left pr-4"> Tindakan
                @if ($tindakan != null)
                    <table class="w-full table-auto mx-2">
                        <tbody>
                            @foreach ($tindakan as $item)
                                <tr>
                                    <td class="text-left w-5/6">{{ $item['layanan']['nmLayanan'] }}</td>
                                    <td class="text-left">Rp.</td>
                                    <td class="text-right">
                                        {{ number_format($item['totalHarga'], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </td>
            <td class="text-center align-bottom">:</td>
            <td class="text-center align-bottom">Rp.</td>
            <td class="text-right align-bottom">
                {{ number_format($totalTindakan, 0, ',', '.') . ',-' }}
            </td>
        </tr>
        <tr class="border-b border-black">
            <td class="text-left"> {{ $no++ }}. </td>
            <td class="text-left">Obat</td>
            <td class="text-center">:</td>
            <td class="text-center">Rp.</td>
            <td class="text-right"> {{ number_format($totalObat, 0, ',', '.') . ',-' }}</td>
        </tr>
        <tr class="border-b border-black">
            <td class="text-left"> {{ $no++ }}. </td>
            <td class="text-left">Obat Kronis</td>
            <td class="text-center">:</td>
            <td class="text-center">Rp.</td>
            <td class="text-right"> {{ number_format($totalObatKronis, 0, ',', '.') . ',-' }}</td>
        </tr>
        <tr class="border-b border-black">
            <td class="text-left"> {{ $no++ }}. </td>
            <td class="text-left">Bahan habis Pakai</td>
            <td class="text-center">:</td>
            <td class="text-center">Rp.</td>
            @php
                $totalTagihan = 50000 + $totalLab + $totalRo + $totalTindakan + $totalObat + $totalObatKronis;
            @endphp
            <td class="text-right"> {{ number_format($totalbmhp, 0, ',', '.') . ',-' }}</td>
        </tr>
        <tr class="font-bold">
            <td class="text-right pr-4" colspan="2">Total Tagihan</td>
            <td class="text-center">:</td>
            <td class="text-center">Rp.</td>
            @php
                $totalTagihan =
                    50000 + $totalLab + $totalRo + $totalTindakan + $totalObat + $totalObatKronis + $totalbmhp;
            @endphp
            <td class="text-right"> {{ number_format($totalTagihan, 0, ',', '.') . ',-' }}</td>
        </tr>
    </tbody>
</table>
<div class="flex justify-end mx-20 mt-4 text-md">
    <div>
        <div>
            Penerima
        </div>
        <div class="mt-20">
            TTD
        </div>
    </div>
</div>
