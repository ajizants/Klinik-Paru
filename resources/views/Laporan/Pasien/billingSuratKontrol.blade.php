<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Bil & Kontrol: {{ $detailSuratKontrol['sep']['peserta']['nama'] }}</title>

    <style>
        .pembungkus {
            padding: 1rem;
            /* border: 1px solid black; */
            box-sizing: border-box;
            /* width: 95.5%; */
        }

        .pembungkus2 {
            padding: 1rem;
            /* border: 1px solid black; */
            box-sizing: border-box;
            /* width: 95.5%; */
        }

        .flex {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .mb-3 {
            margin-bottom: 0.75rem;
        }

        .text-lg {
            font-size: 1.125rem;
        }

        .text-base {
            font-size: 1rem;
        }

        .font-semibold {
            font-weight: 600;
        }

        .text-sm {
            font-size: 0.875rem;
        }

        .text-xs {
            font-size: 0.75rem;
        }

        .w-full {
            width: 100%;
        }

        table {
            width: 100%;
            font-size: 0.875rem;
            border-collapse: collapse;
        }

        td {
            padding: 0;
            margin: 0;
            vertical-align: top;
        }

        .list-disc {
            list-style-type: disc;
            margin-bottom: 0 !important;
        }

        .ml-5 {
            margin-left: 1.25rem;
        }

        .mt-2 {
            margin-top: 0.5rem;
        }

        .mt-4 {
            margin-top: 1rem;
        }

        .mb-0 {
            margin-bottom: 0;
        }

        .mt-0 {
            margin-top: 0;
        }

        .my-0 {
            margin-bottom: 0;
            margin-top: 0;
        }

        .w-7\/12 {
            width: 58.333333%;
        }

        .w-1\/12 {
            width: 8.333333%;
        }

        .w-4\/12 {
            width: 33.333333%;
        }

        .text-left {
            text-align: left;
        }

        img.h-70px {
            height: 50px;
        }

        h3,
        h4,
        h6 {
            margin: 0;
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
                    /* border: 1px solid black; */
                    box-sizing: border-box;
                    width: 22cm;
                    height: 13.8cm;
                    margin: 0.2cm;
                }

                .pembungkus2 {
                    padding: 1rem;
                    /* border: 1px solid black; */
                    box-sizing: border-box;
                    width: 22cm;
                    height: 25cm;
                    /* lebih panjang */
                    margin: 0.2cm;
                }
            }



        }
    </style>
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="flex justify-center">
    <div class="kertas">

        <div class="pembungkus mt-3">
            @include('Laporan.Pasien.tmpSKontrol')
        </div>
        <div class="pembungkus2 mt-3">
            @include('Laporan.Pasien.tmpBilling')
        </div>

    </div>
</body>

</html>
