<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Cetak Etiket Obat</title>
    <style>
        @page {
            size: auto;
            margin: 10mm;
            size: 22cm 14cm;
        }

        @media print {
            body {
                zoom: 0.9;
                /* bisa ganti ke 0.85-0.95 sesuai selera */
            }

        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
        }

        /* .container {
            display: flex;
            flex-wrap: wrap;
            gap: 5mm;
        } */

        .etiket {
            width: 6cm;
            height: 5cm;
            box-sizing: border-box;
            border: 1px solid #000;
            padding: 0.3cm;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }

        .header-table {
            width: 100%;
            border-bottom: 1px solid black;
            margin-bottom: 4px;
        }

        .header-table td {
            vertical-align: top;
            text-align: center;
        }

        .header-table img {
            width: 25px;
            height: auto;
        }

        .info {
            font-size: 8pt;
            line-height: 1.2;
        }

        .nama-obat {
            font-weight: bold;
            font-size: 9pt;
            margin-top: 4px;
        }

        .aturan {
            font-style: italic;
        }

        hr {
            margin: 4px 0;
            border: none;
            border-top: 1px solid #000;
        }
    </style>
</head>

<body>
    <div class="container">
        @foreach ($obats as $obat)
            <div class="etiket">
                <table class="header-table">
                    <tr>
                        <td>
                            <img src="{{ asset('img/banyumas.png') }}" alt="banyumas" />
                        </td>
                        <td style="font-size: 4pt;">
                            <div><strong>PEMERINTAH KABUPATEN BANYUMAS</strong></div>
                            <div style="font-size: 5pt;"><strong>DINAS KESEHATAN</strong></div>
                            <div style="font-size: 4pt;">KLINIK UTAMA KESEHATAN PARU MASYARAKAT</div>
                            <div style="font-size: 4pt;">Jl. A. Yani No. 33, Banyumas</div>
                        </td>
                        <td>
                            <img src="{{ asset('img/LOGO_KKPM.png') }}" alt="kkpm" />
                        </td>
                    </tr>
                </table>

                <div class="info">
                    Nama Pasien: <strong>
                        {{-- {{ $obat['nama_pasien'] }} --}}
                    </strong><br>
                    <div class="nama-obat">
                        Obat : {{ $obat['resep_obat_detail'][0]['nama_obat'] }}
                    </div>
                    Aturan:
                    {{ $obat['signa'] }}
                    <br>
                    <div class="aturan">Simpan di tempat sejuk dan kering</div>
                    Tgl:
                    {{ \Carbon\Carbon::parse($obat['cppt']['tanggal'] ?? now())->format('d-m-Y') }}
                </div>
            </div>
        @endforeach
    </div>
</body>

</html>
