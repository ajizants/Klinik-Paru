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
    <div class="container-fluid row">
        <div class="col mt-2">
            <iframe class="custom-iframe" scrolling="no"
                src="https://kkpm.banyumaskab.go.id/administrator/display_tv/ruang_tensi"></iframe>
        </div>
        <div class="col mt-2">
            {{-- <div class="col p-0" id="player"></div> --}}
            <h2 class="text-center">Daftar Tunggu Tensi</h2>
            <div class="table-responsive table-container">
                <table class="table table-bordered table-striped table-hover" id="header" style="width:100%">
                    <thead class="bg bg-dark">
                        <tr>
                            <th class="col-2">No RM</th>
                            <th class="col-3">Nama Pasien</th>
                            <th>Nama Dokter</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="table-responsive table-container">
                @if (empty($listTunggu))
                    <table class="table table-bordered table-striped table-hover" id="listTunggu" style="width:100%">
                        <tbody style="font-size: 20px">
                            <tr>
                                <td colspan="3" class="text-center">Tidak ada antrian</td>
                            </tr>
                        </tbody>
                    </table>
                @else
                    <table class="table-auto table table-bordered table-striped table-hover" id="listTunggu"
                        style="width:100%">
                        <tbody style="font-size: 20px">
                            @foreach ($listTunggu as $item)
                                <tr>
                                    <td class="col-2">{{ $item['pasien_no_rm'] }}</td>
                                    <td class="col-3">{{ $item['pasien_nama'] }}</td>
                                    <td>{{ $item['dokter_nama'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
    <!-- jQuery -->
    <script src="{{ asset('vendor/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('vendor/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        var tungguTensi = @json($listTunggu);
        console.log("🚀 ~ tungguTensi:", tungguTensi)

        // // Load YouTube IFrame API secara asinkron
        // var tag = document.createElement('script');
        // tag.src = "https://www.youtube.com/iframe_api";
        // var firstScriptTag = document.getElementsByTagName('script')[0];
        // firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

        // // Fungsi ini dipanggil setelah API YouTube dimuat
        // function onYouTubeIframeAPIReady() {
        //     // Inisialisasi pemutar
        //     var player = new YT.Player('player', {
        //         height: '500', // tinggi iframe
        //         // width: '640', // lebar iframe
        //         playerVars: {
        //             listType: 'playlist',
        //             list: 'PLG70n9hvc5bRr5HFJ0mJ4FZZjymJhcrkt',
        //             autoplay: 1,
        //             controls: 1,
        //             loop: 1,
        //             rel: 0,
        //             mute: 1
        //         },
        //         events: {
        //             'onReady': onPlayerReady
        //         }
        //     });
        // }

        // // Fungsi ini dipanggil saat player siap
        // function onPlayerReady(event) {
        //     event.target.mute(); // Mematikan suara
        //     event.target.playVideo(); // Memulai video secara otomatis

        // }
    </script>
    <script type="text/javascript">
        async function getList() {
            const tableBody = document.querySelector("table tbody");
            tableBody.innerHTML = "";
            const norm = "";
            try {
                const response = await fetch("/api/list/tunggu/tensi", {
                    method: "POST",
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
            console.log("🚀 ~ drawTable ~ data:", data)
            if (data.length > 0) {


                const tableBody = document.querySelector("table tbody");
                tableBody.innerHTML = ""; // Bersihkan konten sebelumnya

                data.forEach(item => {
                    const row = document.createElement("tr");

                    const noRmCell = document.createElement("td");
                    noRmCell.textContent = item.pasien_no_rm;
                    row.appendChild(noRmCell);
                    //tambahkan class col-2
                    noRmCell.classList.add("col-2");

                    const namaCell = document.createElement("td");
                    namaCell.textContent = item.pasien_nama;
                    row.appendChild(namaCell);
                    namaCell.classList.add("col-3");

                    const alamatCell = document.createElement("td");
                    alamatCell.textContent = item.dokter_nama;
                    row.appendChild(alamatCell);

                    tableBody.appendChild(row);
                });

                // Memastikan animasi berjalan
                document.querySelector(".table-auto").style.animation = 'scroll 20s linear infinite';
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
            }
        }

        setInterval(() => {
            // Panggil fungsi untuk menggambar tabel
            getList();
        }, 20000);
    </script>
</body>

</html>
