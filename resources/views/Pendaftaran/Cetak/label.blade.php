<!DOCTYPE HTML>
<html>

<head>
    <title>Label Format Baru</title>
    <style>
        @media print {
            @page {
                size: 210mm 145mm;
                size: portrait;
                /* margin: 2mm; */
            }

            .no-print-border {
                border: none !important;
            }

            .box {
                display: inline-block;
                width: 50mm;
                height: 17mm;
                margin-top: 0;
                margin-right: 1.5mm;
                margin-left: 1.5mm;
                margin-bottom: 3.5mm;
                vertical-align: top;
            }

            .box2 {
                display: inline-block;
                width: 50mm;
                height: 17mm;
                margin-top: 0;
                margin-right: 1.5mm;
                margin-left: 1.5mm;
                margin-bottom: 3mm;
                vertical-align: top;
            }
        }

        .box {
            display: inline-block;
            width: 50mm;
            height: 17mm;
            margin-top: 0;
            margin-right: 1.5mm;
            margin-left: 1.5mm;
            margin-bottom: 3.5mm;
            vertical-align: top;
        }

        .box2 {
            display: inline-block;
            width: 50mm;
            height: 17mm;
            margin-top: 0;
            margin-right: 1.5mm;
            margin-left: 1.5mm;
            margin-bottom: 3mm;
            vertical-align: top;
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
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="text-black flex justify-center" style="font-size: 8pt;">
    <div class="border border-black no-print-border" style="width: 220mm; height: 145mm;">
        @foreach ($pasien as $item)
            <div class="box pl-1 border border-black no-print-border">
                <table border="1">
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
                                {{ $item['sebutan'] }}.
                                {{ strlen($item['nama']) > 23 ? substr($item['nama'], 0, 20) . '...' : $item['nama'] }}
                            </b>

                        </td>
                    </tr>
                    <tr>
                        <td>{{ $item['alamat'] }}</td>
                    </tr>
                </table>
            </div>
        @endforeach
        @foreach ($pasien2 as $item)
            <div class="box2 pl-1 border border-black no-print-border">
                <table border="1">
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
                                {{ $item['sebutan'] }}.
                                {{ strlen($item['nama']) > 23 ? substr($item['nama'], 0, 20) . '...' : $item['nama'] }}
                            </b>

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
