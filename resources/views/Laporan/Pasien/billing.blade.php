<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Billing: {{ $detailSEP['peserta']['nama'] }}</title>

    <style>
        .kertas {
            width: 13cm;
            /* atau bisa coba: 29.7cm 21cm */
            margin: 0;
            /* scale: 0.8; */
            /* border: 1px solid black; */
        }

        .container {
            padding: 8px;
            /* border: 1px solid black; */
            box-sizing: border-box;
            /* width: 95.5%; */
        }

        .flex {
            display: flex;
            align-items: center;
            gap: 1rem;
        }


        @media print {
            body {
                zoom: 0.9;
                /* bisa ganti ke 0.85-0.95 sesuai selera */
            }

            @page {
                .container {
                    padding: 8px;
                    /* border: 1px solid black; */
                    box-sizing: border-box;
                    width: 13cm;
                    height: 25cm;
                    /* lebih panjang */
                    margin: 0.2cm 0.2cm 0.2cm 0.2cm;
                }

                .kertas {
                    width: 13cm;
                    /* atau bisa coba: 29.7cm 21cm */
                    margin: 0;
                    /* scale: 0.8; */
                    /* border: 1px solid black; */
                }
            }

        }
    </style>
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="flex justify-center">
    <div class="kertas">
        <div class="container">
            @include('Laporan.Pasien.tmpBilling')
        </div>
    </div>
</body>

</html>
