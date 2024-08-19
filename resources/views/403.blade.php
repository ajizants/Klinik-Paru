@extends('Template.lte')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid" style="height: 70vh">
        <div class="text-center mt-5">
            <h1 class="display-4">403 Forbidden</h1>
            <p class="lead">Anda tidak memiliki hak mengakses laman tersebut!</p>
            <a href="{{ route('home') }}" class="btn btn-primary">Kembali ke Beranda</a>
        </div>
    </div>
    <!-- /.container-fluid -->

    @include('Template.footer')


    <!-- jQuery -->
    <script src="{{ asset('vendor/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('vendor/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('vendor/dist/js/adminlte.min.js') }}"></script>

    <script>
        var tglTransInput = document.getElementById("waktu");
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
