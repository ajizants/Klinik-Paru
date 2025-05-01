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
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('vendor/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/dist/css/adminlte.min.css') }}">
    <!-- Theme style -->
    <link type="text/css" rel="stylesheet" href="{{ asset('css/display.css') }}">
    <style>
        html,
        body {
            overflow: hidden;
        }

        .custom-iframe {
            width: 100%;
            height: 100vh;
            border: none;
            overflow: hidden;
            -ms-overflow-style: none;
            /* Hides scrollbar in Internet Explorer */
            scrollbar-width: none;
            /* Hides scrollbar in Firefox */
        }

        .custom-iframe::-webkit-scrollbar {
            display: none;
            /* Hides scrollbar in Chrome, Safari, and newer versions of Edge */
        }

        .table-container {
            min-height: 100vh;
            /* height: 47.4vh; */
            /* Set max height untuk auto scroll */
            overflow: hidden;
            /* Sembunyikan scroll bar */
            position: relative;
            /* Untuk posisi absolut */
            font-size: 2rem;
        }

        .table-container3 {
            height: 30vh;
            /* Set max height untuk auto scroll */
            overflow: hidden;
            /* Sembunyikan scroll bar */
            position: relative;
            /* Untuk posisi absolut */
            font-size: 2rem;
        }

        .table-container2 {
            max-height: 353px;
            /* Set max height untuk auto scroll */
            overflow: hidden;
            /* Sembunyikan scroll bar */
            position: relative;
            /* Untuk posisi absolut */
            font-size: 2rem;
        }

        .table-auto {
            animation: scroll 30s linear infinite;
            -webkit-animation: scroll 30s linear infinite;
        }

        @keyframes scroll {
            from {
                transform: translateY(0);
                /* Mulai dari atas */
            }

            to {
                transform: translateY(-100%);
                /* Scroll ke atas sepenuhnya */
            }
        }



        .marquee-container {
            width: 80%;
            overflow: hidden;
            background-color: #fff;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .marquee {
            /* display: block;
            white-space: nowrap; */
            font-size: 30px;
            font-weight: bold;
            color: #1602fb;
        }
    </style>
    <script src="{{ asset('vendor/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('vendor/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('vendor/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- InputMask -->
    <script src="{{ asset('vendor/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('vendor/plugins/inputmask/jquery.inputmask.min.js') }}"></script>
    <!-- DataTables  & Plugins -->

    <!-- AdminLTE App -->
    <script src="{{ asset('vendor/dist/js/adminlte.min.js') }}"></script>
    {{-- <script src="{{ asset('public/js/anjunganMandiri.js') }}"></script> --}}
</head>

