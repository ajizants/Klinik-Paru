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
        <div class="row mb-1 pt-2">
            <div class="col text-center font-weight-bold" style="font-size:2.5rem;">DAFTAR TUNGGU RADIOLOGI</div>
            <div class="col text-center font-weight-bold" style="font-size:2.5rem;">DAFTAR TUNGGU LABORATORIUM</div>
        </div>
    </header>
    <div class="container-fluid row mt-4" style="font-size: 1.5rem !important;">
        <div class="col mt-2">
            <h2 class="text-center font-weight-bold">Daftar Tunggu Radiologi</h2>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover mb-0" id="header" style="width:100%">
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
                        <tbody>
                            <tr>
                                <td colspan="3" class="text-center">Tidak ada antrian</td>
                            </tr>
                        </tbody>
                    @else
                        <tbody>
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
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover mb-0" id="header" style="width:100%">
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
                    <tbody>
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
    @include('Display.footer')
    <audio id="morning-audio" src="{{ asset('audio/Indonesia_Raya.mp3') }}" preload="auto"></audio>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const audio = document.getElementById("morning-audio");
            audio.volume = 0.5; // Atur volume ke 50%

            function checkAndPlayAudio() {
                const now = new Date();
                const hours = now.getHours();
                const minutes = now.getMinutes();

                const playedToday = localStorage.getItem("audioPlayedDate");
                const today = now.toISOString().split('T')[0]; // Format: yyyy-mm-dd

                if (hours === 9 && minutes === 59 && playedToday !== today) {
                    audio.play().then(() => {
                        localStorage.setItem("audioPlayedDate", today);
                    }).catch((err) => {
                        console.log("Audio tidak bisa diputar otomatis: ", err);
                    });

                }
            }

            setInterval(checkAndPlayAudio, 60000); // Cek setiap 1 menit
        });
    </script>
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
            tableBody.innerHTML = "";
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
                if (data.length >= 12) {
                    // tableBody.classList.remove("table-auto");
                    // setTimeout(() => {
                    tableBody.classList.add("table-auto");
                    // }, 3000);
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
