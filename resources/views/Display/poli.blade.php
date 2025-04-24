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
        <h1 class="font-weight-bold text-center" style="font-size: 3rem">RUANG POLI {{ $dokter }}</h1>
        {{-- <div class="row mb-1 mt-3">
            <div class="col text-center font-weight-bold" style="font-size:2.5rem;">SEDANG DIPANGGIL</div>
            <div class="col text-center font-weight-bold" style="font-size:2.5rem;">DAFTAR TUNGGU
            </div>
        </div> --}}
    </header>
    <div class="container-fluid">
        <div class="card card-primary"> {{-- Sedang dipanggil --}}
            <div class="card-header d-flex justify-content-center">
                <h1 class="card-title text-center font-weight-bold" style="font-size: 2rem !important;">SEDANG DIPANGGIL
                </h1>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered text-center">
                    <tbody>
                        <tr>
                            <td class="font-weight-bold" style="font-size: calc(2vw + 1rem); width: 33.33%;">
                                <div class="text-center py-2">
                                    <span id="nama_pasien" class="font-weight-bold"
                                        style="font-size: calc(5vw + 2rem); height: auto; line-height: 1;">
                                        {{ $dataPanggil['pasien_nama'] ?? '-' }}
                                    </span>
                                </div>
                                <div style="font-size: calc(3vw + 1rem); height: auto; line-height: 1;"
                                    class="text-center py-2 font-weight-bold">
                                    Dari
                                    <span class="" id="alamat_pasien">
                                        {{ $dataPanggil['kelurahan'] ?? '-' }}
                                    </span>
                                </div>
                                <div style="font-size: calc(3vw + 1rem); height: auto; line-height: 1;"
                                    class="text-center py-2 font-weight-bold">
                                    Silahkan Menuju Ke
                                    <span id="ruang_pasien" class="">
                                        {{ $dataPanggil['menuju_ke'] ?? '-' }}
                                    </span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col card card-primary"> {{-- Tunggu --}}
                <div class="card-header d-flex justify-content-center">
                    <h1 class="card-title text-center font-weight-bold" style="font-size: 2rem !important;">Darfar
                        Tunggu
                    </h1>
                </div>
                <div class="card-body p-0" style="font-size: 1.5rem">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover mb-0" id="header"
                            style="width:100%">
                            <thead class="bg bg-dark">
                                <tr>
                                    <th class="col-2">No RM</th>
                                    <th class="col-4">Nama</th>
                                    <th class="col-4">Alamat</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="table-responsive table-container">
                        @php
                            $scrol = isset($listTunggu) && count($listTunggu) >= 4 ? 'table-auto' : '';
                        @endphp

                        @if (empty($listTunggu) && $listTunggu == null && $listTunggu == '[]')
                            <table class="table table-bordered table-striped table-hover" id="listTunggu"
                                style="width:100%">
                                <tbody>
                                    <tr>
                                        <td colspan="3" class="text-center">Tidak ada antrian</td>
                                    </tr>
                                </tbody>
                            </table>
                        @else
                            <table class="{{ $scrol }} table table-bordered table-striped table-hover"
                                id="listTunggu" style="width:100%">
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
        let id = @json($id)

        async function getList() {
            const norm = "";
            try {
                const response = await fetch("/api/list/tunggu/poli/" + id, {
                    method: "GET",
                    headers: {
                        "Content-Type": "application/json",
                    },
                });
                const data = await response.json();
                // console.log("🚀 ~ getList ~ data:", data)
                const tunggu = data.tunggu;
                //ambil 3 data pertama dari data.dataAtas
                const dataPanggil = data.dataPanggil;

                drawTable(tunggu);
                drawNotif(dataPanggil);
            } catch (error) {
                console.error("Terjadi kesalahan saat mencari data:", error);
            }
        }

        function drawTable(data) {
            console.log("🚀 ~ drawTable ~ data:", data)
            console.log("🚀 ~ drawTable ~ data.length:", data.length)
            if (data.length > 0) {


                const tableBody = document.querySelector("#listTunggu tbody");
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

                    const alamatCell = document.createElement("td");
                    alamatCell.textContent = item.pasien_alamat_min;
                    row.appendChild(alamatCell);
                    alamatCell.classList.add("col-4");

                    tableBody.appendChild(row);
                });

                // Memastikan animasi berjalan
                if (data.length <= 3) {
                    document.querySelector("#listTunggu").style.animation = 'none';
                } else if (data.length > 3 && data.length <= 15) {
                    document.querySelector("#listTunggu").style.animation = 'scroll 50s linear infinite';
                } else {
                    document.querySelector("#listTunggu").style.animation = 'scroll 80s linear infinite';
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
                //tidak usah scroll
                document.querySelector("#listTunggu").style.animation = 'none';
            }
        }

        function drawNotif(data) {
            console.log("🚀 ~ data:", data);

            const nama = document.getElementById("nama_pasien");
            const alamat = document.getElementById("alamat_pasien");
            const ruang = document.getElementById("ruang_pasien");
            console.log("🚀 ~ ruang:", ruang)
            console.log("🚀 ~ data:", data.menuju_ke)

            nama.textContent = data.pasien_nama;
            alamat.textContent = data.kelurahan;
            ruang.textContent = data.menuju_ke;

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
            if (msg == 'paru_ruang_poli') {
                // reload_table();
                getList();
            }
        });
    </script>
</body>

</html>
