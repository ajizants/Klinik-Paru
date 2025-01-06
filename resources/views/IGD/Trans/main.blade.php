{{-- @extends('layouts.layout') --}}
@extends('Template.lte')

@section('content')
    <canvas id="confetti"></canvas>

    @include('IGD.Trans.antrian')
    @include('IGD.Trans.input')

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
            if (msg == "paru_notifikasi_ruang_poli") {
                const notif = new Audio("/audio/dingdong.mp3");
                notif.load();
                notif.play();
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
