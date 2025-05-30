<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Optional: You can use this to adjust the layout if needed */
        /* .wrapper {
            width: 21cm;
        } */
    </style>
    <script>
        // window.addEventListener('afterprint', () => {
        //     window.close();
        // });
        window.onafterprint = function() {
            window.close();
        };
    </script>
</head>

<body class="text-black">
    <div class="wrapper m-3 pt-2">
        <table class="w-full table-auto">
            <tbody>
                <!-- Header -->
                <tr>
                    <td class="w-1/6 py-2 border-b border-black scale-60">
                        <img src="{{ asset('img/banyumas.png') }}" class="w-20 mx-2 justify-self-center" alt="banyumas" />
                    </td>
                    <td class="w-4/6 text-center border-b border-black scale-55">
                        <p class="text-md mb-0">PEMERINTAH KABUPATEN BANYUMAS</p>
                        <p class="text-md font-semibold mb-0">DINAS KESEHATAN</p>
                        <p class="text-md font-bold mb-0">KLINIK UTAMA KESEHATAN PARU MASYARAKAT KELAS A</p>
                        <p class="text-xs">Jln. A. Yani Nomor 33 Purwokerto Timur, Banyumas, Jawa Tengah</p>
                        <p class="text-xs">Kode Pos 53111, Telepon (0281) 635658, Pos-el bkpm_purwokerto@yahoo.com</p>
                    </td>
                    <td class="w-1/6 py-2 border-b border-black scale-60">
                        <img src="{{ asset('img/LOGO_KKPM.png') }}" class="w-20 mx-2 justify-self-center"
                            alt="kkpm" />
                    </td>
                </tr>
                <!-- Identitas -->
                <tr>
                    <td colspan="3" class="border-b border-black mb-2">
                        <h2 class="text-lg font-bold text-center">Resep Obat</h2>
                        <table class="table-auto w-full mb-4 text-sm">
                            <tbody>
                                <tr>
                                    <td class="px-2 align-top">No RM</td>
                                    <td class="px-2 align-top">:</td>
                                    <td class="px-2 align-top">{{ $cppt['pasien_no_rm'] }} /
                                        {{ $cppt['penjamin_nama'] }} /
                                        {{ $cppt['antrean_nomor'] }} @if ($noSep != null)
                                            / {{ $noSep }}
                                        @endif
                                    </td>
                                    <td class="px-2 align-top"></td>
                                    <td class="px-2 align-top">Tanggal</td>
                                    <td class="px-2 align-top">:</td>
                                    <td class="px-2 align-top">{{ $cppt['tanggal'] }}, <span
                                            class="ml-4">{{ $cppt['ket_status_pasien_pulang'] }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-2 align-top">Nama/Umur</td>
                                    <td class="px-2 align-top">:</td>
                                    <td class="px-2 align-top">{{ $cppt['pasien_nama'] }} / {{ $cppt['umur'] }}</td>
                                    <td class="px-2 align-top"></td>
                                    <td class="px-2 align-top">BB</td>
                                    <td class="px-2 align-top">:</td>
                                    <td class="px-2 align-top">{{ $cppt['objek_bb'] }} Kg</td>
                                </tr>
                                <tr>
                                    <td class="px-2 align-top">Alamat</td>
                                    <td class="px-2 align-top">:</td>
                                    <td class="px-2 align-top">{{ $cppt['kelurahan_nama'] }},
                                        {{ $cppt['pasien_rt'] }}/{{ $cppt['pasien_rw'] }},
                                        {{ $cppt['kecamatan_nama'] }}</td>
                                    <td class="px-2 align-top"></td>
                                    <td class="px-2 align-top">Dokter</td>
                                    <td class="px-2 align-top">:</td>
                                    <td class="px-2 align-top">
                                        @if (
                                            $cppt['penjamin_nama'] === 'BPJS' &&
                                                ($cppt['dokter_nama'] === 'dr. Filly Ulfa Kusumawardani' || $cppt['dokter_nama'] === 'dr. Sigit Dwiyanto'))
                                            <input style="width: 100%;" type="text"
                                                value="dr. Agil Dananjaya, Sp.P" />
                                        @else
                                            <input style="width: 100%;" type="text"
                                                value="{{ $cppt['dokter_nama'] }}">
                                        @endif

                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-2 align-top">Alergi</td>
                                    <td class="px-2 align-top">:</td>
                                    <td class="px-2 align-top">
                                        @if ($cppt['riwayat_alergi'] != '')
                                            {{ $cppt['riwayat_alergi'] }}
                                        @else
                                            tidak ada alergi
                                        @endif
                                    </td>

                                    <td class="px-2 align-top"></td>
                                    <td class="px-2 align-top">Diagnosa</td>
                                    <td class="px-2 align-top">:</td>
                                    <td class="px-2 align-top">
                                        @if ($dxs[0]['kode_diagnosa'] == 'Z09.8')
                                            @if (empty($dxs) || count($dxs) == 0)
                                                -
                                            @else
                                                {{ $dxs[1]['nmDx'] }}
                                            @endif
                                        @else
                                            @if (empty($dxs) || count($dxs) == 0)
                                                -
                                            @else
                                                {{ $dxs[0]['nmDx'] }}
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <!-- Data Obat -->
                <tr>
                    <td colspan="3" class="p-2">
                        <table class="table-auto w-full border border-black mb-4 text-sm mx-auto table-layout-fixed">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="border px-2 text-left">R/</th>
                                    <th class="border px-2 text-left">Nama Obat</th>
                                    <th class="border px-2 text-left">Jumlah</th>
                                    <th class="border px-2 text-left">Aturan Pakai</th>
                                    <th class="border px-2 text-left">Jumlah Racikan</th>
                                    <th class="border px-2 text-left">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $rowspanData = [];
                                    foreach ($obats as $obat) {
                                        $rowspanData[$obat['no_resep']] = count($obat['resep_obat_detail']);
                                    }
                                @endphp

                                @foreach ($obats as $obat)
                                    @php $firstRow = true; @endphp
                                    @foreach ($obat['resep_obat_detail'] as $detail)
                                        <tr>
                                            @if ($firstRow)
                                                <td class="border px-2 align-top"
                                                    rowspan="{{ $rowspanData[$obat['no_resep']] }}">
                                                    {{ $obat['no_resep'] }}
                                                </td>
                                            @endif
                                            <td class="border px-2 align-top" width="40%">{{ $detail['nama_obat'] }}
                                            </td>
                                            <td class="border px-2 align-top">{{ $detail['jumlah_obat'] }}</td>
                                            @if ($firstRow)
                                                <td class="border px-2 align-top"
                                                    rowspan="{{ $rowspanData[$obat['no_resep']] }}">
                                                    {{ $obat['signa'] }}
                                                </td>
                                                <td class="border px-2 align-top"
                                                    rowspan="{{ $rowspanData[$obat['no_resep']] }}">
                                                    {{ $obat['jumlah_puyer'] ?? '-' }}
                                                </td>
                                                <td class="border px-2 align-top"
                                                    rowspan="{{ $rowspanData[$obat['no_resep']] }}">
                                                    {{ $obat['ket'] ?? '-' }}
                                                </td>
                                            @endif
                                        </tr>
                                        @php $firstRow = false; @endphp
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr>
                <!-- Data Tindakan -->
                <tr>
                    <td colspan="3" class="border-t border-black pt-3">
                        <table class="w-full mb-2 text-sm mx-auto table-layout-fixed text-center">
                            <tbody>
                                <tr>
                                    <td class="border" style="vertical-align: top;font-weight: bold;" width="33%">
                                        Paraf Dokter
                                        <br>
                                        <br>
                                        <br>
                                        <br>
                                        @if (
                                            $cppt['penjamin_nama'] === 'BPJS' &&
                                                ($cppt['dokter_nama'] === 'dr. Filly Ulfa Kusumawardani' || $cppt['dokter_nama'] === 'dr. Sigit Dwiyanto'))
                                            <input style="width: 100%; text-align: center;" type="text"
                                                value="dr. Agil Dananjaya, Sp.P" />
                                        @else
                                            <input style="width: 100%; text-align: center;" type="text"
                                                value="{{ $cppt['dokter_nama'] }}">
                                        @endif

                                    </td>
                                    <td class="border" style="vertical-align: top;font-weight: bold;" width="33%">
                                        Paraf Petugas Farmasi
                                        <br>
                                        <br>
                                        <br>
                                        <br>
                                        <input style="width: 100%; text-align: center;" type="text"
                                            value="apt. Ummu Kultsum">
                                    </td>
                                    <td class="border" style="vertical-align: top;font-weight: bold;" width="33%">
                                        Paraf Penerima Obat
                                        <br>
                                        <br>
                                        <br>
                                        <br>
                                        <input style="width: 100%; text-align: center; margin-bottom: 15px;"
                                            type="text" value="{{ ucwords(strtolower($cppt['pasien_nama'])) }}">
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                    </td>
                </tr>
                <!-- Validasi Varmasi -->
                <tr>
                    <td colspan="3" class="border-t border-black">
                        <table class="table-auto w-full border border-black mb-2 text-sm mx-auto table-layout-fixed">
                            <table width="100%" style="color: black;font-size: 8pt;">
                                <thead class="border">
                                    <tr class="bg-gray-200">
                                        <th class="border px-2 text-center" colspan="3">
                                            <h5>Validasi Farmasi</h5>
                                        </th>
                                    </tr>
                                    <tr class="border px-2">
                                        <th class="border px-2 text-center">
                                            <h5>Telaah Obat</h5>
                                        </th>
                                        <th class="border px-2 text-center">
                                            <h5>Telaah Resep</h5>
                                        </th>
                                        <th class="border px-2 text-center">
                                            <h5>Jam/Waktu</h5>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="border px-2">
                                    <tr class="border px-2">
                                        <td class="border px-2 align-top" width="33%">
                                            <div class="row text-left">
                                                <div class="col-md-12">
                                                    <input type="checkbox" id="nama_obat">
                                                    <label for="nama_obat">Nama Obat</label>
                                                </div>
                                                <div class="col-md-12">
                                                    <input type="checkbox" id="tepat_obat">
                                                    <label for="tepat_obat">Tepat Obat</label>
                                                </div>
                                                <div class="col-md-12">
                                                    <input type="checkbox" id="dosis_jumlah">
                                                    <label for="dosis_jumlah">Dosis &amp; Jumlah</label>
                                                </div>
                                                <div class="col-md-12">
                                                    <input type="checkbox" id="rute_pemberian">
                                                    <label for="rute_pemberian">Rute Pemberian</label>
                                                </div>
                                                <div class="col-md-12">
                                                    <input type="checkbox" id="waktu_frekuensi">
                                                    <label for="waktu_frekuensi">Waktu &amp; Frekuensi</label>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="border px-2 align-top" width="33%">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <table width="100%" style="color: black;">
                                                        <tbody>
                                                            <tr>
                                                                <td width="15%" class="text-left">
                                                                    <input type="checkbox" id="kejelasan_penulisan">
                                                                    <label for="kejelasan_penulisan">Kejelasan
                                                                        Penulisan</label>
                                                                </td>
                                                                <td width="15%" class="text-left">
                                                                    <input type="checkbox" id="variasi_obat">
                                                                    <label for="variasi_obat">Variasi Obat</label>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-left">
                                                                    <input type="checkbox" id="identitas_pasien">
                                                                    <label for="identitas_pasien">Identitas
                                                                        Pasien</label>
                                                                </td>
                                                                <td class="text-left">
                                                                    <input type="checkbox" id="berat_badan">
                                                                    <label for="berat_badan">Berat Badan</label>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-left">
                                                                    <input type="checkbox" id="duplikasi_obat">
                                                                    <label for="duplikasi_obat">Duplikasi Obat</label>
                                                                </td>
                                                                <td class="text-left">
                                                                    <input type="checkbox" id="kontra_indikasi">
                                                                    <label for="kontra_indikasi">Kontra
                                                                        Indikasi</label>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-left">
                                                                    <input type="checkbox" id="potensi_alergi">
                                                                    <label for="potensi_alergi">Potensi Alergi</label>
                                                                </td>
                                                                <td class="text-left">
                                                                    <input type="checkbox" id="sesuai_formularium">
                                                                    <label for="sesuai_formularium">Sesuai
                                                                        Formularium</label>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2" class="text-left">
                                                                    <input type="checkbox" id="interaksi_obat">
                                                                    <label for="interaksi_obat">Interaksi Obat</label>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="border px-2" style="vertical-align: top;" width="33%">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    Jam Penerimaan
                                                </div>
                                                <div class="col-md-12">
                                                    <input type="text" value="-" id="jam_penerimaan">
                                                </div>
                                            </div>
                                            <div class="row" style="margin-top: 20px;">
                                                <div class="col-md-12">
                                                    Jam Penyerahan
                                                </div>
                                                <div class="col-md-12">
                                                    <input type="text" value="-" id="jam_penyerahan">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </table>

                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <script>
        //buat semua checkbox ter cheked
        document.addEventListener("DOMContentLoaded", function() {
            var checkboxes = document.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = true;
            });

            var now = new Date();

            // Jam penerimaan = sekarang
            var jamPenerimaanJam = now.getHours();
            var jamPenerimaanMenit = now.getMinutes();

            // Tambahkan waktu random antara 15 - 60 menit untuk jam penyerahan
            var tambahanMenit = Math.floor(Math.random() * 46) + 15; // 15-60 menit
            var penyerahan = new Date(now.getTime() + tambahanMenit * 60000);

            // Ambil jam dan menit penyerahan
            var jamPenyerahanJam = penyerahan.getHours();
            var jamPenyerahanMenit = penyerahan.getMinutes();

            // Fungsi untuk menambahkan 0 jika satuan
            function formatWaktu(jam, menit) {
                return (
                    (jam < 10 ? "0" + jam : jam) + ":" + (menit < 10 ? "0" + menit : menit)
                );
            }

            var jamPenerimaan = formatWaktu(jamPenerimaanJam, jamPenerimaanMenit);
            var jamPenyerahan = formatWaktu(jamPenyerahanJam, jamPenyerahanMenit);

            // Set ke input
            document.getElementById("jam_penerimaan").value = jamPenerimaan;
            document.getElementById("jam_penyerahan").value = jamPenyerahan;

        })

        //close setelah cetak
        window.addEventListener('afterprint', () => {
            window.close(); // ini akan berhasil kalau dibuka dari window.open()
        });
    </script>


</body>

</html>
