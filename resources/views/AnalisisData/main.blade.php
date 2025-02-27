@extends('Template.lte')

@section('content')
    @include('AnalisisData.input')

    <!-- my script -->
    <script src="{{ asset('js/template.js') }}"></script>
    {{-- <script src="{{ asset('js/populate.js') }}"></script> --}}
    <script>
        var tglAwal;
        var tglAkhir;

        function cariDataKunjungan(tglAwal, tglAkhir) {
            Swal.fire({
                title: 'Memuat Data, Mohon Tunggu...',
                showConfirmButton: false,
                allowEscapeKey: false,
                allowOutsideClick: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });

            // Cek jika DataTable sudah ada, hancurkan dengan clear untuk menghindari duplikasi data
            if ($.fn.DataTable.isDataTable("#kunjunganTable")) {
                $("#kunjunganTable").DataTable().clear().destroy();
            }

            $.ajax({
                url: "/api/data/analis/biaya_pasien",
                method: "POST",
                data: {
                    tanggal_awal: tglAwal,
                    tanggal_akhir: tglAkhir,
                },
                dataType: "json",
                success: function(response) {
                    $("#dataKunjungan").html(response.html); // Isi tabel dengan hasil response

                    // Inisialisasi ulang DataTables setelah data dimuat
                    var table = $("#kunjunganTable").DataTable({
                        responsive: true,
                        lengthChange: false,
                        autoWidth: false,
                        searching: true,
                        paging: true,
                        ordering: true,
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
                            extend: "excel", // Tombol ekspor ke Excel
                            text: "Download",
                            title: "Data Pasien Baru & Kunjungan Ulang " + tglAwal + " s.d. " +
                                tglAkhir,
                            filename: "Data_Analisis_Biaya_Pasien_" + tglAwal + "_" +
                                tglAkhir,
                            exportOptions: {
                                columns: ":visible",
                            },
                        }]
                    });

                    // Menambahkan tombol ekspor ke dalam wrapper DataTables
                    table.buttons().container().appendTo("#kunjunganTable_wrapper .col-md-6:eq(0)");

                    Swal.close();
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



        window.addEventListener("load", function() {
            setTodayDate();
            var today = new Date().toISOString().split("T")[0];
            $("#tanggal").val(today);

            // Inisialisasi tglAwal dan tglAkhir sebagai objek Moment.js
            // tglAwal = moment().subtract(30, "days").format("YYYY-MM-DD");
            tglAwal = moment().subtract(0, "days").format("YYYY-MM-DD");
            tglAkhir = moment().subtract(0, "days").format("YYYY-MM-DD");

            // Menetapkan nilai ke input tanggal
            $("#reservation").val(tglAwal + " to " + tglAkhir);

            // Date range picker
            $("#reservation").daterangepicker({
                startDate: tglAwal,
                endDate: tglAkhir,
                autoApply: false,
                // showDropdowns: true,
                locale: {
                    format: "YYYY-MM-DD",
                    separator: " s.d. ",
                    applyLabel: "Cari",
                    cancelLabel: "Batal",
                    customRangeLabel: "Custom Range",
                },
            });

            $("#reservation").on(
                "apply.daterangepicker",
                function(ev, picker) {
                    tglAwal = picker.startDate.format("YYYY-MM-DD");
                    tglAkhir = picker.endDate.format("YYYY-MM-DD");
                    cariDataKunjungan(tglAwal, tglAkhir);
                }
            );
        });
    </script>
@endsection
