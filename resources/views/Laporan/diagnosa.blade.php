@extends('Template.lte')

@section('content')
    {{-- Data per pasien --}}
    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-start">
            <h6 class="m-0 font-weight-bold text-primary">Rekap Diagnosa Kunjungan</h6>
        </div>
        <div class="card-body mb-2">
            <div class="row">
                <label class="col-form-label">Rentang Tanggal :</label>
                <div class="form-group col-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="far fa-calendar-alt"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control float-right" id="reservation">
                    </div>
                </div>
                <div class="col-3">
                    <button type="button" class="btn btn-success" onclick="segarkan();">
                        Segarkan
                        <span class="fa-solid fa-rotate ml-1" data-toggle="tooltip" data-placement="top" title="Update Data"
                            id="cariantrian"></span>
                    </button>
                </div>

            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover dataTable dtr-inline" id="report" cellspacing="0">
                    <thead class="bg bg-teal table-bordered border-warning">
                        <tr>
                            <th>Urut</th>
                            <th>Tanggal</th>
                            <th>penjamin</th>
                            <th>No. RM</th>
                            <th class="col-2">Nama Pasien</th>
                            <th>Desa</th>
                            <th>RT/RW</th>
                            <th>Kecamatan</th>
                            <th>Kabupaten</th>
                            <th>ICD X 1</th>
                            <th>Diagnosa 1</th>
                            <th>ICD X 2</th>
                            <th>Diagnosa 2</th>
                            <th>ICD X 3</th>
                            <th>Diagnosa 3</th>
                            <th class="col-3">Dokter</th>
                            <th class="px-0 col-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-bordered border-warning">
                    </tbody>
                </table>
            </div>

        </div>
    </div>


    @include('Template.script')


    <!-- my script -->
    <script src="{{ asset('js/template.js') }}"></script>
    <script>
        function segarkan() {
            Swal.fire({
                icon: "info",
                title: "Sedang mencarikan data...!!!\n Proses lama jika mencari lebih dari 10 hari",
                showConfirmButton: true,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                },
            });
            reportPasien(tglAwal, tglAkhir);
        }

        function cariJumlah() {
            Swal.fire({
                icon: "info",
                title: "Sedang mencarikan data...!!!\n Proses lama jika mencari lebih dari 10 hari",
                showConfirmButton: true,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                },
            });
            reportJumlah(tglAwal, tglAkhir);
        }

        function reportPasien(tglAwal, tglAkhir) {
            var tglA = formatDate(new Date(tglAwal));
            var tglB = formatDate(new Date(tglAkhir));

            var diagnosa = $("#diagnosa").val() || "";

            if ($.fn.DataTable.isDataTable("#report, #total")) {
                var tabletindakan = $("#report, #total").DataTable();
                tabletindakan.destroy();
            }

            $.ajax({
                url: "/api/riwayatKunjungan",
                type: "post",
                data: {
                    tanggal_awal: tglAwal,
                    tanggal_akhir: tglAkhir,
                    diagnosa: diagnosa,
                },
                success: function(response) {
                    var dataPasien = response.response.data;
                    dataPasien.forEach(function(item, index) {
                        // Menambahkan tombol aksi
                        item.aksi =
                            `<button type="button" class="btn btn-primary mr-2"
                                    onclick="cetak('${item.pasien_no_rm}')" placeholder="Cetak">
                                    <i class="fa-solid fa-print"></i>
                            </button>`;

                        item.rtrw = item.pasien_rt + "/" + item.pasien_rw;
                    });


                    $("#report").DataTable({
                        data: dataPasien,
                        columns: [{
                                data: "antrean_nomor"
                            },
                            {
                                data: "tanggal"
                            },
                            {
                                data: "penjamin_nama"
                            },
                            {
                                data: "pasien_no_rm"
                            },
                            {
                                data: "pasien_nama",
                                className: "col-2"
                            },
                            {
                                data: "kelurahan_nama"
                            },
                            {
                                data: "rtrw"
                            },
                            {
                                data: "kecamatan_nama"
                            },
                            {
                                data: "kabupaten_nama"
                            },
                            {
                                data: "diagnosa",
                                render: function(data, type, row) {
                                    if (Array.isArray(data) && data.length > 0) {
                                        return data[0].kode_diagnosa || '-';
                                    } else {
                                        return '-';
                                    }
                                }
                            },
                            {
                                data: "diagnosa",
                                render: function(data, type, row) {
                                    if (Array.isArray(data) && data.length > 0) {
                                        return data[0].nama_diagnosa || '-';
                                    } else {
                                        return '-';
                                    }
                                }
                            },
                            {
                                data: "diagnosa",
                                render: function(data, type, row) {
                                    if (Array.isArray(data) && data.length > 1) {
                                        return data[1].kode_diagnosa || '-';
                                    } else {
                                        return '-';
                                    }
                                }
                            },
                            {
                                data: "diagnosa",
                                render: function(data, type, row) {
                                    if (Array.isArray(data) && data.length > 1) {
                                        return data[1].nama_diagnosa || '-';
                                    } else {
                                        return '-';
                                    }
                                }
                            },
                            {
                                data: "diagnosa",
                                render: function(data, type, row) {
                                    if (Array.isArray(data) && data.length > 2) {
                                        return data[2].kode_diagnosa || '-';
                                    } else {
                                        return '-';
                                    }
                                }
                            },
                            {
                                data: "diagnosa",
                                render: function(data, type, row) {
                                    if (Array.isArray(data) && data.length > 2) {
                                        return data[2].nama_diagnosa || '-';
                                    } else {
                                        return '-';
                                    }
                                }
                            },
                            {
                                data: "dokter_nama",
                                className: "col-3"
                            },
                            {
                                data: "aksi",
                                className: "px-0 col-3"
                            }
                        ],
                        autoWidth: false,
                        order: [
                            [1, "asc"],
                            [0, "asc"]
                        ],
                        buttons: [{
                                extend: "excelHtml5",
                                text: "Excel",
                                title: "Laporan Pendaftaran Tanggal: " + tglA + " s.d. " + tglB,
                                filename: "Laporan Pendaftaran Tanggal: " + tglA + " s.d. " + tglB
                            },
                            {
                                extend: "colvis",
                                text: "Tampilkan Kolom"
                            }
                        ]
                    }).buttons().container().appendTo("#report_wrapper .col-md-6:eq(0)");


                    Swal.close();
                },

                error: function(xhr, status, error) {
                    console.error("Error:", error);
                    Swal.fire({
                        icon: "error",
                        title: "Terjadi kesalahan saat mengambil data pasien...!!!\n" +
                            error,
                    });
                },
            });
        }


        function formatDate(date) {
            // Convert the input to a Date object if it isn't already
            if (!(date instanceof Date)) {
                date = new Date(date);
            }

            // Check if the date is valid
            if (isNaN(date)) {
                throw new Error("Invalid date");
            }

            let day = String(date.getDate()).padStart(2, "0");
            let month = String(date.getMonth() + 1).padStart(2, "0"); // getMonth() returns month from 0-11
            let year = date.getFullYear();
            return `${day}-${month}-${year}`;
        }

        var tglAwal;
        var tglAkhir;

        window.addEventListener("load", function() {
            setTodayDate();
            var today = new Date().toISOString().split("T")[0];
            $("#tanggal").val(today);

            // Inisialisasi tglAwal dan tglAkhir sebagai objek Moment.js
            // tglAwal = moment().subtract(30, "days").format("YYYY-MM-DD");
            tglAwal = moment().subtract(0, "days").format("YYYY-MM-DD");
            tglAkhir = moment().subtract(0, "days").format("YYYY-MM-DD");

            // Menetapkan nilai ke input tanggal
            $("#reservation, #tglJumlah").val(tglAwal + " to " + tglAkhir);

            // Date range picker
            $("#reservation, #tglJumlah").daterangepicker({
                startDate: tglAwal,
                endDate: tglAkhir,
                autoApply: true,
                locale: {
                    format: "YYYY-MM-DD",
                    separator: " to ",
                    applyLabel: "Apply",
                    cancelLabel: "Cancel",
                    customRangeLabel: "Custom Range",
                },
            });

            $("#reservation, #tglJumlah").on(
                "apply.daterangepicker",
                function(ev, picker) {
                    tglAwal = picker.startDate.format("YYYY-MM-DD");
                    tglAkhir = picker.endDate.format("YYYY-MM-DD");

                    // Lakukan sesuatu dengan startDate dan endDate
                    Swal.fire({
                        icon: "info",
                        title: "Sedang mencarikan data...!!!\n Proses lama jika mencari lebih dari 10 hari",
                        showConfirmButton: true,
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        },
                    });
                    reportPasien(tglAwal, tglAkhir);
                }
            );

            reportPasien(tglAwal, tglAkhir);

        });
    </script>

    </body>

    </html>
@endsection
