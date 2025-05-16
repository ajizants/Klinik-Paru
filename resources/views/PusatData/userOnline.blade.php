@extends('Template.lte')

@section('content')
    <div class="container-fluid">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a type="button" class="nav-link active bg-blue" onclick="toggleSections('#tab_1');"><b>Biaya</b></a>
            </li>
        </ul>
    </div>
    <div class="container-fluid mt-1" id="tab_1">
        {!! $data !!}
    </div>


    <!-- my script -->
    <script src="{{ asset('js/template.js') }}"></script>
    <script>
        function cariLogUser() {
            var url = "/api/userOnline";
            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    $("#tab_1").html(data);
                    dataTable();
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: "error",
                        title: "Gagal Memuat Data",
                        text: "Terjadi kesalahan saat mengambil data: " + error,
                    });
                }

            });
        }

        function dataTable() {
            $("#tableUserOnline").DataTable({
                responsive: true,
                lengthChange: false,
                autoWidth: true,
                searching: true,
                paging: true,
                // ordering: false,
                order: [
                    [1, "asc"]
                ],
                info: true,
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Tidak ada data tersedia",
                    zeroRecords: "Tidak ada data yang cocok",
                    paginate: {
                        first: "Awal",
                        last: "Akhir",
                        next: "→",
                        previous: "←"
                    }
                },
                buttons: [{
                    extend: "copyHtml5",
                    text: "Salin",
                }, {
                    extend: "excel", // Tombol ekspor ke Excel
                    text: "Download",
                    title: "Data User Login",
                    filename: "Data User Login",
                    exportOptions: {
                        columns: ":visible",
                    },
                }]
            });
        }

        //inisiasi data tabele tableUserOnline
        document.addEventListener("DOMContentLoaded", function() {
            dataTable();
        })
    </script>
@endsection
