{{-- @extends('layouts.layout') --}}
@extends('tamplate.lte')

@section('content')
    @include('DotsCenter.antrian')
    @include('DotsCenter.input')




    </div>
    </section>
    <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    @include('Tamplate.footer')

    </div>
    @include('DotsCenter.modals')
    @include('Tamplate.script')

    <!-- my script -->
    <script>
        function searchByRM(norm) {
            $.ajax({
                url: "/api/cariRM",
                type: "post",
                data: {
                    norm: norm,
                },
                success: function(response) {
                    // Mendapatkan data dari respons JSON
                    var noRM = response[0].norm; // Menggunakan indeks 0 karena respons adalah array
                    var nama = response[0].biodata.nama;
                    var notrans = response[0].notrans;
                    var layanan = response[0].kelompok.kelompok;
                    var dokter = response[0].petugas.p_dokter_poli;
                    var alamat =
                        `${response[0].biodata.kelurahan}, ${response[0].biodata.rtrw}, ${response[0].biodata.kecamatan}, ${response[0].biodata.kabupaten}`;
                    // Dapatkan data lainnya dari respons JSON sesuai kebutuhan

                    // Mengisikan data ke dalam elemen-elemen HTML
                    $("#norm").val(noRM);
                    $("#nama").val(nama);
                    $("#alamat").val(alamat);
                    $("#notrans").val(notrans);
                    $("#layanan").val(layanan);
                    $("#dokter").val(dokter);
                    $("#dokter").trigger("change");
                    // Mengisi elemen-elemen lainnya sesuai kebutuhan
                },
                error: function(xhr) {
                    // Handle error
                },
            });
        }
    </script>
    <script src="{{ asset('js/alert.js') }}"></script>
    <script src="{{ asset('js/antrianDots.js') }}"></script>
    <script src="{{ asset('js/populate.js') }}"></script>
    <script src="{{ asset('js/mainDots.js') }}"></script>
@endsection
