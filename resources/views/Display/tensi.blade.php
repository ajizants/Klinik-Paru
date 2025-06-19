<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>KKPM | {{ isset($title) ? $title : '' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@300;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vendor/dist/css/adminlte.min.css') }}">
    <script src="{{ asset('vendor/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('vendor/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('vendor/dist/js/adminlte.min.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.0.1/socket.io.min.js "
        integrity="sha512-eVL5Lb9al9FzgR63gDs1MxcDS2wFu3loYAgjIH0+Hg38tCS8Ag62dwKyH+wzDb+QauDpEZjXbMn11blw8cbTJQ=="
        crossorigin=" anonymousÂ&nbsp;"></script>
    <style>
        html,
        body {
            overflow: hidden;
            /* margin-top: 75px; */
        }
        }

        .table-container {
            max-height: 40vh;
            /* Set max height untuk auto scroll */
            overflow: hidden;
            /* Sembunyikan scroll bar */
            position: relative;
            /* Untuk posisi absolut */
        }

        .table-auto {
            animation: scroll 50s linear infinite;
            -webkit-animation: scroll 50s linear infinite;
            animation-delay: 5s;
        }

        @keyframes scroll {
            0% {
                transform: translateY(0);
            }

            85% {
                transform: translateY(-100%);
            }

            86% {
                transform: translateY(0);
            }

            100% {
                transform: translateY(0);
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
            font-size: 30px;
            font-weight: bold;
            color: #1602fb;
        }
    </style>
</head>

<body>
    <header class="container-fluid  bg-primary mt-2">
        <h1 class="font-weight-bold text-center" style="font-size: 3rem">RUANG TENSI</h1>
    </header>
    <div class="container-fluid">
        <div class="card card-primary">
            <div class="card-header d-flex justify-content-center">
                <h1 class="card-title text-center font-weight-bold" style="font-size: 2rem !important;">SEDANG DIPANGGIL
                </h1>
            </div>
            <div class="card-body p-0">
                <table class="text-center">
                    <tbody>
                        <tr>
                            @php
                                $looping = 3;
                            @endphp
                            @if ($sedangDipanggil === [])
                                @for ($i = 0; $i < $looping; $i++)
                                    <td class="font-weight-bold align-middle"
                                        style="font-size: calc(2vw + 1rem); width: 33.33%;">
                                        <div class="text-center py-4">
                                            <span id="nama_loket_{{ $i }}" class="font-weight-bold"
                                                style="font-size: calc(5vw + 2rem); height: auto; line-height: 1;">
                                                -
                                            </span>
                                        </div>
                                    </td>
                                @endfor
                            @endif
                            @foreach ($sedangDipanggil as $index => $item)
                                <td class="font-weight-bold align-middle"
                                    style="font-size: calc(2vw + 1rem); width: 33.33%;">
                                    <div class="text-center py-4">
                                        <span id="nama_loket_{{ $index }}" class="font-weight-bold"
                                            style="font-size: calc(5vw + 2rem); height: auto; line-height: 1;">
                                            {{ $item['pasien_nama'] ?? '-' }}
                                        </span>
                                    </div>
                                </td>
                            @endforeach
                        </tr>
                        <tr>
                            @php
                                $looping = 3;
                            @endphp
                            @if ($sedangDipanggil === [])
                                @for ($i = 0; $i < $looping; $i++)
                                    <td class="font-weight-bold p-0"
                                        style="font-size: calc(2vw + 1rem); width: 33.33%;">
                                        <div class="text-center py-4">
                                            <span id="alamat_loket_{{ $i }}" class="font-weight-bold"
                                                style="font-size: calc(3vw + 1rem); height: auto; line-height: 1;">
                                                -
                                            </span>
                                        </div>
                                    </td>
                                @endfor
                            @endif
                            @foreach ($sedangDipanggil as $index => $item)
                                <td class="font-weight-bold p-0" style="font-size: calc(2vw + 1rem); width: 33.33%;">
                                    <div class="text-center py-4">
                                        <span id="alamat_loket_{{ $index }}" class="font-weight-bold"
                                            style="font-size: calc(3vw + 1rem); height: auto; line-height: 1;">
                                            {{ $item['kelurahan'] ?? '-' }}
                                        </span>
                                    </div>
                                </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="card card-primary col"> {{-- Tunggu --}}
                <div class="card-header d-flex justify-content-center">
                    <h1 class="card-title text-center font-weight-bold"
                        style="font-size: 2rem !important; text-align: center !important;">DAFTAR
                        TUNGGU</h1>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover mb-0" id="header"
                            style="width:100%">
                            <thead class="bg bg-dark" style="font-size: 1.5rem;">
                                <tr>
                                    <th class="col-3">Nama</th>
                                    <th class="col-2">Loket</th>
                                    <th class="col-3">Ket</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="table-responsive" style="height: 27rem; overflow-y: hidden; font-size: 1.5rem">
                        @php
                            $scrol = isset($listTunggu) && count($listTunggu) >= 4 ? 'table-auto' : '';
                        @endphp

                        <table class="table table-bordered table-striped table-hover {{ $scrol }}"
                            id="listTunggu" style="width:100%;">
                            @if (empty($listTunggu))
                                <tbody>
                                    <tr>
                                        <td colspan="3" class="text-center">Tidak ada antrian</td>
                                    </tr>
                                </tbody>
                            @else
                                <tbody>
                                    @foreach ($listTunggu as $item)
                                        @if ($item['keterangan'] === 'SKIP')
                                            @php
                                                $bg = 'bg-warning';
                                            @endphp
                                        @elseif ($item['keterangan'] === 'SEDANG DIPANGGIL')
                                            @php
                                                $bg = 'bg-success';
                                            @endphp
                                        @else
                                            @php
                                                $bg = 'bg-lime';
                                            @endphp
                                        @endif
                                        <tr>
                                            <td class="col-3">{{ $item['pasien_nama'] }}</td>
                                            <td class="col-2">{{ $item['ruang_nama'] }}</td>
                                            <td class="col-3 {{ $bg }}">{{ $item['keterangan'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
            <div class="card card-primary col" hidden> {{-- Selesai --}}
                <div class="card-header d-flex justify-content-center">
                    <h1 class="card-title text-center font-weight-bold"
                        style="font-size: 2rem !important; text-align: center !important;">DAFTAR
                        SELESAI</h1>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover mb-0" id="headerSelesai"
                            style="width:100%">
                            <thead class="bg bg-dark" style="font-size: 1.5rem;">
                                <tr>
                                    <th class="col-3">Nama</th>
                                    <th class="col-2">Loket</th>
                                    <th class="col-3">Jam</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="table-responsive" style="height: 27rem; overflow-y: hidden; font-size: 1.5rem">
                        @php
                            $scrol = isset($listSelesai) && count($listSelesai) >= 4 ? 'table-auto' : '';
                        @endphp

                        <table class="table table-bordered table-striped table-hover {{ $scrol }}"
                            id="listSelesai" style="width:100%;">
                            @if (empty($listSelesai))
                                <tbody>
                                    <tr>
                                        <td colspan="3" class="text-center">Tidak ada antrian</td>
                                    </tr>
                                </tbody>
                            @else
                                <tbody>
                                    @foreach ($listSelesai as $item)
                                        <tr>
                                            <td class="col-3">{{ $item['pasien_nama'] }}</td>
                                            <td class="col-2">{{ $item['ruang_nama'] }}</td>
                                            <td class="col-3">{{ $item['created_at_log'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            @endif
                        </table>
                    </div>
                </div>
            </div>

            <div class="card card-primary col"> {{-- Jadwal --}}
                <div class="card-header d-flex justify-content-center">
                    <h1 class="card-title text-center font-weight-bold"
                        style="font-size: 2rem !important; text-align: center !important;">JADWAL PRAKTIK DOKTER
                    </h1>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover mb-0" id="header"
                            style="width:100%">
                            <thead class="bg bg-dark" style="font-size: 1.5rem">
                                <tr>
                                    <th class="col-1">No</th>
                                    <th class="col-5">Dokter</th>
                                    <th class="col-3">Hari</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="table-responsive" style="height: 27rem; overflow-y: hidden; font-size: 1.5rem">
                        {!! $jadwal !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('Display.footer')
    <script type="text/javascript">
        async function getList() {
            const norm = "";
            try {
                const response = await fetch("/api/list/tunggu/tensi", {
                    method: "GET",
                    headers: {
                        "Content-Type": "application/json",
                    },
                });
                const data = await response.json();
                // console.log("🚀 ~ getList ~ data:", data)
                const tunggu = data.tunggu;
                //ambil 3 data pertama dari data.dataAtas
                const dataAtas = data.dataAtas.slice(0, 3);

                drawTable(tunggu);
                drawNotif(dataAtas);
            } catch (error) {
                console.error("Terjadi kesalahan saat mencari data:", error);
            }
        }

        function drawTable(data) {
            if (data.length > 0) {
                const tableBody = document.querySelector("#listTunggu tbody");
                tableBody.innerHTML = ""; // Bersihkan konten sebelumnya

                data.forEach(item => {
                    let bg;

                    switch (item.keterangan) {
                        case 'SEDANG DIPANGGIL':
                            bg = "bg-success";
                            break;
                        case 'SKIP':
                            bg = "bg-warning";
                            break;
                        case 'MENUNGGU DIPANGGIL':
                            bg = "bg-lime";
                            break;
                        default:
                            bg = "bg-info";
                            break;
                    }

                    const row = document.createElement("tr");

                    const nama = document.createElement("td");
                    nama.textContent = item.pasien_nama;
                    row.appendChild(nama);
                    nama.classList.add("col-2");

                    const loket = document.createElement("td");
                    loket.textContent = item.ruang_nama;
                    row.appendChild(loket);
                    loket.classList.add("col-2");

                    const status = document.createElement("td");
                    status.textContent = item.keterangan;
                    row.appendChild(status);
                    status.classList.add("col-3");
                    status.classList.add(bg);

                    tableBody.appendChild(row);
                });

                if (data.length >= 5) {
                    document.querySelector("#listTunggu").style.animation = 'scroll 50s linear infinite';
                } else {
                    document.querySelector("#listTunggu").style.animation = 'none';
                }

            } else {
                //draw tabel "Tidak ada antrian" coll span 3
                const tableBody = document.querySelector("#listTunggu tbody");
                tableBody.innerHTML = ""; // Bersihkan konten sebelumnya
                const row = document.createElement("tr");
                const noRmCell = document.createElement("td");
                noRmCell.textContent = "Tidak ada antrian";
                noRmCell.colSpan = 3;
                //class text center
                noRmCell.classList.add("text-center");
                row.appendChild(noRmCell);
                tableBody.appendChild(row);
            }
        }

        function drawTableSelesai(data) {
            if (data.length > 0) {
                const tableBody = document.querySelector("#listSelesai tbody");
                tableBody.innerHTML = ""; // Bersihkan konten sebelumnya

                data.forEach(item => {
                    const row = document.createElement("tr");

                    const noUrut = document.createElement("td");
                    noUrut.textContent = item.antrean_angka;
                    row.appendChild(noUrut);
                    noUrut.classList.add("col-2");

                    const penjamin = document.createElement("td");
                    penjamin.textContent = item.penjamin_nama;
                    row.appendChild(penjamin);
                    penjamin.classList.add("col-2");

                    const status = document.createElement("td");
                    status.textContent = item.keterangan;
                    row.appendChild(status);
                    status.classList.add("col-3 bg-success");

                    tableBody.appendChild(row);
                });

                if (data.length >= 5) {
                    document.querySelector("#listSelesai").style.animation = 'scroll 50s linear infinite';
                } else {
                    document.querySelector("#listSelesai").style.animation = 'none';
                }

            } else {
                //draw tabel "Tidak ada antrian" coll span 3
                const tableBody = document.querySelector("#listSelesai tbody");
                tableBody.innerHTML = ""; // Bersihkan konten sebelumnya
                const row = document.createElement("tr");
                const noRmCell = document.createElement("td");
                noRmCell.textContent = "Tidak ada antrian";
                noRmCell.colSpan = 3;
                //class text center
                noRmCell.classList.add("text-center");
                row.appendChild(noRmCell);
                tableBody.appendChild(row);
            }
        }

        function drawNotif(data) {
            console.log("🚀 ~ data:", data);

            data.forEach((item, index) => {
                console.log("🚀 ~ data.forEach ~ index:", index)
                // Isi nama pasien
                const namaEl = document.getElementById(`nama_loket_${index}`);
                if (namaEl) {
                    console.log("🚀 ~ data.forEach ~ namaEl:", namaEl)
                    namaEl.textContent = item.pasien_nama || '-';
                }

                // Isi kelurahan
                const kelurahanEl = document.getElementById(`alamat_loket_${index}`);
                if (kelurahanEl) {
                    kelurahanEl.textContent = item.kelurahan || '-';
                }
            });
        }


        var socketIO = io.connect('wss://kkpm.banyumaskab.go.id:3131/', {
            // path: '/socket.io',
            transports: ['websocket',
                'polling',
                'flashsocket'
            ]
        });

        socketIO.on('connectParu', () => {
            const sessionID = socketIO.id
            $('#socket-id').html(sessionID)
            console.log("Socket ID : " + sessionID)
        });
        socketIO.on('reload', (msg) => {
            if (msg == 'paru_ruang_tensi') {
                // reload_table();
                getList();
            }
        });
    </script>
</body>

</html>
