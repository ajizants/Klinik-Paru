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
    <link type="text/css" rel="stylesheet" href="{{ asset('css/mystyle.css') }}">
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
        <div class="col">
            <iframe class="custom-iframe" scrolling="no"
                src="https://kkpm.banyumaskab.go.id/administrator/display_tv/loket_pendaftaran"></iframe>
        </div>
        <div class="col mt-5">
            <div class="col" id="player"></div>
        </div>
    </div>
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
                    // list: 'PLZiU6eESB4-IosoWmlWEl5K-jhX_5xNuA', // Ganti dengan ID playlist Anda
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
            vent.target.mute(); // Mematikan suara
            event.target.playVideo(); // Memulai video secara otomatis
        }
    </script>
</body>

</html>