<body>
    <header class="container-fluid fixed-top bg-primary">
        <h1 class="font-weight-bold text-center" style="font-size: 3rem">LOKET FARMASI</h1>
        <div class="row mb-1">
            <div class="col text-center font-weight-bold" style="font-size:2.5rem;">DAFTAR TUNGGU</div>
            <div class="col text-center font-weight-bold" style="font-size:2.5rem;">DAFTAR SELESAI</div>
        </div>
    </header>
    <div class="container-fluid row mt-5">
        <div class="col mt-5">
            <div class="mt-5"">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="header" style="width:100%">
                        <thead class="bg bg-dark">
                            <tr>
                                <th class="col-2">No RM</th>
                                <th class="col-3">Nama Pasien</th>
                                <th>Nama Dokter</th>
                                <th class="col-2">Keterangan</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="table-responsive table-container">
                    @if (empty($listMenunggu))
                        <table class="table table-bordered table-striped table-hover" id="listMenunggu"
                            style="width:100%">
                            <tbody>
                                <tr>
                                    <td colspan="3" class="text-center">Tidak ada antrian</td>
                                </tr>
                            </tbody>
                        </table>
                    @else
                        <table class="table-auto table table-bordered table-striped table-hover" id="listMenunggu"
                            style="width:100%">
                            <tbody>
                                @foreach ($listMenunggu as $item)
                                    <tr>
                                        <td class="col-2">{{ $item['pasien_no_rm'] }}</td>
                                        <td class="col-3">{{ $item['pasien_nama'] }}</td>
                                        <td>{{ $item['dokter_nama'] }}</td>
                                        <td class="col-2">{{ $item['ket'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
        <div class="col mt-5">
            <div class="mt-5">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="header" style="width:100%">
                        <thead class="bg bg-dark">
                            <tr>
                                <th class="col-2">No RM</th>
                                <th class="col-3">Nama Pasien</th>
                                <th>Nama Dokter</th>
                                <th class="col-2">Keterangan</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="table-responsive table-container3">
                    @if (empty($listSelesai))
                        <table class="table table-bordered table-striped table-hover" id="listSelesai"
                            style="width:100%">
                            <tbody style="font-size: 20px">
                                <tr>
                                    <td colspan="3" class="text-center">Tidak ada antrian</td>
                                </tr>
                            </tbody>
                        </table>
                    @else
                        <table class="table-auto table table-bordered table-striped table-hover" id="listSelesai"
                            style="width:100%">
                            <tbody style="font-size: 20px">
                                @foreach ($listSelesai as $item)
                                    <tr>
                                        <td class="col-2">{{ $item['pasien_no_rm'] }}</td>
                                        <td class="col-3">{{ $item['pasien_nama'] }}</td>
                                        <td>{{ $item['dokter_nama'] }}</td>
                                        <td class="col-2">{{ $item['ket'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
            <div class="card card-primary"> {{-- Jadwal --}}
                <div class="card-header d-flex justify-content-center">
                    <h1 class="card-title text-center font-weight-bold"
                        style="font-size: 2rem !important; text-align: center !important;">JADWAL PRAKTIK DOKTER</h1>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover mb-0" id="header"
                            style="width:100%">
                            <thead class="bg bg-dark" style="font-size: 1.5rem">
                                <tr>
                                <tr>
                                    <th class="col-1">No</th>
                                    <th class="col-5">Dokter</th>
                                    <th class="col-3">Hari</th>
                                    <th>Waktu</th>
                                </tr>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="table-responsive" style="height: 23.3rem; overflow-y: hidden; font-size: 2rem">
                        {{-- <table class="table-auto table table-bordered table-striped table-hover" id="listJadwal"
                            style="width:100%">
                            <tbody id="listJadwal">
                                @foreach ($jadwal as $item)
                                    <td class="col-1">{{ $loop->iteration }}</td>
                                    <td class="col-2">{{ $item['nama_hari'] }}</td>
                                    <td>
                                        <!-- Convert and display waktu_mulai_poli and waktu_selesai_poli -->
                                        {{ \Carbon\Carbon::createFromTimestamp($item['waktu_mulai_poli'])->format('H:i') }}
                                        -
                                        {{ \Carbon\Carbon::createFromTimestamp($item['waktu_selesai_poli'])->format('H:i') }}
                                    </td>
                                    <td class="col-5">{{ $item['admin_nama'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table> --}}
                        {!! $jadwal !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('Display.footer')
    <!-- jQuery -->
    <script src="{{ asset('vendor/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('vendor/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.0.1/socket.io.min.js "
        integrity="sha512-eVL5Lb9al9FzgR63gDs1MxcDS2wFu3loYAgjIH0+Hg38tCS8Ag62dwKyH+wzDb+QauDpEZjXbMn11blw8cbTJQ=="
        crossorigin=" anonymous"></script>

    <script type="text/javascript">
        async function getList() {
            const tableBody = document.querySelector("table tbody");
            tableBody.innerHTML = "";
            const norm = "";
            try {
                const response = await fetch("/api/list/tunggu/farmasi", {
                    method: "GET",
                    headers: {
                        "Content-Type": "application/json",
                    },
                });
                const data = await response.json();
                console.log("🚀 ~ getList ~ data:", data)

                drawTable(data);
            } catch (error) {
                console.error("Terjadi kesalahan saat mencari data:", error);
            }
        }

        function drawTable(data) {
            console.log("🚀 ~ drawTable ~ data:", data);

            // Select the table bodies for "Menunggu" and "Selesai"
            const menungguTableBody = document.querySelector("#listMenunggu tbody");
            const selesaiTableBody = document.querySelector("#listSelesai tbody");

            // Clear previous contents in both tables
            menungguTableBody.innerHTML = "";
            selesaiTableBody.innerHTML = "";

            if (data.length > 0) {
                let menungguCount = 0; // Counter for "Menunggu" items
                data.forEach(item => {
                    const row = document.createElement("tr");

                    const noRmCell = document.createElement("td");
                    noRmCell.textContent = item.pasien_no_rm;
                    noRmCell.classList.add("col-2");
                    row.appendChild(noRmCell);

                    const namaCell = document.createElement("td");
                    namaCell.textContent = item.pasien_nama;
                    namaCell.classList.add("col-3");
                    row.appendChild(namaCell);

                    const dokterCell = document.createElement("td");
                    dokterCell.textContent = item.dokter_nama;
                    row.appendChild(dokterCell);

                    const ketCell = document.createElement("td");
                    ketCell.textContent = item.ket;
                    ketCell.classList.add("col-2");
                    row.appendChild(ketCell);

                    // Append the row to the appropriate table based on the "ket" value
                    if (item.ket === "Menunggu") {
                        menungguTableBody.appendChild(row);
                        menungguCount++;
                    } else if (item.ket === "Selesai") {
                        selesaiTableBody.appendChild(row);
                    }
                });

                // Check if scroll animation should be applied based on "Menunggu" count
                if (menungguCount >= 10) {
                    document.querySelector("#listMenunggu").style.animation = 'scroll 30s linear infinite';
                } else {
                    document.querySelector("#listMenunggu").style.animation = 'none';
                }

            } else {
                // Handle empty case for both tables
                const noDataRow = document.createElement("tr");
                const noDataCell = document.createElement("td");
                noDataCell.textContent = "Tidak ada antrian";
                noDataCell.colSpan = 4;
                noDataCell.classList.add("text-center");
                noDataRow.appendChild(noDataCell);

                menungguTableBody.appendChild(noDataRow.cloneNode(true));
                selesaiTableBody.appendChild(noDataRow);
            }
        }



        var socketIO = io.connect("wss://kkpm.banyumaskab.go.id:3131/", {
            // path: '/socket.io',
            transports: ["websocket", "polling", "flashsocket"],
        });

        socketIO.on("connectParu", () => {
            const sessionID = socketIO.id;
            $("#socket-id").html(sessionID);
            console.log("Socket ID : " + sessionID);
        });

        socketIO.on("reload", (msg) => {
            if (msg == "paru_ruang_poli") {
                getList()
            }
            if (msg == "paru_notifikasi_ruang_poli") {
                const notif = new Audio("/audio/dingdong.mp3");
                notif.load();
                notif.play();
                getList()
            }
            if (msg == "paru_notifikasi_ruang_tensi_1") {
                const notif = new Audio("/audio/dingdong.mp3");
                notif.load();
                notif.play();
                getList()
            }

        });
    </script>

</body>

</html>
