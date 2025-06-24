<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @page {
            width: 29.7cm;
            height: 21cm;
            margin: 0.5cm 0.5cm 0.5cm 0.5cm;
            orientation: landscape;
        }

        .kertas {
            width: 33cm;
            /* atau bisa coba: 29.7cm 21cm */
            margin: 0.2cm 0.2cm 0.2cm 0.2cm;
            scale: 0.8;
            /* border: 1px solid black; */
        }

        .table-bor td {
            border: 1.2px solid #000000;
            /* Hitam dalam format hex */
            color: #000000;
            /* Hitam untuk teks */
        }

        .table-noborder td {
            border: none;
        }
    </style>

</head>

<body class="flex justify-center">
    <div class="relative overflow-x-auto">
        {{-- <table class="w-full text-sm text-left  border-collapse">
            <thead class="text-xs">
                <tr>
                    <td class="text-center border border-black">
                        <h1 class="font-bold text-md whitespace-nowrap">PROGRAM TB NASIONAL</h1>
                    </td>
                    <td class="text-center">
                    </td>
                    <td class="text-center border border-black">
                        <h1 class="font-bold text-md">TB 04</h1>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td class="text-center">
                        <h1 class="font-bold text-xl">REGISTER LABORATORIUM TB</h1>
                    </td>
                    <td class="text-center border border-black">
                        <h1 class="font-bold text-md whitespace-nowrap">INDONESIA 2015</h1>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td class="text-center">
                        <h1 class="font-bold text-xl">UNTUK LABORATORIUM FASKES MIKROSKOPIS DAN TES CEPAT</h1>
                    </td>
                    <td></td>
                </tr>
            </thead>
        </table> --}}
        <table class="w-full text-sm text-left border-collapse mt-2">
            <thead class="text-xs table-bor">

                <tr>
                    <th colspan="18" style="border: none !important">
                        <div class="grid grid-cols-[1fr_2fr_1fr] text-center text-sm">
                            <div class="p-2 flex justify-start">
                                <div class="px-2">
                                    <h1 class="border border-black font-bold text-md px-2">PROGRAM TB NASIONA</h1>
                                </div>
                            </div>
                            <div class="p-2">
                                <h1 class="font-bold text-xl">REGISTER LABORATORIUM TB TAHUN {{ $tahun }}</h1>
                                <h1 class="font-bold text-xl">UNTUK LABORATORIUM FASKES MIKROSKOPIS DAN TES CEPAT</h1>
                            </div>
                            <div class="p-2 flex justify-end">
                                <div class="px-2">
                                    <h1 class="border border-black font-bold text-md px-2">TB 04</h1>
                                    <h1 class="border border-black font-bold text-md px-2">INDONESIA 2015</h1>
                                </div>
                            </div>
                        </div>
                    </th>
                </tr>
                <tr>
                    <th colspan="18" style="border: none !important">
                        <div class="w-full text-sm grid grid-cols-12 gap-y-1">
                            <!-- Baris 1 -->
                            <div class="col-span-2 font-medium">Nama Lab. Pemeriksa</div>
                            <div class="col-span-4">: KLINIK UTAMA KESEHATAN PARU MASYARAKAT KELAS A</div>
                            <div class="col-span-2"></div>
                            <div class="col-span-1 text-center text-[8pt]"></div>
                            <div class="col-span-1 text-center text-[8pt]"></div>
                            <div class="col-span-1 text-center text-[8pt]"></div>

                            <!-- Baris 2 -->
                            <div class="col-span-2 font-medium">Kabupaten/Kota</div>
                            <div class="col-span-4">: Banyumas</div>
                            <div class="col-span-2 text-right px-2"></div>
                            <div class="col-span-1 text-center text-[8pt]k"></div>
                            <div class="col-span-1 text-center text-[8pt]k"></div>
                            <div class="col-span-1 text-center text-[8pt]k"></div>

                            <!-- Baris 3 -->
                            <div class="col-span-2 font-medium">Provinsi</div>
                            <div class="col-span-4">: Jawa Tengah</div>
                            <div class="col-span-2 text-right px-2"></div>
                            <div class="col-span-1 text-center text-[8pt]k"></div>
                            <div class="col-span-1 text-center text-[8pt]k"></div>
                            <div class="col-span-1 text-center text-[8pt]k"></div>
                        </div>

                    </th>
                </tr>
                {{-- <tr>
                    <th colspan="18" style="border: none !important">
                        <div class="w-full text-sm grid grid-cols-12 gap-y-1">
                            <!-- Baris 1 -->
                            <div class="col-span-2 font-medium">Nama Lab. Pemeriksa</div>
                            <div class="col-span-4">: KLINIK UTAMA KESEHATAN PARU MASYARAKAT KELAS A</div>
                            <div class="col-span-2"></div>
                            <div class="col-span-1 text-center text-[8pt]">Diagnosis</div>
                            <div class="col-span-1 text-center text-[8pt]">Follow Up</div>
                            <div class="col-span-1 text-center text-[8pt]">Total</div>

                            <!-- Baris 2 -->
                            <div class="col-span-2 font-medium">Kabupaten/Kota</div>
                            <div class="col-span-4">: Banyumas</div>
                            <div class="col-span-2 text-right px-2">Jumlah Sediaan Positif *)</div>
                            <div class="col-span-1 text-center text-[8pt] border border-black"></div>
                            <div class="col-span-1 text-center text-[8pt] border border-black"></div>
                            <div class="col-span-1 text-center text-[8pt] border border-black"></div>

                            <!-- Baris 3 -->
                            <div class="col-span-2 font-medium">Provinsi</div>
                            <div class="col-span-4">: Jawa Tengah</div>
                            <div class="col-span-2 text-right px-2">Jumlah Sediaan Scanty *)</div>
                            <div class="col-span-1 text-center text-[8pt] border border-black"></div>
                            <div class="col-span-1 text-center text-[8pt] border border-black"></div>
                            <div class="col-span-1 text-center text-[8pt] border border-black"></div>

                            <!-- Baris 4 -->
                            <div class="col-span-2 font-medium">Tahun</div>
                            <div class="col-span-4">: <input type="text" value="2025" class="border px-1" /></div>
                            <div class="col-span-2 text-right px-2">Jumlah Sediaan Negatif *)</div>
                            <div class="col-span-1 text-center text-[8pt] border border-black"></div>
                            <div class="col-span-1 text-center text-[8pt] border border-black"></div>
                            <div class="col-span-1 text-center text-[8pt] border border-black"></div>

                            <!-- Baris 5 -->
                            <div class="col-span-2"></div>
                            <div class="col-span-4"></div>
                            <div class="col-span-2 text-right px-2">*direkap per lembar</div>
                            <div class="col-span-1 text-center text-[8pt] border border-black"></div>
                            <div class="col-span-1 text-center text-[8pt] border border-black"></div>
                            <div class="col-span-1 text-center text-[8pt] border border-black"></div>
                        </div>

                    </th>
                </tr> --}}

                <tr>
                    <th class="border border-black p-1 text-center" rowspan="2">Nomor Reg. Lab.</th>
                    <th class="border border-black p-1 text-center" rowspan="2">Nomor Identitas
                        Contoh
                        Uji</th>
                    <th class="border border-black p-1 text-center" rowspan="2" style="width: 90.5px;">Tanggal
                        Penerimaan
                        Contoh
                        Uji</th>
                    <th class="border border-black p-1 text-center" rowspan="2">Nama Lengkap Pasien
                    </th>
                    <th class="border border-black p-1 text-center" rowspan="2">No Induk Kependudukan
                    </th>
                    <th class="border border-black p-1 text-center" rowspan="2">Umur (th)</th>
                    <th class="border border-black p-1 text-center" rowspan="2">Alamt Lengkap</th>
                    <th class="border border-black p-1 text-center" rowspan="2" style="width: 70.5px; ">Nama Faskes
                        Asal
                        Contoh
                        Uji</th>
                    <th class="border border-black p-1 text-center" rowspan="2" style="width: 40px; ">Alasan
                        Peme riksaan
                        (K1)
                    </th>
                    {{-- <th class="border border-black p-1 text-center" rowspan="2">Alasan Pemeriksaan
                        (K2)
                    </th> --}}
                    <th class="border border-black p-1 text-center text-[9pt]" colspan="3">Hasil
                        Pemeriksaan Mikroskopis (BTA/Lainnya)</th>
                    <th class="border border-black p-1 text-center text-[9pt]" colspan="4">Hasil Tes
                        Cepat Dengan Xpert</th>
                    <th class="border border-black p-1 text-center text-[9pt]" rowspan="2">Tanda
                        Tangan
                    </th>
                    <th class="border border-black p-1 text-center text-[7pt]" rowspan="2">Ket.</th>
                </tr>
                <tr>
                    <th class="border border-black p-1 text-center" style="width: 90.5px;">Tgl. Hasil</th>
                    <th class="border border-black p-1 text-center">1</th>
                    <th class="border border-black p-1 text-center">2</th>
                    <th class="border border-black p-1 text-center" style="width: 50.5px;">No TCM</th>
                    <th class="border border-black p-1 text-center"style="width: 90.5px;">Tgl. Pemeriksaan</th>
                    <th class="border border-black p-1 text-center">Hasil Pemeriksaan</th>
                    <th class="border border-black p-1 text-center"style="width: 90.5px;">Tgl. Hasil Dilaporkan</th>
                </tr>
                <tr>
                    <th class="border border-black p-1 text-center">1</th>
                    <th class="border border-black p-1 text-center">2</th>
                    <th class="border border-black p-1 text-center">3</th>
                    <th class="border border-black p-1 text-center">4</th>
                    <th class="border border-black p-1 text-center">5</th>
                    <th class="border border-black p-1 text-center">6</th>
                    <th class="border border-black p-1 text-center">7</th>
                    <th class="border border-black p-1 text-center">8</th>
                    <th class="border border-black p-1 text-center">9</th>
                    <th class="border border-black p-1 text-center">10</th>
                    <th class="border border-black p-1 text-center">11</th>
                    <th class="border border-black p-1 text-center">12</th>
                    <th class="border border-black p-1 text-center">13</th>
                    <th class="border border-black p-1 text-center">14</th>
                    <th class="border border-black p-1 text-center">15</th>
                    <th class="border border-black p-1 text-center">16</th>
                    <th class="border border-black p-1 text-center">17</th>
                    <th class="border border-black p-1 text-center">18</th>
                    {{-- <th class="border border-black p-1 text-center">18</th> --}}
                </tr>
            </thead>
            <tbody class="table-bor">
                @foreach ($data as $item)
                    <tr>
                        <td class="p-1 text-center">{{ $item['no_reg_lab'] ?? 'reg lab' }}</td>
                        @if (isset($item['tb04'][0]['no_iden_sediaan']))
                            <td class="p-1 text-left">
                                25/K3302730/{{ $item['tb04'][0]['kode_tcm'] }}/{{ $item['no_iden_sediaan'] }}
                            </td>
                        @else
                            <td class="p-1 text-left">-</td>
                        @endif
                        <td class="p-1 text-left" style="width: 90.5px;">
                            {{ \Carbon\Carbon::parse($item['tgl_terima'])->format('d-m-Y') }}
                        </td>

                        <td class="p-1 text-left">{{ $item['nama'] }}
                            {{-- {{ $item['norm'] }} --}}
                        </td>
                        <td class="p-1 text-center">{{ $item['nik'] }}</td>
                        <td class="p-1 text-center">{{ $item['umur'] }}</td>
                        <td class="p-1 text-left">{{ $item['alamat'] }}</td>
                        <td class=" p-1 text-center"><input type="text" class="w-[50px] text-center"
                                value="{{ $item['namaFaskes'] ?? 'KKPM' }}">
                        </td>

                        <td class="p-1 text-center">{{ $item['tb04'][0]['alasan_periksa'] ?? 'alasan1' }}</td>
                        {{-- <td class="p-1 text-left">{{ $item['tb04'][1]['alasan_periksa'] ?? 'alasan2' }}</td> --}}
                        <td class="p-1 text-center">
                            {{ \Carbon\Carbon::parse($item['tb04'][0]['tgl_hasil'])->format('d-m-Y') }}
                        </td>

                        @if ($item['tb04'][0]['idLayanan'] == 131)
                            <td class="text-center">-</td>
                            <td class="text-center">-</td>
                            <td class="text-center">{{ $item['tb04'][0]['no_tcm'] }}</td>
                            <td class="p-1 text-center" style="width: 90.5px;">
                                {{ \Carbon\Carbon::parse($item['tb04'][0]['tgl_hasil'])->format('d-m-Y') }}
                            </td>
                            <td class="p-1 text-center">
                                {{ $item['tb04'][0]['hasil'] }}
                                {{-- {{ $item['tb04'][0]['idLayanan'] }} --}}
                            </td>
                            <td class="p-1 text-center">
                                {{ \Carbon\Carbon::parse($item['tb04'][0]['tgl_hasil'])->format('d-m-Y') }}
                            </td>
                            <td class="text-center"></td>
                        @else
                            <td class="p-1 text-center">
                                {{ $item['tb04'][0]['hasil'] }}
                                {{-- {{ $item['tb04'][0]['idLayanan'] }} --}}
                            </td>
                            <td class="p-1 text-center">
                                {{ $item['tb04'][1]['hasil'] ?? '' }}
                                {{-- {{ $item['tb04'][1]['idLayanan'] ?? '' }} --}}
                            </td>
                            <td class="text-center">-</td>
                            <td class="text-center">-</td>
                            <td class="text-center">-</td>
                            <td class="text-center">-</td>
                            <td class="text-center">-</td>
                        @endif


                        {{-- <td class=" p-1 text-center"></td>
                        <td class=" p-1 text-center"></td> --}}
                        <td class=" p-1 text-center">
                            @if (count($item['tb04']) > 1 && $item['tb04'][0]['ket'] !== null)
                                {{ $item['tb04'][0]['ket'] ?? '-' }}, {{ $item['tb04'][1]['ket'] ?? '' }}
                            @else
                                {{ $item['tb04'][0]['ket'] ?? '-' }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>


        </table>
    </div>
</body>

</html>
