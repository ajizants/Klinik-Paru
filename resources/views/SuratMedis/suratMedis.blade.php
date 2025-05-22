<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Medis</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        ol {
            list-style-type: decimal !important;
            padding-left: 1.5rem !important;
        }

        ul {
            list-style-type: disc !important;
            padding-left: 1.5rem !important;
        }

        li {
            display: list-item !important;
        }
    </style>

</head>

<body class="text-black">
    <div class="wrapper m-6 pt-2">
        <table class="w-full table-auto mb-8">
            <tbody>
                <!-- Header -->
                <tr>
                    <td class="text-center py-2 border-b-4 border-black scale-60">
                        <img src="{{ asset('img/banyumas.png') }}" class="w-16 mx-auto" alt="banyumas" />
                    </td>
                    <td class="w-4/6 text-center border-b-4 border-black scale-60">
                        <p class="text-md mb-0">PEMERINTAH KABUPATEN BANYUMAS</p>
                        <p class="text-lg font-semibold mb-0">DINAS KESEHATAN</p>
                        <p class="text-md font-bold mb-0">KLINIK UTAMA KESEHATAN PARU MASYARAKAT KELAS A</p>
                        <p class="text-xs">Jln. A. Yani Nomor 33 Purwokerto Timur, Banyumas, Jawa Tengah</p>
                        <p class="text-xs">Kode Pos 53111, Telepon (0281) 635658, Pos-el bkpm_purwokerto@yahoo.com</p>
                    </td>
                    <td class="text-center py-2 border-b-4 border-black scale-60">
                        <img src="{{ asset('img/LOGO_KKPM.png') }}" class="w-16 mx-auto" alt="kkpm" />
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="text-center border-b border-black">
                </tr>
                <!-- Isi Dokumen -->
                <tr>
                    <td colspan="3">
                        <h3 class="text-center text-lg font-bold"><u class="w-4"><strong>SURAT KETERANGAN
                                    MEDIS</strong></u>
                        </h3>
                        <p class="text-center font-bold">No.
                            {{ $pasien['noSurat'] }}
                        </p>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <div class="my-3 mx-3">
                            <p class="text-md text-justify">
                                <span class="ml-6"></span>Yang bertanda tangan di bawah ini, Dokter Klinik Utama
                                Kesehatan Paru
                                Masyarakat Kelas A menerangkan dengan mengingat sumpah pada waktu menerima jabatan
                                bahwa:
                            </p>
                            <table class="w-full ml-24 table-fixed">
                                <tr>
                                    <td class="w-48">Nama</td>
                                    <td class="text-center w-2">:</td>
                                    <td>
                                        <input type="text" name="nama" id="nama"
                                            value="{{ $pasien['nama'] }}">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="w-48">Tgl. Lahir / Umur</td>
                                    <td class="text-center w-2">:</td>
                                    <td>
                                        <input type="text" name="umur" id="umur"
                                            value="{{ $pasien['tglLahir'] }} / {{ $pasien['umur'] }}">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="w-48">Pekerjaan</td>
                                    <td class="text-center w-2">:</td>
                                    <td>
                                        <input type="text" name="pekerjaan" id="pekerjaan"
                                            value="{{ strtoupper($pasien['pekerjaan']) }}">
                                    </td>
                                </tr>

                                <tr>
                                    <td class="w-48 align-top">Alamat</td>
                                    <td class="text-center w-2 align-top">:</td>
                                    <td>
                                        <p type="text" class="w-2/4" rows="2" name="alamat" id="alamat">
                                            {{ strtoupper($pasien['alamat']) }}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="mt-2"><u class="ml-8 mt-2">Pemeriksaan Tanda
                                            Vital</u></td>
                                </tr>
                                <tr>
                                    <td class="w-48">Tensi</td>
                                    <td class="text-center w-2">:</td>
                                    <td>
                                        <input class="w-16" type="text" name="tensi" id="tensi"
                                            value="{{ $pasien['td'] == '-' ? $cppt[0]['objek_tekanan_darah'] : $pasien['td'] }}">
                                        mmHg
                                    </td>
                                </tr>
                                <tr>
                                    <td class="w-48">Nadi</td>
                                    <td class="text-center w-2">:</td>
                                    <td>
                                        <input class="w-16" type="text" name="nadi" id="nadi"
                                            value="{{ $pasien['nadi'] == '-' ? $cppt[0]['objek_nadi'] : $pasien['nadi'] }}">
                                        x/menit
                                    </td>
                                </tr>
                                <tr>
                                    <td class="w-48">Berat Badan</td>
                                    <td class="text-center w-2">:</td>
                                    <td>
                                        <input class="w-16" type="text" name="berat badan" id="bb"
                                            value="{{ $pasien['bb'] == '-' ? $cppt[0]['objek_bb'] : $pasien['bb'] }}">
                                        kg
                                    </td>
                                </tr>
                                <tr>
                                    <td class="w-48">Tinggi Badan</td>
                                    <td class="text-center w-2">:</td>
                                    <td>
                                        <input class="w-16" type="text" name="Tinggi Badan" id="tb"
                                            value="{{ $pasien['tb'] == '-' ? $cppt[0]['objek_tb'] : $pasien['tb'] }}">
                                        cm
                                    </td>
                                </tr>
                            </table>
                            <p class="text-md text-justify">
                                <span class="ml-6"></span>Telah diperiksa dengan teliti dan dinyatakan
                                <strong>{{ $pasien['hasil'] }}</strong>, Surat keterangan ini diberikan
                                untuk : <strong>{{ $pasien['keperluan'] }}</strong>.
                            <p class="text-md text-justify">
                                <span class="ml-6"></span>Demikian surat keterangan ini dibuat supaya dapat
                                dipergunakan
                                sebagaimana mestinya.
                            </p>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <div class="my-3 mx-3">
                            <p class="text-md text-justify">
                                <strong>Catatan :</strong>
                            </p>
                            <div class="mx-4 text-justify list-decimal list-inside" id="catatan">
                                {!! $pasien['catatan'] !!}
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="text-center">
                        <!-- Tanda Tangan -->
                        <div class="flex justify-between mt-5">
                            <div class="w-1/2 text-center">

                            </div>
                            <div class="w-1/2 text-center">
                                <p>Purwokerto, {{ date('d M Y') }}</p>
                                <p>Dokter,</p>
                                <div class="h-16"></div>
                                <p><strong>{{ $pasien->dok->gelar_d }} {{ $pasien->dok->biodata->nama }}
                                        {{ $pasien->dok->gelar_b }}</strong></p>
                                <p>SIP. {{ $pasien->dok->sip }}</p>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
