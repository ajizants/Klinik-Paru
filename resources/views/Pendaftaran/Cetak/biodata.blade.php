<!DOCTYPE HTML>
<html>

<head>
    <title>RM</title>
    <style>
        body {
            font-size: 12pt;
        }

        table tr td {
            padding-left: 5px;
            padding-bottom: 10px;
        }

        .cusMargin025 {
            -webkit-margin-after: 0.25em;
            -webkit-margin-before: 0.25em;
        }

        h3 {
            font-size: 18pt;
        }

        h2 {
            font-size: 20pt;
        }

        h5 {
            font-size: 12pt;
        }

        table.uraian td {
            padding-top: 5px;
            padding-bottom: 5px;
        }

        .bordered {
            border-bottom: 1px solid black;
        }
    </style>
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body>
    <div style="border:1px solid black; padding:10px; overflow: auto; height: 100%; max-width:210mm;">
        <table width="100%">
            <tr>
                {{-- <td>
                    <img src="{{ asset('img/banyumas.png') }}" style="width:3cm" />
                </td>
                <td align="center" style="width:100%">
                    <h3 class="cusMargin025">PEMERINTAH KABUPATEN BANYUMAS</h3>
                    <h3 class="cusMargin025">DINAS KESEHATAN</h3>
                    <h2 class="cusMargin025">KLINIK UTAMA KESEHATAN PARU MASYARAKAT KELAS A</h2>
                    <h5 class="cusMargin025">Jalan Jendral Ahmad Yani No. 33 Purwokerto</h5>
                </td>
                <td></td>
                <td>
                    <img src="{{ asset('/img/LOGO_KKPM.png') }}" style="width:3cm; height:3cm" />
                </td> --}}
                <td class="text-center py-2 border-b border-black scale-60">
                    <img src="{{ asset('img/banyumas.png') }}" class="w-[85px] mx-auto" alt="banyumas" />
                </td>
                <td class="w-4/6 text-center border-b border-black scale-55">
                    <p class="text-md mb-0">PEMERINTAH KABUPATEN BANYUMAS</p>
                    <p class="text-lg font-semibold mb-0">DINAS KESEHATAN</p>
                    <p class="text-md font-bold mb-0">KLINIK UTAMA KESEHATAN PARU MASYARAKAT KELAS A</p>
                    <p class="text-xs">Jln. A. Yani Nomor 33 Purwokerto Timur, Banyumas, Jawa Tengah</p>
                    <p class="text-xs">Kode Pos 53111, Telepon (0281) 635658</p>
                    <p class="text-xs">Pos-el bkpm_purwokerto@yahoo.com</p>
                </td>
                <td class="text-center py-2 border-b border-black scale-60">
                    <img src="{{ asset('img/LOGO_KKPM.png') }}" class="w-24 mx-auto" alt="kkpm" />
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <hr />
                </td>
            </tr>
            <tr>
                <td colspan="4" align="center">
                    <h3><b>FORMULIR PENDAFTARAN PASIEN BARU</b></h3>
                </td>
            </tr>
        </table>
        <br />

        <table style="width:100%;" class="uraian">
            <tr>
                <td style="width:25%">No. Rekam Medis</td>
                <td style="width:2%;">:</td>
                <td colspan="2">{{ $pasien_no_rm }}</td>
                <td>Gol. Darah</td>
                <td style="width:2%;">:</td>
                <td colspan="3">{{ $goldar_nama == null ? '-' : $goldar_nama ?? '-' }}</td>
            </tr>
            <tr>
                <td>Tgl/Jam Daftar</td>
                <td>:</td>
                <td colspan="3">{{ $created_at }}</td>
            </tr>
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td colspan="5">{{ $pasien_nama }}</td>
            </tr>
            <tr>
                <td>Tempat / Tgl Lahir</td>
                <td>:</td>
                <td colspan="2">{{ $pasien_tempat_lahir }}</td>
                <td>Tanggal Lahir</td>
                <td>:</td>
                <td colspan="3">{{ $pasien_tgl_lahir }}</td>
            </tr>
            <tr>
                <td>NIK</td>
                <td>:</td>
                <td colspan="5">{{ $pasien_nik }}</td>
            </tr>
            <tr>
                <td>Cara Pembayaran</td>
                <td>:</td>
                <td colspan="5">{{ $penjamin_nama }}</td>
            </tr>
            <tr>
                <td>No. Asuransi</td>
                <td>:</td>
                <td colspan="5">{{ $penjamin_nomor }}</td>
            </tr>
            <tr>
                <td>Jenis Kelamin</td>
                <td>:</td>
                <td colspan="2">{{ $jenis_kelamin_nama }}</td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td>RT / RW</td>
                <td>:</td>
                <td colspan="3">{{ $pasien_rt }}/{{ $pasien_rw }}</td>
            </tr>
            <tr>
                <td colspan="2"></td>
                <td>Desa / Kelurahan</td>
                <td>:</td>
                <td colspan="3">{{ $kelurahan_nama }}</td>
            </tr>
            <tr>
                <td colspan="2"></td>
                <td>Kecamatan</td>
                <td>:</td>
                <td colspan="3">{{ $kecamatan_nama }}</td>
            </tr>
            <tr>
                <td colspan="2"></td>
                <td>Kabupaten</td>
                <td>:</td>
                <td colspan="3">{{ $kabupaten_nama }}</td>
            </tr>
            <tr>
                <td colspan="2"></td>
                <td>Provinsi</td>
                <td>:</td>
                <td colspan="3">{{ $provinsi_nama }}</td>
            </tr>
            <tr>
                <td>Pendidikan Terakhir</td>
                <td>:</td>
                <td colspan="5">{{ $pendidikan_nama }}</td>
            </tr>
            <tr>
                <td>Pekerjaan</td>
                <td>:</td>
                <td colspan="5">{{ $pekerjaan ?? '-' }}</td>
            </tr>
            <tr>
                <td>Status</td>
                <td>:</td>
                <td colspan="5">{{ $status_kawin_nama }}</td>
            </tr>
            <tr>
                <td>Penanggung Jawab</td>
                <td>:</td>
                <td colspan="5">{{ $pasien_penanggung_jawab_nama }}</td>
            </tr>
            <tr>
                <td>Ibu Kandung</td>
                <td>:</td>
                <td colspan="5">{{ $ibuKandung ?? '-' }}</td>
            </tr>
        </table>

        <p style="text-align:justify">
            &emsp;&emsp;&emsp; Dengan ini saya menyatakan setuju untuk dilakukan dalam upaya kesembuhan /
            keselamatan jiwa saya serta identitas diri saya telah saya berikan dengan sebenar-benarnya tanpa kebohongan.
        </p>

        <br /><br />
        <table>
            <tr>
                <td width="65%" align="center"></td>
                <td align="center">
                    Purwokerto, {{ \Carbon\Carbon::parse($created_at_tanggal)->translatedFormat('d-M-Y') }}
                    <br /><br /><br /><br /><br /><br /><br />
                    <pre>(                      )</pre>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
