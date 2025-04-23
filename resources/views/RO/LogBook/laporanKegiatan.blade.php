<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REKAP LAPORAN KEGIATAN HARIAN RONTGEN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* .wrapper {
            width: 21cm;
        } */
    </style>
</head>

<body class="text-black">
    <div class="wrapper m-3 pt-2">
        <h1 class="text-center font-bold text-3xl">REKAP LAPORAN KEGIATAN HARIAN RONTGEN</h1>
        <h2 class="text-center font-bold text-xl">KLINIK UTAMA KESEHATAN PARU MASYARAKAT KELAS A KAB. BANYUMAS</h2>
        <h3 class="text-center font-bold mb-4">Bulan: {{ $blnTahun }}</h3>

        {!! $html !!}

        <div class="flex justify-end">

            <div class="w-1/2 text-left text-xs">
                <p>Keterangan : <br>A : Ambarsari <br>N : Nofi Indriyani <br>1. Ev. Mutu/CR : mengevaluasi mutu
                    radiograf di layar
                    CR <br>2. Persiapan RO : mempersiapkan pasien, alat dan bahan sebelum melakukan
                    pemeriksaan/pemotretan
                    <br>3. RO : melakukan pemeriksaan rontgen thorax
                </p>
            </div>
            <div class="w-1/2 text-center">
                <p>Purwokerto,
                    {{ \Carbon\Carbon::parse($tglAkhir)->locale('id')->isoFormat('DD MMMM YYYY') }}
                </p>
                <p>Mengetahui,</p>
                <p>Plt. Kepala KKPM</p>
                <div class="h-16"></div>
                <p>dr. Rendi Retissu</p>
                <p>NIP. 19881016 201902 1 002</p>
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
