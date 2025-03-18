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
    <header class="container-fluid fixed-top bg-primary">
        <h1 class="font-weight-bold text-center" style="font-size: 3rem">LOKET PENDAFTARAN</h1>
        <div class="row mb-1">
            <div class="col text-center font-weight-bold" style="font-size:2.5rem;">SEDANG DIPANGGIL</div>
            <div class="col text-center font-weight-bold" style="font-size:2.5rem;">DAFTAR TUNGGU</div>
        </div>
    </header>
    <aside class="bg-white main-sidebar" style="width: 20px;z-index: 2 !important;height: 5000px;"></aside>
    <div class="container-fluid row px-2 mx-2">
        <div class="col">
            <iframe class="custom-iframe" scrolling="no" style="margin-top: -34px; margin-left: -10px;"
                src="https://kkpm.banyumaskab.go.id/administrator/display_tv/loket_pendaftaran"></iframe>
        </div>
        <div class="col mt-1">
            <h1 class="text-center font-weight-bold mt-5">Daftar Tunggu</h1>
            <div class="table-responsive mt-5">
                <table class="table table-bordered table-striped table-hover mb-0" id="header" style="width:100%">
                    <thead class="bg bg-dark" style="font-size: 2rem;">
                        <tr>
                            <th class="col-2">Antrean</th>
                            <th class="col-2">Jaminan</th>
                            <th class="col-3">Keterangan</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="table-responsive table-container" style=" font-size: 2rem;">
                @php
                    $scrol = isset($listTunggu) && count($listTunggu) >= 7 ? 'table-auto' : '';
                @endphp

                <table class="table table-bordered table-striped table-hover {{ $scrol }}" id="listTunggu"
                    style="width:100%;">
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
                                @else
                                    @php
                                        $bg = 'bg-success';
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
            <div class="bg-primary text-center">
                <h2 class="text-center mt-2 mb-0">JADWAL PRAKTIK DOKTER</h2>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover mb-0" id="header" style="width:100%">
                    <thead class="bg bg-dark">
                        <tr>
                            <th class="col-1">No</th>
                            <th class="col-2">Hari</th>
                            <th>Waktu</th>
                            <th class="col-5">Dokter</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="table-responsive" style="height: 25vh; overflow-y: hidden; font-size: 1.5rem">
                <table class="table-auto table table-bordered table-striped table-hover" id="listJadwal"
                    style="width:100%">
                    <tbody id="listJadwal">
                        @foreach ($jadwal as $item)
                            <td class="col-1">{{ $loop->iteration }}</td>
                            <td class="col-2">{{ $item['nama_hari'] }}</td>
                            <td>
                                <!-- Convert and display waktu_mulai_poli and waktu_selesai_poli -->
                                {{ \Carbon\Carbon::createFromTimestamp($item['waktu_mulai_poli'])->format('H:i') }} -
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
                const response = await fetch("/api/list/tunggu/loket", {
                    method: "GET",
                    headers: {
                        "Content-Type": "application/json",
                    },
                });
                const data = await response.json();
                // console.log("🚀 ~ getList ~ data:", data)

                drawTable(data);
            } catch (error) {
                console.error("Terjadi kesalahan saat mencari data:", error);
            }
        }

        function drawTable(data) {
            if (data.length > 0) {
                const tableBody = document.querySelector("#listTunggu tbody");
                tableBody.innerHTML = ""; // Bersihkan konten sebelumnya

                data.forEach(item => {
                    if (item.keterangan === 'SKIP') {
                        bg = "bg-warning";
                    } else {
                        bg = "bg-success";
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

        setInterval(() => {
            getList();
        }, 20000);

        function reload_table() {

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
                reload_table();
            }
        });
    </script>
</body>

</html>
