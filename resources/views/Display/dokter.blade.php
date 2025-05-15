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
        <h1 class="font-weight-bold text-center py-3" style="font-size: 5rem">Jumlah Antrian Tiap Dokter</h1>
    </header>
    <div class="container-fluid">
        <div class="form-row">
            <div class="card card-primary col-6">
                <div class="card-header d-flex justify-content-center"
                    style="height: 130px !important;align-items: center;">
                    <h1 class="card-title text-center font-weight-bold" style="font-size: 3rem !important;">dr. Agil
                        Dananjaya Sp.P
                    </h1>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center" style="font-size: 2rem;">Tunggu</th>
                                <th class="text-center" style="font-size: 2rem;">Selesai</th>
                                <th class="text-center" style="font-size: 2rem;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <h1 class="text-center" style="font-size: 9rem " id="tungguAgil">
                                        {{ $data['listTungguAgil'] }}</h1>
                                </td>
                                <td>
                                    <h1 class="text-center" style="font-size: 9rem " id="selesaiAgil">
                                        {{ $data['listSelesaiAgil'] }}</h1>
                                </td>
                                <td>
                                    <h1 class="text-center" style="font-size: 9rem " id="totalAgil">
                                        {{ $data['listAgil'] }}</h1>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card card-primary col-6">
                <div class="card-header d-flex justify-content-center"
                    style="height: 130px !important;align-items: center;">
                    <h1 class="card-title text-center font-weight-bold" style="font-size: 3rem !important;">dr. Cempaka
                        N.I., Sp.P, FISR., MM.
                    </h1>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center" style="font-size: 2rem;">Tunggu</th>
                                <th class="text-center" style="font-size: 2rem;">Selesai</th>
                                <th class="text-center" style="font-size: 2rem;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <h1 class="text-center" style="font-size: 9rem " id="tungguNova">
                                        {{ $data['listTungguNova'] }}</h1>
                                </td>
                                <td>
                                    <h1 class="text-center" style="font-size: 9rem " id="selesaiNova">
                                        {{ $data['listSelesaiNova'] }}</h1>
                                </td>
                                <td>
                                    <h1 class="text-center" style="font-size: 9rem " id="totalNova">
                                        {{ $data['listNova'] }}</h1>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card card-primary col-6">
                <div class="card-header d-flex justify-content-center"
                    style="height: 130px !important;align-items: center;">
                    <h1 class="card-title text-center font-weight-bold" style="font-size: 3rem !important;">dr. Sigit
                        Dwiyanto
                    </h1>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center" style="font-size: 2rem;">Tunggu</th>
                                <th class="text-center" style="font-size: 2rem;">Selesai</th>
                                <th class="text-center" style="font-size: 2rem;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <h1 class="text-center" style="font-size: 9rem " id="tungguSigit">
                                        {{ $data['listTungguSigit'] }}</h1>
                                </td>
                                <td>
                                    <h1 class="text-center" style="font-size: 9rem " id="selesaiSigit">
                                        {{ $data['listSelesaiSigit'] }}</h1>
                                </td>
                                <td>
                                    <h1 class="text-center" style="font-size: 9rem " id="totalSigit">
                                        {{ $data['listSigit'] }}</h1>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card card-primary col-6">
                <div class="card-header d-flex justify-content-center"
                    style="height: 130px !important;align-items: center;">
                    <h1 class="card-title text-center font-weight-bold" style="font-size: 3rem !important;">dr. Filly
                        Ulfa Kusumawardani
                    </h1>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center" style="font-size: 2rem;">Tunggu</th>
                                <th class="text-center" style="font-size: 2rem;">Selesai</th>
                                <th class="text-center" style="font-size: 2rem;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <h1 class="text-center" style="font-size: 9rem " id="tungguFilly">
                                        {{ $data['listTungguFilly'] }}</h1>
                                </td>
                                <td>
                                    <h1 class="text-center" style="font-size: 9rem " id="selesaiFilly">
                                        {{ $data['listSelesaiFilly'] }}</h1>
                                </td>
                                <td>
                                    <h1 class="text-center" style="font-size: 9rem " id="totalFilly">
                                        {{ $data['listFilly'] }}</h1>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('Display.footer')

    <script type="text/javascript">
        async function getList() {
            const norm = "";
            try {
                const response = await fetch("/api/list/tunggu/dokter", {
                    method: "GET",
                    headers: {
                        "Content-Type": "application/json",
                    },
                });
                const data = await response.json();

                drawNotif(data);
            } catch (error) {
                console.error("Terjadi kesalahan saat mencari data:", error);
            }
        }

        function drawNotif(data) {
            console.log("🚀 ~ data:", data);

            const tungguAgil = document.getElementById("tungguAgil");
            const selesaiAgil = document.getElementById("selesaiAgil");
            const totalAgil = document.getElementById("totalAgil");

            const tungguNova = document.getElementById("tungguNova");
            const selesaiNova = document.getElementById("selesaiNova");
            const totalNova = document.getElementById("totalNova");

            const tungguSigit = document.getElementById("tungguSigit");
            const selesaiSigit = document.getElementById("selesaiSigit");
            const totalSigit = document.getElementById("totalSigit");

            const tungguFilly = document.getElementById("tungguFilly");
            const selesaiFilly = document.getElementById("selesaiFilly");
            const totalFilly = document.getElementById("totalFilly");

            tungguAgil.textContent = data.listTungguAgil;
            selesaiAgil.textContent = data.listSelesaiAgil;
            totalAgil.textContent = data.listAgil;

            tungguNova.textContent = data.listTungguNova;
            selesaiNova.textContent = data.listSelesaiNova;
            totalNova.textContent = data.listNova;

            tungguSigit.textContent = data.listTungguSigit;
            selesaiSigit.textContent = data.listSelesaiSigit;
            totalSigit.textContent = data.listSigit;

            tungguFilly.textContent = data.listTungguFilly;
            selesaiFilly.textContent = data.listSelesaiFilly;
            totalFilly.textContent = data.listFilly;

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
