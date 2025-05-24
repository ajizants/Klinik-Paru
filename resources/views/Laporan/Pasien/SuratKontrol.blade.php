<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>S.Kontrol: {{ $detailSuratKontrol['sep']['peserta']['nama'] }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @page {
            size: 22cm 29, 7cm;
            /* lebar x tinggi */
            margin: 0.3cm;
            /* atur sesuai kebutuhan */
        }

        .pembungkus {
            padding: 1rem;
            border: 1px solid black;
            box-sizing: border-box;
            /* width: 95.5%; */
        }

        .kertas {
            width: 22cm;
            /* atau bisa coba: 29.7cm 21cm */
            margin: 0.2cm 0.2cm 0.2cm 0.2cm;
            /* scale: 0.8; */
            /* border: 1px solid black; */
        }

        @media print {
            body {
                zoom: 0.9;
                /* bisa ganti ke 0.85-0.95 sesuai selera */
            }

            @page {
                .pembungkus {
                    padding: 1rem;
                    border: 1px solid black;
                    box-sizing: border-box;
                    width: 22cm;
                    height: 13.8cm;
                    margin: 0.2cm;

                }
            }
        }
    </style>



</head>

<body class="flex justify-center">
    <div class="kertas">
        <div class="pembungkus mt-3">
            @include('Laporan.Pasien.tmpSKontrol')
        </div>
    </div>

    <script>
        //bagaimana untuk mengecek umur dan no_sampel jika cetak dari tombol CTRL + P dan browser
        document.addEventListener("keydown", function(event) {
            if (event.key === "Enter") {
                window.print();
                // window.onafterprint = function() {
                window.close();
                // }
            }
        })

        // load langsung cetak
        // document.addEventListener("DOMContentLoaded", function() {
        //     window.print();
        //     window.onafterprint = function() {
        //         window.close();
        //     }
        // })
    </script>
</body>

</html>
