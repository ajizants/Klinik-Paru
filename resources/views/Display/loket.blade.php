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
    <link type="text/css" rel="stylesheet" href="{{ asset('css/display.css') }}">
    <!-- jQuery -->
    <script src="{{ asset('vendor/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('vendor/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('vendor/dist/js/adminlte.min.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.0.1/socket.io.min.js "
        integrity="sha512-eVL5Lb9al9FzgR63gDs1MxcDS2wFu3loYAgjIH0+Hg38tCS8Ag62dwKyH+wzDb+QauDpEZjXbMn11blw8cbTJQ=="
        crossorigin=" anonymousÂ&nbsp;"></script>
    <style>
        .fs3 {
            font-size: 3rem !important;
        }

        .fs2 {
            font-size: 2rem !important;
        }
    </style>

</head>

<body>
    <header class="container-fluid  bg-primary mt-2">
        <h1 class="font-weight-bold text-center" style="font-size: 3rem">LOKET PENDAFTARAN</h1>
    </header>
    <div class="container-fluid row px-2 mx-2">
        <div class="col-7">
            <div class="card card-primary"> {{-- Dipanggil --}}
                <div class="card-header d-flex justify-content-center">
                    <h1 class="card-title text-center font-weight-bold"
                        style="font-size: 2rem !important; text-align: center !important;">SEDANG
                        DIPANGGIL</h1>
                </div>
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col">
                            <div class="card card-dark mb-0">
                                <div class="card-header d-flex justify-content-center">
                                    <h1 class="card-title" style="font-size: 3rem;">Loket Pendaftaran 1</h1>
                                </div>
                                <div class="card-body p-0">
                                    <div class="text-center py-4">
                                        <span id="notif_loket_1" class="font-weight-bold"
                                            style="font-size: 19rem; height: auto; line-height: 1;">
                                            {{ $loket1['antrean_nomor'] ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card card-dark mb-0">
                                <div class="card-header d-flex justify-content-center">
                                    <h3 class="card-title" style="font-size: 3rem;">Loket Pendaftaran 2</h3>
                                </div>
                                <div class="card-body p-0">
                                    <div class="text-center py-4">
                                        <span id="notif_loket_2" class="font-weight-bold"
                                            style="font-size: 19rem; height: auto; line-height: 1;">
                                            {{ $loket2['antrean_nomor'] ?? '-' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
        <div class="col-5">
            <div class="card card-primary"> {{-- Tunggu --}}
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
                                    <th class="col-2">Antrean</th>
                                    <th class="col-2">Jaminan</th>
                                    <th class="col-3">Keterangan</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="table-responsive table-container" style="height: 23.3rem; font-size: 1.5rem;">
                        @php
                            $scrol = isset($listTunggu) && count($listTunggu) >= 7 ? 'table-auto' : '';
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
                                            <td class="col-2 fs3">{{ $item['antrean_angka'] }}
                                            </td>
                                            <td class="col-2 fs3">{{ $item['penjamin_nama'] }}
                                            </td>
                                            <td class="col-3 {{ $bg }}">{{ $item['keterangan'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
            <div class="card card-primary"> {{-- Selesai --}}
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
                                    <th class="col-2">Antrean</th>
                                    <th class="col-2">Loket</th>
                                    <th class="col-2">Ket</th>
                                    <th class="col-3">Jam</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="table-responsive table-container" style=" font-size: 1.5rem;">
                        @php
                            $scrol = isset($listSelesai) && count($listSelesai) >= 7 ? 'table-auto' : '';
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
                                            <td class="col-2">{{ $item['antrean_nomor'] }}</td>
                                            <td class="col-2">{{ $item['menuju_ke'] }}</td>
                                            <td class="col-2">Selesai di Daftar</td>
                                            <td class="col-3">{{ $item['created_at'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            @endif
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
    @include('Display.footer')
    <audio id="morning-audio" src="{{ asset('audio/IndonesiaRaya.mp3') }}" preload="auto"></audio>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const audio = document.getElementById("morning-audio");
            audio.volume = 0.7; // Atur volume ke 50%

            function checkAndPlayAudio() {
                // localStorage.setItem("audioPlayedDate", "");
                console.log("🚀 ~ checkAndPlayAudio ~ checkAndPlayAudio:", checkAndPlayAudio)
                const now = new Date();
                const hours = now.getHours();
                const minutes = now.getMinutes();

                const playedToday = localStorage.getItem("audioPlayedDate");
                console.log("🚀 ~ checkAndPlayAudio ~ playedToday:", playedToday)
                const today = now.toISOString().split('T')[0]; // Format: yyyy-mm-dd
                console.log("🚀 ~ checkAndPlayAudio ~ today:", today)

                if (hours === 10 && minutes === 00 && playedToday !== today) {
                    audio.play().then(() => {
                        localStorage.setItem("audioPlayedDate", today);
                    }).catch((err) => {
                        console.log("Audio tidak bisa diputar otomatis: ", err);
                    });
                    console.log("🚀 ~ audio.play ~ localStorage:", localStorage)

                }
            }
            setInterval(checkAndPlayAudio, 60000); // Cek setiap 1 menit
        });
    </script>
    <script type="text/javascript">
        async function getList() {
            const norm = "";
            try {
                const response = await fetch("/api/list/tunggu/loket", {
                    method: "GET",
                    headers: {
                        "Content-Type": "application/json",
                    },
                });
                const data = await response.json();
                // console.log("🚀 ~ getList ~ data:", data)
                const tunggu = data.tunggu;
                const panggil = data.panggil;

                drawTable(tunggu);
                drawNotif(panggil);
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

                    const noUrut = document.createElement("td");
                    noUrut.textContent = item.antrean_angka;
                    row.appendChild(noUrut);
                    noUrut.classList.add("fs3");
                    noUrut.classList.add("col-2");

                    const penjamin = document.createElement("td");
                    penjamin.textContent = item.penjamin_nama;
                    row.appendChild(penjamin);
                    penjamin.classList.add("fs3");
                    penjamin.classList.add("col-2");

                    const status = document.createElement("td");
                    status.textContent = item.keterangan;
                    row.appendChild(status);
                    status.classList.add("col-3");
                    status.classList.add(bg);

                    tableBody.appendChild(row);
                });

                if (data.length >= 7) {
                    document.querySelector("#listTunggu").style.animation = 'scroll 30s linear infinite';
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

                if (data.length >= 7) {
                    document.querySelector("#listSelesai").style.animation = 'scroll 30s linear infinite';
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
            console.log("🚀 ~ data:", data)
            // Filter data untuk Loket Pendaftaran 1 dan 2 dengan waktu_submit null
            let loket1 = data.find(item => item.menuju_ke === "Loket Pendaftaran 1" && item.waktu_submit ===
                null);
            console.log("🚀 ~ loket1:", loket1)
            let loket2 = data.find(item => item.menuju_ke === "Loket Pendaftaran 2" && item.waktu_submit ===
                null);
            console.log("🚀 ~ loket2:", loket2, loket2)

            // Ambil elemen DOM
            let notifLoket1 = document.getElementById("notif_loket_1");
            let notifLoket2 = document.getElementById("notif_loket_2");

            // Update nomor antrean dan kategori untuk Loket 1
            if (loket1) {
                notifLoket1.innerHTML = `
                            ${loket1.antrean_nomor}
                    `;
            }
            if (loket2) {
                notifLoket2.innerHTML = `
                            ${loket2.antrean_nomor}
                    `;
            }
        }


        function reload_table() {
            tableData.ajax.reload(null, false);
            //  $("#div_ulangi_panggilan").html( /*html*/ ``)
            $.ajax({
                type: "POST",
                url: "https://kkpm.banyumaskab.go.id/administrator/display_tv/loket_pendaftaran_get_data",
                dataType: "json",
                beforeSend: function() {
                    $(".spinnerReloadTable").show()
                },
                data: {
                    tanggal: $('#tanggal_filter').val()
                },
                success: function(e) {
                    $(".spinnerReloadTable").hide()
                    data = e.data
                    antrean_sedang_dipanggil = data[0]['antrean_nomor']
                    antrean_sedang_dipanggil_menuju_ke = data[0]['menuju_ke']
                    if (antrean_sedang_dipanggil != null) {
                        $("#antrean_sedang_dipanggil").html( /*html*/ `${antrean_sedang_dipanggil}`)
                        $("#antrean_sedang_dipanggil_menuju_ke").html( /*html*/
                            `${antrean_sedang_dipanggil_menuju_ke}`)
                    } else {
                        $("#antrean_sedang_dipanggil").html( /*html*/ `-`)
                        $("#antrean_sedang_dipanggil_menuju_ke").html( /*html*/ ``)
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    Swal.close();
                    // alertz(xhr.responseText);
                    iziToast.warning({
                        title: "'" + xhr.responseText + "'",
                        position: 'bottomRight'
                    });
                }
            })
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
            if (msg == 'paru_loket_pendaftaran') {
                // reload_table();
                getList();
            }
        });
    </script>
</body>

</html>
