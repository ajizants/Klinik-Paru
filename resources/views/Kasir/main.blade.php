@extends('Template.lte')

@section('content')
    {{-- @include('Kasir.antrian')
    @include('Kasir.input') --}}



    @include('Template.comingSoon')

    @include('Template.script')

    <!-- my script -->

    <!-- my script -->
    {{-- <script src="{{ asset('js/template.js') }}"></script> --}}
    {{-- <script src="{{ asset('js/populate.js') }}"></script> --}}
    {{-- <script src="{{ asset('js/antrianKasir.js') }}"></script> --}}
    {{-- <script src="{{ asset('js/mainKasir.js') }}"></script> --}}
    <script>
        var tglTransInput = document.getElementById("waktu");
        let tglnow = "";
        document.addEventListener("DOMContentLoaded", function() {
            function updateDateTime() {
                var now = new Date();
                var options = {
                    timeZone: "Asia/Jakarta",
                    year: "numeric",
                    month: "2-digit",
                    day: "2-digit",
                    hour: "2-digit",
                    minute: "2-digit",
                    second: "2-digit",
                };
                // var formattedDate = now.toLocaleString("id-ID", options);
                let tglnow = now
                    .toLocaleString("id-ID", options)
                    .replace(
                        /(\d{4})\D(\d{2})\D(\d{2})\D(\d{2})\D(\d{2})\D(\d{2})/,
                        "$1-$2-$3 $4.$5.$6"
                    );

                tglTransInput.value = tglnow;
            }
            setInterval(updateDateTime, 1000);
        });
    </script>
@endsection
