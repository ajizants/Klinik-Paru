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

</head>

<body>
    <header class="container-fluid  bg-primary mt-2">
        <h1 class="font-weight-bold text-center" style="font-size: 3rem">RUANG TENSI</h1>
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
                                            <td class="col-2">{{ $item['antrean_angka'] }}</td>
                                            <td class="col-2">{{ $item['penjamin_nama'] }}</td>
                                            <td class="col-3 {{ $bg }}">{{ $item['keterangan'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            @endif
                        </table>
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
                                    <th class="col-1">No</th>
                                    <th class="col-2">Hari</th>
                                    <th>Waktu</th>
                                    <th class="col-5">Dokter</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="table-responsive" style="height: 23.3rem; overflow-y: hidden; font-size: 1.5rem">
                        <table class="table-auto table table-bordered table-striped table-hover" id="listJadwal"
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
                        </table>
                    </div>
                </div>
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
                    noUrut.classList.add("col-2");

                    const penjamin = document.createElement("td");
                    penjamin.textContent = item.penjamin_nama;
                    row.appendChild(penjamin);
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
        let count = 0;
        socketIO.on('reload', (msg) => {
            if (msg == 'paru_ruang_tensi') {
                // reload_table();
                getList();
                count++;
                if (count == 1) {
                    alert('Tensi 1' + count)
                } else if (count == 2) {
                    alert('Tensi 1' + count)
                } else if (count == 3) {
                    alert('Tensi 1' + count)
                    count = 0
                }
            }
        });
    </script>
</body>

</html>
