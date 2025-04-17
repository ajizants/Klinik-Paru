<!DOCTYPE HTML>
<html>

<head>
    <title>Label Format Baru</title>
    <style>
        /* @media print {
            @page {
                size: 211mm 135mm;
                size: portrait;
            }
        } */

        * {
            box-sizing: border-box;
        }

        body {
            font-size: 8pt;
        }

        .fs11 {
            font-size: 11pt;
        }

        .fs10 {
            font-size: 10pt;
        }

        .fs9 {
            font-size: 9pt;
        }

        .box {
            display: inline-block;
            width: 50mm;
            height: 17mm;
            margin: 0 0 1mm 0;
            /* padding: 1mm 2mm 1mm 1mm; */
            vertical-align: top;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }

        @page {
            margin: 2mm;
        }

        body {
            margin: 0;
            /* biasanya 0 di sini biar yang dipakai margin @page */
            font-family: Arial, sans-serif;
        }
    </style>
    <script>
        window.onload = function() {
            window.print();
        }
        // tutup jendela saat selesai cetak
        window.onafterprint = function() {
            window.close();
        }

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

<body>

    @foreach ($pasien as $item)
        <div class="box">
            <table border="0">
                <tr>
                    <td>
                        <b>{{ $item['norm'] }}</b>
                        / {{ $item['jkel'] }}
                        / {{ \Carbon\Carbon::parse($item['tgllahir'])->format('d-m-Y') }}
                    </td>

                </tr>
                <tr>
                    <td>
                        <b>
                            {{ $item['sebutan'] }}. {{ $item['nama'] }}
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
