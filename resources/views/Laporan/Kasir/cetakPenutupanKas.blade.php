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
        <h1 class="text-center font-bold text-sm">REGISTER PENUTUPAN KAS
        </h1>
        <br>
        <br>
        <div class="text-xs flex justify-start w-full">
            <div class="w-1/2 ">
                <p>Nama Penutup Kas/ Pengelola Keuangan</p>
                <p>Tanggal penutupan kas yang lalu</p>
                <p>Tanggal Penutupan Kas Sekarang</p>
            </div>
            <div class=" ">
                <p>: Nasirin</p>
                <p>: {{ \Carbon\Carbon::parse($res->tanggal_lalu)->locale('id')->isoFormat('DD MMMM YYYY') }}</p>
                <p>: {{ \Carbon\Carbon::parse($res->tanggal_sekarang)->locale('id')->isoFormat('DD MMMM YYYY') }}</p>
            </div>
        </div>
        <br>
        <div class="text-xs flex justify-start w-full">
            <div class="w-1/2 ">
                <p>Jumlah Total Penerimaan</p>
                <p>Jumlah Total Pengeluaran</p>
                <p>Saldo buku KAS umum</p>
                <p>Saldo KAS</p>
            </div>
            <div class=" ">
                <p>: {{ 'Rp ' . number_format($res->total_penerimaan, 0, ',', '.') . ',00' }} </p>
                <p>: {{ 'Rp ' . number_format($res->total_pengeluaran, 0, ',', '.') . ',00' }} </p>
                <p>: {{ 'Rp ' . number_format($res->saldo_bku, 0, ',', '.') . ',00' }} </p>
                <p>: {{ 'Rp ' . number_format($res->saldo_kas, 0, ',', '.') . ',00' }} </p>
            </div>
        </div>
        <br>
        <div class="text-xs flex justify-start w-full">
            <div class="w-1/2">
                <p>Selisih positif/negatif antara saldo Buku Kas Umum dan Saldo kas</p>
            </div>
            <div class=" ">
                <p>Rp. <span><input type="text" name="selisih" id="selisih" value={{ $res->selisih_saldo }}></span>
                </p>
            </div>
        </div>
        <table class="w-full text-xs table-auto mb-6">
            <tbody>
                <tr>
                    <td class="px-1 text-left font-bold" colspan="9">Saldo Kas terdiri dari
                    </td>
                </tr>
                <tr>
                    <td class="px-1 text-left font-bold" colspan="9"><strong>1.
                            Uang
                            Kertas</strong></td>
                </tr>
                <tr>
                    <td class="px-1 text-left font-bold" rowspan="8"></td>
                </tr>
                <tr>
                    <td class="px-1 text-left ">1.</td>
                    <td class="px-1 text-left ">Pecahan</td>
                    <td class="px-1 text-left ">Rp. </td>
                    <td class="px-1 text-right ">100.000</td>
                    <td class="px-1 text-center ">X</td>
                    <td class="px-1 text-right ">{{ $res->kertas100k }} </td>
                    <td class="px-1 text-center ">Lembar</td>
                    <td class="px-1 text-center ">Rp. </td>
                    <td class="px-1 text-right ">{{ number_format($res->jml_kertas100k, 0, ',', '.') . ',00' }}
                    </td>
                </tr>
                <tr>
                    <td class="px-1 text-left ">2.</td>
                    <td class="px-1 text-left ">Pecahan</td>
                    <td class="px-1 text-left ">Rp. </td>
                    <td class="px-1 text-right ">50.000</td>
                    <td class="px-1 text-center ">X</td>
                    <td class="px-1 text-right ">{{ $res->kertas50k }}</td>
                    <td class="px-1 text-center ">Lembar</td>
                    <td class="px-1 text-center ">Rp. </td>
                    <td class="px-1 text-right ">{{ number_format($res->jml_kertas50k, 0, ',', '.') . ',00' }}
                    </td>
                </tr>
                <tr>
                    <td class="px-1 text-left ">3.</td>
                    <td class="px-1 text-left ">Pecahan</td>
                    <td class="px-1 text-left ">Rp. </td>
                    <td class="px-1 text-right ">20.000</td>
                    <td class="px-1 text-center ">X</td>
                    <td class="px-1 text-right ">{{ $res->kertas20k }}</td>
                    <td class="px-1 text-center ">Lembar</td>
                    <td class="px-1 text-center ">Rp. </td>
                    <td class="px-1 text-right ">{{ number_format($res->jml_kertas20k, 0, ',', '.') . ',00' }}
                    </td>
                </tr>
                <tr>
                    <td class="px-1 text-left ">4.</td>
                    <td class="px-1 text-left ">Pecahan</td>
                    <td class="px-1 text-left ">Rp. </td>
                    <td class="px-1 text-right ">10.000</td>
                    <td class="px-1 text-center ">X</td>
                    <td class="px-1 text-right ">{{ $res->kertas10k }}</td>
                    <td class="px-1 text-center ">Lembar</td>
                    <td class="px-1 text-center ">Rp. </td>
                    <td class="px-1 text-right ">{{ number_format($res->jml_kertas10k, 0, ',', '.') . ',00' }}
                    </td>
                </tr>
                <tr>
                    <td class="px-1 text-left ">5.</td>
                    <td class="px-1 text-left ">Pecahan</td>
                    <td class="px-1 text-left ">Rp. </td>
                    <td class="px-1 text-right ">5.000</td>
                    <td class="px-1 text-center ">X</td>
                    <td class="px-1 text-right ">{{ $res->kertas5k }}</td>
                    <td class="px-1 text-center ">Lembar</td>
                    <td class="px-1 text-center ">Rp. </td>
                    <td class="px-1 text-right ">{{ number_format($res->jml_kertas5k, 0, ',', '.') . ',00' }}
                    </td>
                </tr>
                <tr>
                    <td class="px-1 text-left ">6.</td>
                    <td class="px-1 text-left ">Pecahan</td>
                    <td class="px-1 text-left ">Rp. </td>
                    <td class="px-1 text-right ">2.000</td>
                    <td class="px-1 text-center ">X</td>
                    <td class="px-1 text-right ">{{ $res->kertas2k }}</td>
                    <td class="px-1 text-center ">Lembar</td>
                    <td class="px-1 text-center ">Rp. </td>
                    <td class="px-1 text-right ">{{ number_format($res->jml_kertas2k, 0, ',', '.') . ',00' }}
                    </td>
                </tr>
                <tr>
                    <td class="px-1 text-left ">7.</td>
                    <td class="px-1 text-left ">Pecahan</td>
                    <td class="px-1 text-left ">Rp. </td>
                    <td class="px-1 text-right ">1000</td>
                    <td class="px-1 text-center ">X</td>
                    <td class="px-1 text-right ">{{ $res->kertas1k }}</td>
                    <td class="px-1 text-center ">Lembar</td>
                    <td class="px-1 text-center ">Rp. </td>
                    <td class="px-1 text-right mb-4">{{ number_format($res->jml_kertas1k, 0, ',', '.') . ',00' }}
                    </td>
                </tr>

                <tr>
                    <td class="px-1 text-left font-bold" colspan="9"><strong>2.
                            Uang
                            Logam</strong></td>
                </tr>
                <tr>
                    <td class="px-1 text-left font-bold" rowspan="6"></td>
                </tr>
                <tr>
                    <td class="px-1 text-left ">1.</td>
                    <td class="px-1 text-left ">Pecahan</td>
                    <td class="px-1 text-left ">Rp. </td>
                    <td class="px-1 text-right ">1000</td>
                    <td class="px-1 text-center ">X</td>
                    <td class="px-1 text-right ">{{ $res->logam1k }}</td>
                    <td class="px-1 text-center ">Lembar</td>
                    <td class="px-1 text-center ">Rp. </td>
                    <td class="px-1 text-right ">{{ number_format($res->jml_logam1k, 0, ',', '.') . ',00' }}
                    </td>
                </tr>
                <tr>
                    <td class="px-1 text-left ">2.</td>
                    <td class="px-1 text-left ">Pecahan</td>
                    <td class="px-1 text-left ">Rp. </td>
                    <td class="px-1 text-right ">500</td>
                    <td class="px-1 text-center ">X</td>
                    <td class="px-1 text-right ">{{ $res->logam1k }}</td>
                    <td class="px-1 text-center ">Lembar</td>
                    <td class="px-1 text-center ">Rp. </td>
                    <td class="px-1 text-right ">{{ number_format($res->jml_logam500, 0, ',', '.') . ',00' }}
                    </td>
                </tr>
                <tr>
                    <td class="px-1 text-left ">3.</td>
                    <td class="px-1 text-left ">Pecahan</td>
                    <td class="px-1 text-left ">Rp. </td>
                    <td class="px-1 text-right ">200</td>
                    <td class="px-1 text-center ">X</td>
                    <td class="px-1 text-right ">{{ $res->logam200 }}</td>
                    <td class="px-1 text-center ">Lembar</td>
                    <td class="px-1 text-center ">Rp. </td>
                    <td class="px-1 text-right ">{{ number_format($res->jml_logam200, 0, ',', '.') . ',00' }}
                    </td>
                </tr>
                <tr>
                    <td class="px-1 text-left ">4.</td>
                    <td class="px-1 text-left ">Pecahan</td>
                    <td class="px-1 text-left ">Rp. </td>
                    <td class="px-1 text-right ">100</td>
                    <td class="px-1 text-center ">X</td>
                    <td class="px-1 text-right ">{{ $res->logam100 }}</td>
                    <td class="px-1 text-center ">Lembar</td>
                    <td class="px-1 text-center ">Rp. </td>
                    <td class="px-1 text-right  mb-4">{{ number_format($res->jml_logam100, 0, ',', '.') . ',00' }}
                    </td>
                </tr>
                <tr>
                    <td class="px-1 text-left font-bold" colspan="1"><strong></strong></td>
                    <td class="px-1 text-left font-bold" colspan="6"><strong>Jumlah</strong></td>
                    <td class="px-1 text-center font-bold" colspan=""><strong>Rp. </strong></td>
                    <td class="px-1 text-right font-bold  mb-4" colspan="">
                        <strong>{{ number_format($res->jumlah, 0, ',', '.') . ',00' }} </strong>
                    </td>
                </tr>
                <tr>
                    <td class="px-1 text-left font-bold" colspan="8"><strong>3.
                            Saldo Bank</strong></td>
                    <td class="px-1 text-center font-bold mb-4"> <strong>Rp. </strong></td>
                    <td class="px-1 text-center font-bold mb-4 flex justify-end"> <span><input type="text"
                                name="saldo_bank" id="saldo_bank" class="font-bold text-right"
                                value="0,00"></span></td>
                </tr>
                <tr>
                    <td class="px-1 text-left font-bold" colspan="9"><strong>4.
                            Surat Berharga</strong></td>
                </tr>
                <tr>
                    <td class="px-1 text-left font-bold" rowspan="6"></td>
                </tr>
                <tr>
                    <td class="px-1 text-left ">a.</td>
                    <td class="px-1 text-left " colspan="6">Uang Muka Kerja</td>
                    <td class="px-1 text-center ">Rp. </td>
                    <td class="px-1 text-right "><input type="text" class=" text-right" name="selisih"
                            value="0,00"></td>
                </tr>
                <tr>
                    <td class="px-1 text-left ">b.</td>
                    <td class="px-1 text-left " colspan="6">Kwitansi</td>
                    <td class="px-1 text-center ">Rp. </td>
                    <td class="px-1 text-right "><input type="text" class=" text-right" name="selisih"
                            value="0,00"></td>
                </tr>
                <tr>
                    <td class="px-1 text-left ">c.</td>
                    <td class="px-1 text-left " colspan="6">SPMU</td>
                    <td class="px-1 text-center ">Rp. </td>
                    <td class="px-1 text-right mb-6"><input type="text" class=" text-right" name="selisih"
                            value="0,00"></td>
                </tr>
                <tr>
                    <td class="px-1 text-center font-bold" colspan="7"><strong>Jumlah</strong></td>
                    <td class="px-1 text-center ">Rp. </td>
                    <td class="px-1 text-right font-bold  mb-4">
                        <strong>{{ number_format($res->jumlah, 0, ',', '.') . ',00' }} </strong>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="text-xs flex justify-start">
            <div class="w-1/4 text-center">
                <p>Penjelasan selisih</p>

            </div>
            <div class="w-3/4">
                <p>: <span>
                        <input name="penjelasan" id="penjelasan" rows="2" class="w-11/12 resize-none"></input>
                    </span></p>
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
                <p>Purwokerto,
                    {{ \Carbon\Carbon::parse($res->tanggal_sekarang)->locale('id')->isoFormat('DD MMMM YYYY') }}</p>
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
