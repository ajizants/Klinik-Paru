<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Cetak Etiket Obat</title>
    <style>
        html {
            filter: grayscale(100%);
        }

        @page {
            height: auto;
            width: 8cm;
        }


        @media print {
            body {
                zoom: 1;
                font-family: Arial, sans-serif;
                font-size: 10pt;
            }
        }

        .container {
            gap: 5mm;
        }

        .etiket {
            width: 7cm;
            height: 5cm;
            box-sizing: border-box;
            border: 1px solid #000;
            padding: 0.3cm;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            margin: 4mm 0 4mm 0;
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
                        <td style="font-size: 5pt;">
                            <div>PEMERINTAH KABUPATEN BANYUMAS</div>
                            <div style="font-size: 6pt;">DINAS KESEHATAN</div>
                            <div style="font-size: 5pt;"><strong>KLINIK UTAMA KESEHATAN PARU MASYARAKAT</strong></div>
                            <div style="font-size: 4pt;">Jl. A. Yani No. 33, Purwokerto Timur, Banyumas
                            </div>

                        </td>
                        <td>
                            <img src="{{ asset('img/LOGO_KKPM.png') }}" alt="kkpm" />
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <div style="font-size: 5pt;">Kd.Pos 53111, Tlp. (0281) 635658, Pos-el
                                bkpm_purwokerto@yahoo.com</div>
                        </td>
                    </tr>
                </table>

                <table style="font-family: sans-serif; line-height: 1; border-collapse: collapse; font-size: 6pt;">
                    <tr style="vertical-align: top;">
                        <td style="padding: 1px;"><strong>No.</strong></td>
                        <td style="padding: 1px;">:</td>
                        <td style="padding: 1px;">
                            <div style="display: flex; flex-direction: row; gap: 50px; align-items: center;">
                                <div>
                                    {{ $obat['no_resep'] ?? '' }}
                                </div>
                                <div style="text-align: right;">
                                    <strong>Tgl :</strong>
                                    {{ \Carbon\Carbon::parse($obat['cppt']['tanggal'] ?? now())->format('d-m-Y') }}
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr style="vertical-align: top;">
                        <td style="padding: 1px; width: 160px;"><strong>Nama Pasien</strong></td>
                        <td style="padding: 1px; width: 10px;">:</td>
                        <td style="padding: 1px;">{{ $obat['nama_pasien'] }}</td>
                    </tr>
                    <tr style="vertical-align: top;">
                        <td style="padding: 1px;"><strong>Nama Obat</strong></td>
                        <td style="padding: 1px;">:</td>
                        <td style="padding: 1px;">{{ $obat['resep_obat_detail'][0]['nama_obat'] }}</td>
                    </tr>
                    <tr style="vertical-align: top;">
                        <td style="padding: 1px;"><strong>Fungsi Obat</strong></td>
                        <td style="padding: 1px;">:</td>
                        <td style="padding: 1px;">
                            <input type="text" name="fungsi_obat" id="fungsi_obat"
                                value="{{ $obat['fungsi_obat'] ?? '' }}"
                                style="border: none; padding: 1px; width: 160px; font-size: 6pt;">
                        </td>
                    </tr>
                    <tr style="vertical-align: top;">
                        <td style="padding: 1px;"><strong>Aturan Pakai</strong></td>
                        <td style="padding: 1px;">:</td>
                        <td style="padding: 1px;">{{ $obat['signa'] }}</td>
                    </tr>
                    <tr style="vertical-align: top;">
                        <td style="padding: 1px;"><strong>Pada Jam</strong></td>
                        <td style="padding: 1px;">:</td>
                        <td style="padding: 1px;">
                            {{-- <div style="display: grid; grid-template-columns: repeat(3, auto); gap: 2px;">
                                @foreach (['06.00', '12.00', '14.00', '18.00', '22.00', '24.00'] as $jam)
                                    <label
                                        style="display: flex; align-items: center; font-size: 7pt; line-height: 1; gap: 4px;">
                                        <input type="radio" name="jam_pakai" value="{{ $jam }}"
                                            style="width: 7pt; height: 7pt; margin: 0;">
                                        <span>{{ $jam }}</span>
                                    </label>
                                @endforeach
                            </div> --}}
                            <div style="display: grid; grid-template-columns: repeat(3, auto); gap: 6px;">
                                @foreach (['06.00', '12.00', '14.00', '18.00', '22.00', '24.00'] as $jam)
                                    <label
                                        style="display: flex; align-items: center; gap: 4px; font-size: 7pt; line-height: 1;">
                                        <input type="checkbox" name="jam_pakai[]" value="{{ $jam }}"
                                            style="width: 7pt; height: 7pt; margin: 0;">
                                        <span>{{ $jam }}</span>
                                    </label>
                                @endforeach
                            </div>

                        </td>
                    </tr>
                    <tr style="vertical-align: top;">
                        {{-- <td style="padding: 1px;"><strong>Keterangan</strong></td>
                        <td style="padding: 1px;">:</td> --}}
                        <td style="padding: 1px;" colspan="3">🧊 Simpan di tempat sejuk dan kering</td>
                    </tr>
                </table>

            </div>
        @endforeach
    </div>
</body>

</html>
