<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .table-bordered {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #dee2e6;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6;
            padding: 0.75rem;
            vertical-align: top;
        }

        .table-bordered thead th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
    </style>

</head>

<body class="text-black">
    <div class="wrapper m-3 pt-2">
        <h1 class="text-center font-bold text-2xl">LOGBOOK RONTGEN</h1>
        <h2 class="text-center font-bold text-xl">KLINIK UTAMA KESEHATAN PARU MASYARAKAT KELAS A KAB. BANYUMAS</h2>
        <h3 class="text-center font-bold mb-4">
            {{ \carbon\Carbon::parse($tglAwal)->locale('id')->isoFormat('DD MMMM YYYY') }} s.d.
            {{ \carbon\Carbon::parse($tglAkhir)->locale('id')->isoFormat('DD MMMM YYYY') }}</h3>

        {!! $table !!}

        <div class="flex justify-end">

            <div class="w-1/2 text-left text-xs">

            </div>
            <div class="w-1/2 text-center">
                <p>Purwokerto,
                    {{ \Carbon\Carbon::parse($tglAkhir)->locale('id')->isoFormat('DD MMMM YYYY') }}
                </p>
                <p>Mengetahui,</p>
                <p>Plt. Kepala KKPM</p>
                <div class="h-16"></div>
                <p>dr. Anwar Hudiono, M.P.H.</p>
                <p>NIP. 198212242010011022</p>
            </div>
        </div>

    </div>

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            alert(
                "Sebelum mencetak, jangan melakukan koreksi data terlebih dahulu."
            );
        })
    </script>
</body>

</html>
