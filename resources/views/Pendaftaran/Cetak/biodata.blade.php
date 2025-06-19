<!DOCTYPE HTML>
<html>

<head>
    <title>RM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-size: 11pt;
            margin: 0;
            padding: 0;
        }

        table tr td {
            padding-left: 5px;
            padding-bottom: 10px;
        }

        table.uraian td {
            padding-top: 5px;
            padding-bottom: 5px;
        }

        .anukertas {
            width: 205mm;
            height: 280mm;
            border: 1px solid black;
            margin: 5mm 5mm 0 20mm;
            padding: 16px;
            box-sizing: border-box;
        }


        @media print {
            @page {
                size: 205mm 280mm portrait;
                margin: 0;
            }

            .no-print-border {
                border: none !important;
            }

            body {
                margin: 0;
            }
        }
    </style>
    {{-- buatkan scrit untuk cetak saat selesai load --}}
    <script>
        // window.onload = function() {
        //     window.print();
        // }
        // // tutup jendela saat selesai cetak
        // window.onafterprint = function() {
        //     window.close();
        // }

        // saat menekan tombol p lakukan cetak
        document.addEventListener("keydown", function(event) {
            if (event.key === "p" || event.keyCode === 80) { // Check for Enter key
                event.preventDefault(); // Prevent the default action (optional)
                // Your code to execute on Enter key press
                console.log("Enter key pressed!");
                window.print();
            }
        })
    </script>

</head>

<body class="text-black flex justify-center">
    {{-- <div class="border border-black no-print-border" style="width: 220mm; height: 330mm;"> --}}
    <div class="anukertas">
        <table width="100%">
            <tr>
                <td
                    style="text-align: center; padding-top: 0.5rem; padding-bottom: 0.5rem; border-bottom: 1px solid black;">
                    <img src="{{ asset('img/banyumas.png') }}" style="width: 85px; display: block; margin: 0 auto;"
                        alt="banyumas" />
                    {{-- <img src="/public/img/banyumas.png" style="width: 85px; display: block; margin: 0 auto;"
                        alt="banyumas" /> --}}
                </td>
                <td style="width: 66.66%; text-align: center; border-bottom: 1px solid black;">
                    <p style="font-size: 0.875rem; margin-bottom: 0;">PEMERINTAH KABUPATEN BANYUMAS</p>
                    <p style="font-size: 1.125rem; font-weight: 600; margin-bottom: 0;">DINAS KESEHATAN</p>
                    <p style="font-size: 0.875rem; font-weight: bold; margin-bottom: 0;">KLINIK UTAMA KESEHATAN PARU
                        MASYARAKAT KELAS A</p>
                    <p style="font-size: 0.75rem;">Jln. A. Yani Nomor 33 Purwokerto Timur, Banyumas, Jawa Tengah</p>
                    <p style="font-size: 0.75rem;">Kode Pos 53111, Telepon (0281) 635658</p>
                    <p style="font-size: 0.75rem;">Pos-el bkpm_purwokerto@yahoo.com</p>
                </td>
                <td
                    style="text-align: center; padding-top: 0.5rem; padding-bottom: 0.5rem; border-bottom: 1px solid black;">
                    <img src="{{ asset('img/LOGO_KKPM.png') }}" style="width: 6rem; display: block; margin: 0 auto;"
                        alt="kkpm" />
                    {{-- <img src="/public/img/LOGO_KKPM.png" style="width: 6rem; display: block; margin: 0 auto;"
                        alt="kkpm" /> --}}
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
                <td colspan="5">{{ $pekerjaan_nama ?? '' }}</td>
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
            keselamatan jiwa saya serta identitas diri saya telah saya berikan dengan sebenar-benarnya tanpa
            kebohongan.
        </p>

        <br /><br />
        <table>
            <tr>
                <td width="65%" align="center"></td>
                <td align="center">
                    Purwokerto, {{ \Carbon\Carbon::parse($created_at_tanggal)->translatedFormat('d-M-Y') }}
                    <br /><br /><br /><br /><br /><br />
                    <pre>(                      )</pre>
                </td>
            </tr>
        </table>
    </div>
    {{-- </div> --}}
</body>

</html>
