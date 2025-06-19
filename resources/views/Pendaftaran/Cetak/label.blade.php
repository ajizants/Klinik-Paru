<!DOCTYPE HTML>
<html>

<head>
    <title>Label Format Baru</title>
    <style>
        @media print {
            @page {
                size: 211mm 135mm;
                margin: 0px;
                padding: 0px;
                size: portrait;
                /* Atur margin 0 agar layout presisi */
            }

            body {
                margin: 0px;
                /* Pastikan tidak ada margin tambahan dari body */
                padding: 0px;
            }

            /* Box styling tetap */
            .box,
            .box2 {
                display: inline-block;
                width: 50mm;
                height: 16mm;
                /* Disesuaikan agar muat 7 baris */
                margin: 2mm 3mm 2mm 0;
                padding-left: 1mm;
                padding-top: 3mm;
                vertical-align: top;
                border: 1px solid black;
                box-sizing: border-box;
            }

            .box2 {
                margin-bottom: 2mm;
            }
        }


        .box,
        .box2 {
            display: inline-block;
            width: 50mm;
            height: 17mm;
            margin: 2mm 3mm 2mm 0mm;
            padding-left: 1mm;
            padding-top: 1mm;
            vertical-align: top;
            border: 1px solid black;
        }

        .box2 {
            margin-bottom: 2.5mm;
        }
    </style>
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
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="text-black " style="font-size: 8pt; color: red">
    {{-- <div class="border border-black no-print-border"> --}}
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
    {{-- </div> --}}
</body>

</html>
