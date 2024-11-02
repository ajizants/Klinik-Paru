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
    {{-- <div class="p-0" id="player"></div> --}}
    <div class="container-fluid row">
        <div class="col mt-2">
            <h2 class="text-center font-weight-bold">Daftar Tunggu Radiologi</h2>
            <div class="table-responsive table-container2">
                <table class="table table-bordered table-striped table-hover" id="header" style="width:100%">
                    <thead class="bg bg-dark">
                        <tr>
                            <th class="col-2">No RM</th>
                            <th class="col-4">Nama Pasien</th>
                            <th class="col-2">Jam Mulai</th>
                            <th class="col-2">Estimasi Selesai</th>
                            <th class="col-2">Status</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="table-responsive table-container2">
                @php
                    $scrol = isset($tungguLab) && count($tungguLab) > 12 ? 'table-auto' : '';
                @endphp

                <table class="table table-bordered table-striped table-hover {{ $scrol }}" id="tungguRo"
                    style="width:100%">
                    @if (empty($tungguRo))
                        <tbody style="font-size: 20px">
                            <tr>
                                <td colspan="3" class="text-center">Tidak ada antrian</td>
                            </tr>
                        </tbody>
                    @else
                        <tbody style="font-size: 20px">
                            @foreach ($tungguRo as $item)
                                @if ($item['status'] === 'Belum' || $item['status'] === 'Belum Selesai')
                                    @php
                                        $bg = 'bg-warning';
                                    @endphp
                                @else
                                    @php
                                        $bg = 'bg-success';
                                    @endphp
                                @endif
                                <tr>
                                    <td class="col-2">{{ $item['norm'] }}</td>
                                    <td class="col-4">{{ $item['nama'] }}</td>
                                    <td class="col-2">{{ $item['jam_masuk'] }}</td>
                                    <td class="col-2">{{ $item['estimasi'] }} menit</td>
                                    <td class="col-2 {{ $bg }}">{{ $item['status'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    @endif
                </table>
            </div>
        </div>
        <div class="col mt-2">
            <h2 class="text-center font-weight-bold">Daftar Tunggu Laboratorium</h2>
            <div class="table-responsive table-container2">
                <table class="table table-bordered table-striped table-hover" id="header" style="width:100%">
                    <thead class="bg bg-dark">
                        <tr>
                            <th class="col-2">No RM</th>
                            <th class="col-4">Nama Pasien</th>
                            <th class="col-2">Jam Mulai</th>
                            <th class="col-2">Estimasi Selesai</th>
                            <th class="col-2">Status</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="table-responsive table-container2">
                @php
                    $scrol = isset($tungguLab) && count($tungguLab) > 12 ? 'table-auto' : '';
                @endphp

                <table class="table table-bordered table-striped table-hover {{ $scrol }}" id="tungguLab"
                    style="width:100%">
                    <tbody style="font-size: 20px">
                        @if (empty($tungguLab) || count($tungguLab) == 0)
                            <tr>
                                <td colspan="3" class="text-center">Tidak ada antrian</td>
                            </tr>
                        @else
                            @foreach ($tungguLab as $item)
                                @if ($item['status'] === 'Belum' || $item['status'] === 'Belum Selesai')
                                    @php
                                        $bg = 'bg-warning';
                                    @endphp
                                @else
                                    @php
                                        $bg = 'bg-success';
                                    @endphp
                                @endif
                                <tr>
                                    <td class="col-2">{{ $item['norm'] }}</td>
                                    <td class="col-4">{{ $item['nama'] }}</td>
                                    <td class="col-2">{{ $item['jam_masuk'] }}</td>
                                    <td class="col-2">{{ $item['estimasi'] }} menit</td>
                                    <td class="col-2 {{ $bg }}">{{ $item['status'] }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

        </div>
    </div>
    <footer class="container-fluid fixed-bottom bg-primary">
        <marquee class="marquee my-3" style="font-size: 45px !important; color: #ffffff">
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
    <!-- jQuery -->
    <script src="{{ asset('vendor/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('vendor/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script type="text/javascript">
        async function getList(ruang) {
            const url = ruang === "ro" ? "/api/list/tunggu/ro" : "/api/list/tunggu/lab";

            try {
                const response = await fetch(url, {
                    method: "GET",
                    headers: {
                        "Content-Type": "application/json",
                    },
                });
                const data = await response.json();
                console.log("🚀 ~ getList ~ data:", data);

                drawTable(data, ruang);
            } catch (error) {
                console.error("Terjadi kesalahan saat mencari data:", error);
            }
        }

        function drawTable(data, ruang) {
            console.log("🚀 ~ drawTable ~ ruang:", ruang)
            const tableBody = document.getElementById(ruang === "ro" ? "tungguRo" : "tungguLab");
            console.log("🚀 ~ drawTable ~ tableBody:", tableBody)
            tableBody.innerHTML = ""; // Bersihkan konten sebelumnya
            tableBody.style.fontSize = "20px";

            if (data.length > 0) {
                data.forEach((item) => {
                    let bg;
                    if (item.status === 'Belum' || item.status === 'Belum Selesai') {
                        bg = "bg-warning";
                    } else if (item.status === 'Selesai') {
                        bg = "bg-success";
                    }
                    const row = document.createElement("tr");

                    const noRmCell = document.createElement("td");
                    noRmCell.textContent = item.norm;
                    noRmCell.classList.add("col-2");
                    row.appendChild(noRmCell);

                    const namaCell = document.createElement("td");
                    namaCell.textContent = item.nama;
                    namaCell.classList.add("col-4");
                    row.appendChild(namaCell);

                    const jamMulaiCell = document.createElement("td");
                    jamMulaiCell.textContent = item.jam_masuk;
                    jamMulaiCell.classList.add("col-2");
                    row.appendChild(jamMulaiCell);

                    const estimasiCell = document.createElement("td");
                    estimasiCell.textContent = item.estimasi + " menit";
                    estimasiCell.classList.add("col-2");
                    row.appendChild(estimasiCell);

                    const status = document.createElement("td");
                    status.textContent = item.status;
                    status.classList.add("col-2");
                    status.classList.add(bg);
                    row.appendChild(status);

                    tableBody.appendChild(row);
                });

                // Memastikan animasi berjalan
                if (data.length > 6) {
                    tableBody.classList.add("table-auto");
                    document.querySelector(".table-auto").style.animation = "scroll 30s linear infinite";
                }
            } else {
                const row = document.createElement("tr");
                const noRmCell = document.createElement("td");
                noRmCell.textContent = "Tidak ada antrian";
                noRmCell.colSpan = 3;
                noRmCell.classList.add("text-center");
                row.appendChild(noRmCell);
                tableBody.appendChild(row);
            }
        }

        // Panggil fungsi setiap 20 detik
        setInterval(() => {
            getList("lab");
            getList("ro");
        }, 20000);
    </script>
</body>

</html>
