<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* .wrapper {
            width: 21cm;
        } */
    </style>
</head>

<body class="text-black">
    <div class="wrapper m-3 pt-2">
        <table class="w-full table-auto border border-black mb-8">
            <tbody>
                <!-- Header -->
                <tr>
                    <td class="text-center py-2 border-b border-black scale-60">
                        <img src="{{ asset('img/banyumas.png') }}" class="w-14 mx-2" alt="banyumas" />
                    </td>
                    <td class="w-3/6 text-center border-b border-black scale-55">
                        <p class="text-md mb-0">PEMERINTAH KABUPATEN BANYUMAS</p>
                        <p class="text-md font-semibold mb-0">DINAS KESEHATAN</p>
                        <p class="text-md font-bold mb-0">KLINIK UTAMA KESEHATAN PARU MASYARAKAT KELAS A</p>
                        <p class="text-xs">Jln. A. Yani Nomor 33 Purwokerto Timur, Banyumas, Jawa Tengah</p>
                        <p class="text-xs">Kode Pos 53111, Telepon (0281) 635658</p>
                        <p class="text-xs">Pos-el bkpm_purwokerto@yahoo.com</p>
                    </td>
                    <td class="text-center py-2 border-b border-black scale-60">
                        <img src="{{ asset('img/LOGO_KKPM.png') }}" class="w-14 mr-6" alt="kkpm" />
                    </td>
                    <td class="w-2/6 py-2 border border-black">
                        <div class="w-full mx-2">
                            <div class="text-sm font-bold">
                                SURAT BUKTI SETORAN ( SBS )
                            </div>
                            <div class="flex py-0">
                                <div class="w-1/4">No</div>
                                <div class="w-fil mx-2">:</div>
                                <div class="flex-1">{{ $doc['nomor'] }}</div>
                            </div>
                            <div class="flex py-0">
                                <div class="w-1/4">Tanggal</div>
                                <div class="w-fil mx-2">:</div>
                                <div class="flex-1">{{ $doc['tgl_nomor'] }}</div>
                            </div>
                            <div class="flex py-0">
                                <div class="w-1/4">Bank</div>
                                <div class="w-fil mx-2">:</div>
                                <div class="flex-1">Bang Jateng</div>
                            </div>
                            <div class="flex py-0">
                                <div class="w-1/4">No. Rek</div>
                                <div class="w-fil mx-2">:</div>
                                <div class="flex-1">3-003-25581-5</div>
                            </div>
                        </div>
                    </td>
                </tr>
                <!-- Isi Dokumen -->
                <tr>
                    <td colspan="4">
                        <div class="my-3 mx-3">
                            <div class="w-full text-left ml-10">
                                <div class="flex py-0">
                                    <div class="w-2/6">Harap diterima uang sebesar</div>
                                    <div class="w-fil mx-2">:</div>
                                    <div class="flex-1"> {{ $doc['pendapatan'] }}</div>
                                </div>
                                <div class="flex py-0">
                                    <div class="w-2/6">Dengan huruf</div>
                                    <div class="w-fil mx-2">:</div>
                                    <div class="flex-1"> {{ $doc['terbilang'] }}</div>
                                </div>
                            </div>
                            <br>

                            <table class="w-full table-auto">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="py-1 px-2 border border-black text-center">No</th>
                                        <th class="py-1 px-2 border border-black text-center">Kode Akun</th>
                                        <th class="py-1 px-2 border border-black text-center">Uraian Akun
                                        </th>
                                        <th class="py-1 px-2 border border-black text-center">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="py-1 px-2 border border-black text-center">1</td>
                                        <td class="py-1 px-2 border border-black text-center">102010041411
                                        </td>
                                        <td class="py-1 px-2 border border-black">
                                            <p>Pend. Jasa Pel Rawat Jalan 1</p>
                                            <p>Tanggal disetorkan: {{ $doc['tgl_setor'] }}</p>
                                        </td>
                                        <td class="py-1 px-2 border border-black text-left">
                                            {{ $doc['pendapatan'] }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="py-1 px-2 border border-black text-right">
                                        </td>
                                        <td class="py-1 px-2 border border-black text-left">
                                            {{ $doc['pendapatan'] }}</td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="4" class="text-center">
                        <!-- Tanda Tangan -->
                        <div class="flex justify-between">
                            <div class="w-1/2 text-center">
                                <p>Yang menyerahkan,</p>
                                <p>Bendahara Penerimaan / Kasir</p>
                                <div class="h-16"></div>
                                <p>NASIRIN</p>
                                <p>196906022007011039</p>
                            </div>
                            <div class="w-1/2 text-center">
                                <p>Yang menerima,</p>
                                <p>Pejabat Keuangan</p>
                                <div class="h-16"></div>
                                <p>SITI ASMINATUN JARIAH, SE</p>
                                <p>196708081988032010</p>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
