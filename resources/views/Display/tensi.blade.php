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

    <script type="text/javascript">
        // 1 detik = 1000
        window.setTimeout("waktu()", 1000);

        function waktu() {
            var tanggal = new Date();
            setTimeout("waktu()", 1000);
            var detik = tanggal.getSeconds();
            var menit = tanggal.getMinutes();
            var jam = tanggal.getHours();
            if (detik < 10) {
                detik = "0" + detik;
            }
            if (menit < 10) {
                menit = "0" + menit;
            }
            if (jam < 10) {
                jam = "0" + jam;
            }
            document.getElementById("tanggalku").innerHTML

            = jam + ":" + menit + ":" + detik;
        }

        $(document).ready(function() {
            reload_table()
        })

        var tableData = $("#table_data").DataTable({
            "lengthChange": false,
            "info": false,
            "dom": "lfrti",
            "pagingType": "full_numbers",
            "language": {
                "infoEmpty": "Tidak ada data!",
                "emptyTable": "Tidak ada data!",
                "loadingRecords": "Memuat data...", // Teks yang ditampilkan saat data sedang dimuat
                "processing": "Memproses...",
            },
            "processing": true,
            "serverSide": true,
            "ordering": false,
            "searching": false,
            "columnDefs": [],
            "ajax": {
                "url": "https://kkpm.banyumaskab.go.id/administrator/display_tv/ruang_tensi_get_data",
                "type": "POST",
                "error": function(xhr, status, error) {
                    iziToast.warning({
                        title: "'" + xhr.responseText + "'",
                        position: 'topLeft',
                        timeout: 100, //TODO : coba cek segini dulu
                    });
                },

            },
            "createdRow": function(row, data, dataIndex) {
                console.log(data);
                if (data['waktu_submit'] == null) {
                    $(row).addClass('bg-info');
                }
            },
            "columns": [{
                    data: "pasien_nama",
                    className: "text-center",
                },
                {
                    data: "kelurahan",
                    className: "text-center",
                },
                {
                    data: "menuju_ke",
                    className: "text-center",
                },
                {
                    data: "created_at",
                    className: "text-center",
                },
            ]
        })

        function reload_table() {
            tableData.ajax.reload(null, false);
            //  $("#div_ulangi_panggilan").html( /*html*/ ``)
            $.ajax({
                type: "POST",
                url: "https://kkpm.banyumaskab.go.id/administrator/display_tv/ruang_tensi_get_data",
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
                    antrean_sedang_dipanggil = data[0]['pasien_nama']
                    antrean_sedang_dipanggil_kelurahan = data[0]['kelurahan']
                    antrean_sedang_dipanggil_menuju_ke = data[0]['menuju_ke']
                    if (antrean_sedang_dipanggil != null) {
                        $("#antrean_sedang_dipanggil").html( /*html*/ `${antrean_sedang_dipanggil}`)
                        $("#antrean_sedang_dipanggil_kelurahan").html( /*html*/
                            `${antrean_sedang_dipanggil_kelurahan}`)
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

        // var socketIO = io.connect('wss://kkpm.banyumaskab.go.id:3131/', {
        //     // path: '/socket.io',
        //     transports: ['websocket',
        //         'polling',
        //         'flashsocket'
        //     ]
        // });

        socketIO.on('connectParu', () => {
            const sessionID = socketIO.id
            $('#socket-id').html(sessionID)
            console.log("Socket ID : " + sessionID)
        });

        socketIO.on('reload', (msg) => {
            if (msg == 'paru_ruang_tensi') {
                reload_table()
            }
        });
    </script>
</head>

<body>
    <header class="container-fluid fixed-top bg-primary">
        <h1 class="font-weight-bold text-center" style="font-size: 3rem">RUANG TENSI</h1>
        <div class="row mb-1">
            <div class="col text-center font-weight-bold" style="font-size:2.5rem;">SEDANG DIPANGGIL</div>
            <div class="col text-center font-weight-bold" style="font-size:2.5rem;">DAFTAR TUNGGU</div>
        </div>
    </header>
    <div class="container-fluid row mt-2">
        <div class="col">
            <iframe class="custom-iframe" scrolling="no"
                src="https://kkpm.banyumaskab.go.id/administrator/display_tv/ruang_tensi"></iframe>
        </div>
        <aside class="bg-white main-sidebar" style="width: 20px;z-index: 2 !important;height: 5000px;"></aside>
        <div class="col" style="margin-top: 130px; font-size: 1.5rem">
            <div class="table-responsive">
                <table class="mb-0 table table-bordered table-striped table-hover" id="header" style="width:100%">
                    <thead class="bg bg-dark">
                        <tr>
                            <th class="col-2">No RM</th>
                            <th class="col-4">Nama Pasien</th>
                            <th>Nama Dokter</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="table-responsive table-container">
                @php
                    $scrol = isset($listTunggu) && count($listTunggu) >= 7 ? 'table-auto' : '';
                @endphp
                @if (empty($listTunggu))
                    <table class="table table-bordered table-striped table-hover" id="listTunggu" style="width:100%">
                        <tbody>
                            <tr>
                                <td colspan="3" class="text-center">Tidak ada antrian</td>
                            </tr>
                        </tbody>
                    </table>
                @else
                    <table class="{{ $scrol }} table table-bordered table-striped table-hover" id="listTunggu"
                        style="width:100%">
                        <tbody>
                            @foreach ($listTunggu as $item)
                                <tr>
                                    <td class="col-2">{{ $item['pasien_no_rm'] }}</td>
                                    <td class="col-4">{{ $item['pasien_nama'] }}</td>
                                    <td>{{ $item['dokter_nama'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
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
                    namaCell.classList.add("col-4");

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
