{{-- @extends('layouts.layout') --}}
@extends('Template.lte')

@section('content')
    <canvas id="confetti"></canvas>

    @include('IGD.Trans.antrian')
    @include('IGD.Trans.input')

    <audio id="morning-audio" src="{{ asset('audio/IndonesiaRaya.mp3') }}" preload="auto"></audio>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const audio = document.getElementById("morning-audio");
            audio.volume = 0.5; // Atur volume ke 50%

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
    <!-- my script -->
    <script src="{{ asset('js/template.js') }}"></script>
    <script src="{{ asset('js/antrianIGD.js') }}"></script>
    <script src="{{ asset('js/populate.js') }}"></script>
    <script src="{{ asset('js/mainIGD.js') }}"></script>
    <script>
        socketIO.on("reload", (msg) => {
            if (msg == "paru_ruang_poli") {
                antrian("igd");
            }
            if (msg == "paru_notifikasi_ruang_tensi_1") {
                const notif = new Audio("/audio/dingdong.mp3");
                notif.load();
                notif.play();
                antrianAll("igd");
            }
        });
    </script>
@endsection
