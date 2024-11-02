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
            height: 47.4vh;
            /* Set max height untuk auto scroll */
            overflow: hidden;
            /* Sembunyikan scroll bar */
            position: relative;
            /* Untuk posisi absolut */
        }

        .table-container3 {
            height: 25vh;
            /* Set max height untuk auto scroll */
            overflow: hidden;
            /* Sembunyikan scroll bar */
            position: relative;
            /* Untuk posisi absolut */
        }

        .table-container2 {
            max-height: 353px;
            /* Set max height untuk auto scroll */
            overflow: hidden;
            /* Sembunyikan scroll bar */
            position: relative;
            /* Untuk posisi absolut */
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
    <div class="container-fluid row">
        <div class="col mt-2">
            <div>
                <h2 class="text-center">Daftar Tunggu Farmasi</h2>
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
                            <tbody style="font-size: 20px">
                                <tr>
                                    <td colspan="3" class="text-center">Tidak ada antrian</td>
                                </tr>
                            </tbody>
                        </table>
                    @else
                        <table class="table-auto table table-bordered table-striped table-hover" id="listMenunggu"
                            style="width:100%">
                            <tbody style="font-size: 20px">
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
            <div>
                <h2 class="text-center">Daftar Selesai Farmasi</h2>
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
        </div>
        <div class="col mt-2">
            <div class="col" id="player"></div>
            <h2 class="text-center">Jadwal Praktek Dokter</h2>
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
            <div class="table-responsive table-container2">
                <table class="table-auto table table-bordered table-striped table-hover" id="listTunggu"
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
        <marquee class="marquee my-3" style="font-size: 45px !important; color: #ffffff">
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
    <!-- jQuery -->
    <script src="{{ asset('vendor/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('vendor/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        // Load YouTube IFrame API secara asinkron
        var tag = document.createElement('script');
        tag.src = "https://www.youtube.com/iframe_api";
        var firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

        // Fungsi ini dipanggil setelah API YouTube dimuat
        function onYouTubeIframeAPIReady() {
            // Inisialisasi pemutar
            var player = new YT.Player('player', {
                height: '550', // tinggi iframe
                // width: '640', // lebar iframe
                playerVars: {
                    listType: 'playlist',
                    list: 'PLG70n9hvc5bRr5HFJ0mJ4FZZjymJhcrkt', // Ganti dengan ID playlist Anda
                    autoplay: 1, // Mengaktifkan autoplay
                    controls: 1, // Menampilkan kontrol pemutar
                    loop: 1, // Mengulang playlist
                    rel: 0, // Tidak menampilkan video terkait setelah selesai
                    mute: 1
                },
                events: {
                    'onReady': onPlayerReady
                }
            });
        }

        // Fungsi ini dipanggil saat player siap
        function onPlayerReady(event) {
            event.target.mute(); // Mematikan suara
            event.target.playVideo(); // Memulai video secara otomatis
        }
    </script>
</body>

</html>
