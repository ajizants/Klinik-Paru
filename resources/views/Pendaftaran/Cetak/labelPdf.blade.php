<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Label Pasien</title>
    <style>
        /* Reset global */
        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            font-size: 8pt;
        }

        @page {
            size: 220mm 330mm;
            margin: 0;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .no-print-border {
                border: none !important;
            }
        }

        .page-wrapper {
            width: 220mm;
            height: 330mm;
            margin: 0 auto;
            padding: 0;
        }

        .box,
        .box2 {
            display: inline-block;
            width: 50mm;
            height: 17mm;
            margin: 0 1mm 3mm 1mm;
            padding-left: 1mm;
            vertical-align: top;
            border: 1px solid black;
        }

        .box2 {
            margin-bottom: 2.5mm;
        }

        table {
            width: 100%;
            border: 0;
            border-collapse: collapse;
        }

        td {
            vertical-align: top;
        }
    </style>
</head>

<body onload="window.print()" onafterprint="window.close()">
    <div class="page-wrapper">
        @foreach ($pasien as $item)
            <div class="box no-print-border">
                <table>
                    <tr>
                        <td>
                            <strong>{{ $item['norm'] }}</strong>
                            / {{ $item['jkel'] }}
                            / {{ \Carbon\Carbon::parse($item['tgllahir'])->format('d-m-Y') }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>
                                {{ $item['sebutan'] }}.
                                {{ strlen($item['nama']) > 23 ? substr($item['nama'], 0, 20) . '...' : $item['nama'] }}
                            </strong>
                        </td>
                    </tr>
                    <tr>
                        <td>{{ $item['alamat'] }}</td>
                    </tr>
                </table>
            </div>
        @endforeach

        @foreach ($pasien2 as $item)
            <div class="box2 no-print-border">
                <table>
                    <tr>
                        <td>
                            <strong>{{ $item['norm'] }}</strong>
                            / {{ $item['jkel'] }}
                            / {{ \Carbon\Carbon::parse($item['tgllahir'])->format('d-m-Y') }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>
                                {{ $item['sebutan'] }}.
                                {{ strlen($item['nama']) > 23 ? substr($item['nama'], 0, 20) . '...' : $item['nama'] }}
                            </strong>
                        </td>
                    </tr>
                    <tr>
                        <td>{{ $item['alamat'] }}</td>
                    </tr>
                </table>
            </div>
        @endforeach
    </div>
</body>

</html>
