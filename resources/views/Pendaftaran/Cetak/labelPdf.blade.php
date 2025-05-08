<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Label Pasien</title>
    <style>
        /* Reset global */
        * {
            box-sizing: border-box;
            outline: 1px solid red;
        }

        @page {
            /* mar: t r b l */
            margin: 0px;
            size: portrait;
            font-size: 10pt;
        }

        body {
            margin: 0px;
            size: portrait;
            font-size: 10pt;
        }

        .box,
        .box2 {
            display: inline-block;
            width: 50mm;
            height: 17mm;
            margin: 0 1mm 3mm 1mm;
            padding-left: 1mm;
            padding-top: 1mm;
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
                        <b>
                            {{ $item['sebutan'] }}.
                            {{ strlen($item['nama']) > 18 ? substr($item['nama'], 0, 15) . '...' : $item['nama'] }}
                        </b>
                    </td>
                </tr>
                <tr>
                    <td>{{ $item['alamat'] }}</td>
                </tr>
            </table>
        </div>
    @endforeach
</body>

</html>
