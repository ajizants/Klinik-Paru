<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>KKPM | {{ isset($title) ? $title : '' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@300;700;900&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('vendor/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/dist/css/adminlte.min.css') }}">
    <!-- My Theme style -->
    <link type="text/css" rel="stylesheet" href="{{ asset('css/mystyle.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset('css/display.css') }}">
    <!-- jQuery -->
    <script src="{{ asset('vendor/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('vendor/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('vendor/dist/js/adminlte.min.js') }}"></script>
</head>

<body>
    <header class="container-fluid fixed-top bg-primary">
        <h1 class="font-weight-bold text-center" style="font-size: 3rem">RUANG POLI {{ $dokter }}</h1>
        <div class="row mb-1 mt-3">
            <div class="col text-center font-weight-bold" style="font-size:2.5rem;">SEDANG DIPANGGIL</div>
            <div class="col text-center font-weight-bold" style="font-size:2.5rem;">DAFTAR TUNGGU
            </div>
        </div>
    </header>
    <aside class="bg-white main-sidebar" style="width: 20px;z-index: 2 !important;height: 5000px;"></aside>
    <div class="container-fluid row mt-0">
        <div class="col">
            <iframe class="custom-iframe" scrolling="no"
                src="https://kkpm.banyumaskab.go.id/administrator/display_tv/ruang_poli"></iframe>
        </div>
        <div class="col mt-5 pt-5" style="font-size: 1.5rem;">
            <div class="table-responsive mt-5">
                <table class="table table-bordered table-striped table-hover mb-0" id="header" style="width:100%">
                    <thead class="bg bg-dark">
                        <tr>
                            <th class="col-2">No RM</th>
                            <th class="col-4">Nama</th>
                            <th class="col-4">Dokter</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="table-responsive table-container">
                @php
                    $scrol = isset($listTunggu) && count($listTunggu) >= 11 ? 'table-auto' : '';
                @endphp

                @if (empty($listTunggu) && $listTunggu == null && $listTunggu == '[]')
                    <table class="table table-bordered table-striped table-hover" id="listTunggu" style="width:100%">
                        <tbody>
                            <tr>
                                <td colspan="3" class="text-center">Tidak ada antrian</td>
                            </tr>
                        </tbody>
                    </table>
                @else
                    <table class="{{ $scrol }} table table-bordered table-striped table-hover" id="listTunggu"
                        style="width:100%">
                        <tbody>
                            @foreach ($listTunggu as $item)
                                <tr>
                                    <td class="col-2">{{ $item['pasien_no_rm'] }}</td>
                                    <td class="col-4">{{ $item['pasien_nama'] }}</td>
                                    <td class="col-4">{{ $item['pasien_alamat_min'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
    <footer class="container-fluid fixed-bottom bg-primary">
        <marquee class="marquee my-1" style="font-size: 2rem !important; color: #ffffff">
            "Kamu seorang pejuang. Lawan penyakit yang ada di tubuhmu dan semoga segera sembuh."
            &nbsp;&nbsp;|&nbsp;&nbsp;
            "Saya sangat menantikan kehadiranmu dengan penuh semangat. Segera sembuh, Sobat."
            &nbsp;&nbsp;|&nbsp;&nbsp;
            "Kita memiliki banyak impian untuk dicapai bersama dan kita memiliki lebih banyak hal untuk
            dicapai
            dalam hidup. Cepat sembuh, Sayang." &nbsp;&nbsp;|&nbsp;&nbsp;
            "Kamu pasti akan pulih karena saya tahu bahwa penyakitmu bisa dikalahkan dengan kekuatan dan
            kemauanmu. Segera sembuh dan kembali lebih kuat." &nbsp;&nbsp;|&nbsp;&nbsp;
            "Saya tahu kamu akan kembali lebih kuat dan lebih sehat, tidak ada yang bisa memenangkan tekad
            dan
            kekuatanmu. Semoga cepat sembuh." &nbsp;&nbsp;|&nbsp;&nbsp;
            "Teman yang terkasih, percayalah semuanya akan baik-baik saja. Semoga cepat sembuh!"
            &nbsp;&nbsp;|&nbsp;&nbsp;
            "Jangan takut, Sahabatku, doamu didengar. Dia akan menaklukkanmu dan memberimu kemenangan. Cepat
            Sembuh, Sob." &nbsp;&nbsp;|&nbsp;&nbsp;
            "Semua pasti ada hikmahnya, jangan larut dalam kesedihan. Bersemangatlah karena itu akan membuat
            keadaan lebih baik." &nbsp;&nbsp;|&nbsp;&nbsp;
            "Semangat. Tak apa kamu sekarang terbujur lemas di ranjang rumah sakit ini. Aku yakin kamu bisa
            melewati ini semua dan pulih seperti sedia kala." &nbsp;&nbsp;|&nbsp;&nbsp;
            "Hal terpenting adalah jangan pernah putus asa. Aku selalu berdoa, semoga kamu cepat sembuh."
            &nbsp;&nbsp;|&nbsp;&nbsp;
            "Anda sedang melalui situasi yang sulit, tetapi saya tahu Anda memiliki kekuatan untuk muncul
            dengan
            penuh kemenangan. Jaga diri Anda baik-baik dan jangan pernah menyerah!"
            &nbsp;&nbsp;|&nbsp;&nbsp;
            "Saya sangat mengagumi keberanian Anda menghadapi situasi ini. Anda adalah orang yang sangat
            pejuang
            dan saya tahu bahwa Anda akan menang. Saya mengirimi Anda pelukan hangat dan harapan terbaik
            saya."
            &nbsp;&nbsp;|&nbsp;&nbsp;
            "Ingatlah suatu hari, tidak lama lagi, kamu akan benar-benar sehat dan tersenyum kembali."
            &nbsp;&nbsp;|&nbsp;&nbsp;
            "Kesembuhan memang butuh waktu dan kerja keras, tapi kamu tidak sendiri. Kami selalu
            memikirkanmu
            dan berdoa untuk kesembuhanmu." &nbsp;&nbsp;|&nbsp;&nbsp;
            "Masa-masa sulit tidak bertahan lama, orang-orang tangguh melakukannya. Semoga cepat sembuh."
            &nbsp;&nbsp;|&nbsp;&nbsp;
            "Percayalah bahwa setiap penyakit selalu ada obatnya. Kamu hanya perlu berpikir positif dan
            bangkit
            dari keputusasaan." &nbsp;&nbsp;|&nbsp;&nbsp;
            "Janganlah kamu takut dengan rasa sakit, sebab dengan semangatmu, itu akan hilang. Aku akan
            menemanimu dan merawatmu sampai kamu pulih dan sembuh. Lekas pulih ya."
            &nbsp;&nbsp;|&nbsp;&nbsp;
            "Optimistislah, mulailah berpikir bahwa semuanya akan terjadi dan Anda akan segera mendapatkan
            kembali kesehatan Anda." &nbsp;&nbsp;|&nbsp;&nbsp;
            "Jadilah kuat karena segalanya akan menjadi lebih baik. Mungkin badai sekarang, tetapi tidak
            pernah
            hujan selamanya. Semoga cepat sembuh." &nbsp;&nbsp;|&nbsp;&nbsp;
            "Anda tidak tampak hebat saat sakit. Jadi cepatlah sembuh agar Anda terlihat menarik kembali.
            Semoga
            Anda cepat pulih." &nbsp;&nbsp;|&nbsp;&nbsp;
            "Rasa sakit itu nyata, tetapi begitu juga harapan. Semoga cepat sembuh."
        </marquee>
    </footer>

    <script type="text/javascript">
        let dokter = @json($dokter)

        async function getList() {
            const tableBody = document.querySelector("table tbody");
            tableBody.innerHTML = "";
            console.log("🚀 ~ dokter:", dokter)
            const norm = "";
            try {
                const response = await fetch("/api/kominfo/pendaftaran", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        norm: '',
                        tanggal: ''
                    }),
                });
                const data = await response.json();
                const filteredData = data.filter(item =>
                    item.dokter_nama === dokter && item.status_pulang === "Belum Pulang"
                );
                drawTable(filteredData);
            } catch (error) {
                console.error("Terjadi kesalahan saat mencari data:", error);
            }
        }

        function drawTable(data) {
            console.log("🚀 ~ drawTable ~ data:", data)
            console.log("🚀 ~ drawTable ~ data.length:", data.length)
            if (data.length > 0) {


                const tableBody = document.querySelector("table tbody");
                tableBody.innerHTML = ""; // Bersihkan konten sebelumnya

                data.forEach(item => {
                    const row = document.createElement("tr");

                    const noRmCell = document.createElement("td");
                    noRmCell.textContent = item.pasien_no_rm;
                    row.appendChild(noRmCell);
                    noRmCell.classList.add("col-2");

                    const namaCell = document.createElement("td");
                    namaCell.textContent = item.pasien_nama;
                    row.appendChild(namaCell);
                    namaCell.classList.add("col-4");

                    const dokterCell = document.createElement("td");
                    dokterCell.textContent = item.dokter_nama;
                    row.appendChild(dokterCell);
                    dokterCell.classList.add("col-4");

                    tableBody.appendChild(row);
                });

                // Memastikan animasi berjalan
                document.querySelector("#listTunggu").style.animation = 'scroll 20s linear infinite';
            } else {
                //draw tabel "Tidak ada antrian" coll span 3
                const tableBody = document.querySelector("table tbody");
                tableBody.innerHTML = ""; // Bersihkan konten sebelumnya
                const row = document.createElement("tr");
                const noRmCell = document.createElement("td");
                noRmCell.textContent = "Tidak ada antrian";
                noRmCell.colSpan = 3;
                //class text center
                noRmCell.classList.add("text-center");
                row.appendChild(noRmCell);
                tableBody.appendChild(row);
                //tidak usah scroll
                document.querySelector("#listTunggu").style.animation = 'none';
            }
        }

        getList();
        setInterval(() => {
            // Panggil fungsi untuk menggambar tabel
            getList();
        }, 40000);
    </script>
</body>

</html>
