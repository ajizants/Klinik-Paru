@extends('Template.lte')

@section('content')
    <div class="card shadow">
        <div class="card-body">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a type="button" class="nav-link border border-primary active bg-blue" onclick=" toggleSections('#tab_1')"
                        id="link_tab_1"><b>Riwayat
                            Kunjungan</b></a>
                </li>
                <li class="nav-item">
                    <a type="button" class="nav-link border border-primary" onclick=" toggleSections('#tab_2')"><b>Rekap
                            Diagnosa</b></a>
                </li>
                <li class="nav-item">
                    <a type="button" class="nav-link border border-primary" onclick=" toggleSections('#tab_3')"><b>Jumlah
                            Diagnosa</b></a>
                </li>
            </ul>
            <div class="card shadow mb-4" id="tab_1">
                @include('Laporan.Pasien.riwayatKunjungan')
            </div>
            @include('Laporan.Pasien.rekapDX')
            @include('Laporan.Pasien.jumlahDX')
        </div>
    </div>

    <!-- my script -->
    <script src="{{ asset('js/template.js') }}"></script>
    <script>
        let pasien_no_rm = "";

        function cariRiwayat(tglAwal, tglAkhir) {
            console.log("🚀 ~ cariRiwayat ~ tglAkhir:", tglAkhir)
            console.log("🚀 ~ cariRiwayat ~ tglAwal:", tglAwal)
            Swal.fire({
                icon: "info",
                title: "Sedang mencarikan data Riwayat Pasien...!!!\n Proses lama jika mencari lebih dari 10 hari\n Mohon ditunggu...",
                showConfirmButton: true,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                },
            });
            reportPasien(tglAwal, tglAkhir);
        }

        function cariJumlah(tglAwal, tglAkhir) {
            // console.log("🚀 ~ cariJumlah ~ tglAkhir:", tglAkhir)
            // console.log("🚀 ~ cariJumlah ~ tglAwal:", tglAwal)

            Swal.fire({
                icon: "info",
                title: "Sedang mencarikan data Jumlah Diagnosa...!!!\n Proses lama jika mencari lebih dari 10 hari\n Mohon ditunggu...",
                showConfirmButton: true,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                },
            });
            getDiagnosisCounts(tglAwal, tglAkhir);
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
                            `<p>${item.pasien_no_rm}</p>
                            <p>${item.penjamin_nama}</p>
                            <button type="button" class="btn btn-primary mr-2"
                                    onclick="cariDataPasien('${item.pasien_no_rm}')" placeholder="Lihat">
                                    Rincian
                            </button>`;

                        item.rtrw = item.pasien_rt + "/" + item.pasien_rw;
                        item.alamatLengkap = item.kelurahan_nama + " " + item.rtrw + " " + item
                            .kecamatan_nama + " " + item.kabupaten_nama
                        let obatHtml = `
                                        <div>
                                            <table border="0" style="width:100%; border-collapse:collapse;">                                                
                                                <tbody>`;

                        item.resep_obat.forEach(obat => {
                            obat.resep_obat_detail.forEach(detail => {
                                let aturan = obat.aturan_pakai || "";
                                obatHtml += `
                                                <tr>
                                                    <td>${detail.nama_obat}</td>
                                                </tr>`;
                            });
                        });

                        obatHtml += `</tbody></table></div>`;
                        item.dataObats = obatHtml;
                    });


                    $("#report").DataTable({
                        data: dataPasien,
                        columns: [{
                                data: "antrean_nomor"
                            },
                            {
                                data: "tanggal"
                            },
                            // {
                            //     data: "penjamin_nama"
                            // },
                            {
                                data: "aksi"
                            },
                            {
                                data: "pasien_nama",
                                className: "col-2"
                            },
                            {
                                data: "alamatLengkap"
                            },
                            // {
                            //     data: "rtrw"
                            // },
                            // {
                            //     data: "kecamatan_nama"
                            // },
                            // {
                            //     data: "kabupaten_nama"
                            // },
                            {
                                data: "dokter_nama",
                                className: "col-3"
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
                                data: "subjek",
                                className: "col-3",
                                label: "Data Subjektif"
                            },
                            {
                                data: null, // gunakan null karena kita render manual
                                className: "col-3",
                                label: "Status Pulang",
                                render: function(data, type, row) {
                                    return `${row.ket_status_pasien_pulang} (${row.status_pasien_pulang})`;
                                }
                            },
                            {
                                data: "rencana_tindak_lanjut",
                                className: "col-3",
                                label: "RTL"
                            },
                            {
                                data: "dataObats",
                                className: "col-3",
                                label: "Obat"
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
                                title: "Riwayat DX Pasien Tanggal: " + tglA + " s.d. " + tglB,
                                filename: "Riwayat DX Pasien Tanggal: " + tglA + " s.d. " + tglB
                            },
                            {
                                extend: "pdfHtml5",
                                text: "PDF",
                                title: "Riwayat DX Pasien Tanggal: " + tglA + " s.d. " + tglB,
                                filename: "Riwayat DX Pasien Tanggal: " + tglA + " s.d. " + tglB
                            },
                            {
                                extend: "print",
                                text: "Cetak",
                                title: "Riwayat DX Pasien Tanggal: " + tglA + " s.d. " + tglB
                            },
                            {
                                extend: "copyHtml5",
                                text: "Salin"
                            },
                            {
                                extend: "colvis",
                                text: "Tampilkan Kolom"
                            }
                        ]
                    }).buttons().container().appendTo("#report_wrapper .col-md-6:eq(0)");


                    Swal.fire({
                        icon: "success",
                        title: "Berhasil mengambil data pasien...!!!",
                        timer: 1500
                    })
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

        function getDiagnosisCounts(tglAwal, tglAkhir) {
            var tglA = formatDate(new Date(tglAwal));
            var tglB = formatDate(new Date(tglAkhir));

            if ($.fn.DataTable.isDataTable("#diagnosisTable")) {
                var tabletindakan = $("#diagnosisTable").DataTable();
                tabletindakan.destroy();
            }

            // Clear the existing table body
            $('#diagnosisTable tbody').empty();

            $.ajax({
                url: '/api/riwayatKunjungan/jumlahDx',
                type: 'POST',
                data: {
                    tanggal_awal: tglAwal,
                    tanggal_akhir: tglAkhir
                },
                success: function(response) {
                    Swal.fire({
                        icon: "success",
                        title: "Berhasil mengambil data pasien...!!!",
                        timer: 1500
                    })
                    const diagnosisCounts = response.diagnosis_counts;
                    const data = [];
                    const addedDiagnoses = new Set(); // Track added diagnoses

                    // Process the diagnosis counts
                    for (const key in diagnosisCounts) {
                        for (const diagnosis in diagnosisCounts[key]) {
                            if (!addedDiagnoses.has(diagnosis)) {
                                addedDiagnoses.add(diagnosis); // Track the added diagnosis
                                data.push({
                                    diagnosa: diagnosis,
                                    kodeDx: diagnosis.split(' - ')[0],
                                    jumlahTotal: diagnosisCounts['Total'][diagnosis] || 0,
                                    jumlahUMUM: diagnosisCounts['UMUM'][diagnosis] || 0,
                                    jumlahBPJS: diagnosisCounts['BPJS'][diagnosis] || 0
                                });
                            }
                        }
                    }

                    // Populate the DataTable
                    data.forEach(item => {
                        $('#diagnosisTable tbody').append(`
                    <tr>
                        <td>${item.diagnosa}</td>
                        <td>${item.kodeDx}</td>
                        <td>${item.jumlahTotal}</td>
                        <td>${item.jumlahUMUM}</td>
                        <td>${item.jumlahBPJS}</td>
                    </tr>
                `);
                    });

                    // Re-initialize the DataTable
                    $('#diagnosisTable').DataTable({
                        order: [
                            [2, "dsc"]
                        ],
                        buttons: [{
                                extend: "excelHtml5",
                                text: "Excel",
                                title: "Laporan Jumlah Diagnosa Tanggal: " + tglA + " s.d. " + tglB,
                                filename: "Laporan Jumlah Diagnosa Tanggal: " + tglA + " s.d. " +
                                    tglB
                            },
                            {
                                extend: "pdfHtml5",
                                text: "PDF",
                                title: "Laporan Jumlah Diagnosa Tanggal: " + tglA + " s.d. " + tglB,
                                filename: "Laporan Jumlah Diagnosa Tanggal: " + tglA + " s.d. " +
                                    tglB
                            },
                            {
                                extend: "print",
                                text: "Cetak",
                                title: "Laporan Jumlah Diagnosa Tanggal: " + tglA + " s.d. " + tglB
                            },
                            {
                                extend: "copyHtml5",
                                text: "Salin"
                            }, {
                                extend: "colvis",
                                text: "Tampilkan Kolom"
                            }
                        ]
                    }).buttons().container().appendTo("#diagnosisTable_wrapper .col-md-6:eq(0)");
                },
                error: function(error) {
                    console.error("Error fetching diagnosis counts:", error);
                }
            });
        }

        var tglAwal;
        var tglAkhir;

        window.addEventListener("load", function() {
            setTodayDate();
            var today = new Date().toISOString().split("T")[0];
            $("#tanggal").val(today);
            // $("#no_rm").on("keyup", function(event) {
            //     if (event.key === "Enter") {
            //         event.preventDefault();
            //         cariRiwayatKunjunganPasien();
            //     }
            // });

            // Inisialisasi tglAwal dan tglAkhir sebagai objek Moment.js
            // tglAwal = moment().subtract(30, "days").format("YYYY-MM-DD");
            tglAwal = moment().subtract(0, "days").format("YYYY-MM-DD");
            tglAkhir = moment().subtract(0, "days").format("YYYY-MM-DD");

            // Menetapkan nilai ke input tanggal
            $("#reservation, #reservation2").val(tglAwal + " to " + tglAkhir);

            // Date range picker
            $("#reservation, #reservation2").daterangepicker({
                startDate: tglAwal,
                endDate: tglAkhir,
                // autoApply: true,
                locale: {
                    format: "YYYY-MM-DD",
                    separator: " to ",
                    applyLabel: "Apply",
                    cancelLabel: "Cancel",
                    customRangeLabel: "Custom Range",
                },
            });

            $("#reservation").on(
                "apply.daterangepicker",
                function(ev, picker) {
                    tglAwal = picker.startDate.format("YYYY-MM-DD");
                    tglAkhir = picker.endDate.format("YYYY-MM-DD");

                    cariRiwayat(tglAwal, tglAkhir);
                }
            );
            $("#reservation2").on(
                "apply.daterangepicker",
                function(ev, picker) {
                    tglAwal = picker.startDate.format("YYYY-MM-DD");
                    tglAkhir = picker.endDate.format("YYYY-MM-DD");

                    cariJumlah(tglAwal, tglAkhir);
                }
            );

            // reportPasien(tglAwal, tglAkhir);
            // getDiagnosisCounts(tglAwal, tglAkhir);

        });
    </script>
@endsection
