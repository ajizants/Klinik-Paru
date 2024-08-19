@extends('Template.lte')


@section('content')
    @include('Askep.input')





    </div>
    </section>
    <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    @include('Template.footer')

    </div>



    <!-- my script -->
    <script src="{{ asset('js/template.js') }}"></script>
    <script src="{{ asset('js/populate.js') }}"></script>
    <script src="{{ asset('js/mainIGD.js') }}"></script>
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
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var tgltindInput = document.getElementById("tgltind");

            function updateDateTime() {
                var now = new Date();
                var options = {
                    timeZone: "Asia/Jakarta"
                };
                var formattedDate = now.toLocaleString("id-ID", options);
                tgltindInput.value = formattedDate;
            }

            setInterval(updateDateTime, 1000);
        });

        function setTodayDate() {
            var today = new Date().toISOString().split("T")[0];
            $("#tanggal").val(today);
        }

        function scrollToInputSection() {
            $("html, body").animate({
                    scrollTop: $("#inputSection").offset().top
                },
                500
            );
        }

        $(document).ready(function() {
            var Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
            });

            $(".select2bs4").select2({
                theme: "bootstrap4"
            });

            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"), // Mengirim token CSRF untuk perlindungan keamanan
                },
            });

            setTodayDate();
            populateDokterOptions();
            populatePetugasOptions();

            $("#tblBatal").on("click", function(e) {
                $(
                    "#norm, #nama, #alamat, #layanan, #notrans, #dokter, #petugas").val("");
                $("#dokter, #petugas").trigger("change ");


            });

            $("#tblSimpan").on("click", function(e) {
                e.preventDefault();
                var norm = $("#norm").val();
                var notrans = $("#notrans").val();
                // Memeriksa apakah ada nilai yang kosong
                if (!norm || !notrans) {
                    // Menampilkan notifikasi jika ada nilai yang kosong
                    var dataKurang = [];
                    if (!norm || !notrans) dataKurang.push("Belum Ada Data Transaksi");

                    Toast.fire({
                        icon: "error",
                        title: "Data Tidak Lengkap...!!! " + dataKurang.join(", "),
                    });
                    scrollToAntrianSection();
                } else {
                    $(
                        "#norm, #nama, #alamat, #layanan, #notrans, #dokter, #apoteker, #obat, #qty"
                    ).val("");
                    $("#dokter, #apoteker, #obat").trigger("change");

                    Toast.fire({
                        icon: "success",
                        title: "Transaksi Berhasil Disimpan, Maturnuwun...!!!",
                    });
                }
            });

            $("#norm").on("keyup", function(event) {
                if (event.key === "Enter") {
                    event.preventDefault();
                    searchByRM($("#norm").val());
                }
            });

        });

        $(document).on("select2:open", () => {
            document.querySelector(".select2-search__field").focus();
        });
    </script>
@endsection
